<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\SecurityDeposit;
use App\Models\Payment;
use App\Models\DepositTransaction;
use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function invoices(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $invoices = Invoice::with(['customer', 'booking'])
            ->when($status, fn($q) => $q->where('payment_status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('billing.invoices', compact('invoices', 'status', 'search'));
    }

    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['company', 'branch', 'customer', 'booking.assignedVehicle.model.brand', 'items', 'payments']);
        return view('billing.invoice_show', compact('invoice'));
    }

    public function deposits(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $deposits = SecurityDeposit::with(['customer', 'booking.assignedVehicle.model'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where('deposit_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('billing.deposits', compact('deposits', 'status', 'search'));
    }

    public function refundDeposit(Request $request, SecurityDeposit $deposit)
    {
        $validated = $request->validate([
            'refund_reference' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $refundAmount = $deposit->deposit_amount - $deposit->deductions_amount;

        $deposit->update([
            'status' => 'refunded',
            'refunded_amount' => $refundAmount,
            'refunded_at' => now(),
            'refund_reference' => $validated['refund_reference'],
            'notes' => ($deposit->notes ? $deposit->notes . "\n" : '') . "Refund Processed: " . ($validated['notes'] ?? ''),
        ]);

        DepositTransaction::create([
            'deposit_id' => $deposit->id,
            'transaction_type' => 'refund',
            'amount' => $refundAmount,
            'notes' => "Refund issued via {$deposit->payment_method} Ref: {$validated['refund_reference']}",
            'processed_by' => auth()->id(),
        ]);

        return back()->with('success', "Security deposit ₹" . number_format($refundAmount, 2) . " marked as refunded.");
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $invoice->balance_due,
            'payment_method' => 'required|string',
            'transaction_reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($invoice, $validated) {
            Payment::create([
                'company_id' => $invoice->company_id,
                'invoice_id' => $invoice->id,
                'booking_id' => $invoice->booking_id,
                'customer_id' => $invoice->customer_id,
                'payment_number' => 'PAY-' . date('Y') . '-' . strtoupper(Str::random(5)),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $validated['transaction_reference'] ?? null,
                'status' => 'successful',
                'received_by' => auth()->id(),
                'notes' => $validated['notes'] ?? null,
            ]);

            $newPaid = $invoice->paid_amount + $validated['amount'];
            $newBalance = max(0, $invoice->total_amount - $newPaid);

            $invoice->update([
                'paid_amount' => $newPaid,
                'balance_due' => $newBalance,
                'payment_status' => $newBalance == 0 ? 'paid' : 'partially_paid',
            ]);
        });

        return back()->with('success', 'Payment recorded successfully.');
    }

    public function ledger()
    {
        $accounts = ChartOfAccount::with(['lines' => fn($q) => $q->orderBy('id', 'desc')->take(20)])->get();
        $journalEntries = JournalEntry::with('lines.account')->orderBy('id', 'desc')->paginate(15);

        return view('billing.ledger', compact('accounts', 'journalEntries'));
    }
}

