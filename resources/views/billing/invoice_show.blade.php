@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page-title', 'Tax Invoice — ' . $invoice->invoice_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
        <!-- Top Invoice Meta -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md border border-indigo-100">GST Tax Invoice</span>
                <h3 class="text-xl font-mono font-bold text-slate-900 mt-2">{{ $invoice->invoice_number }}</h3>
                <p class="text-xs text-slate-500">Date: {{ $invoice->invoice_date->format('d M Y') }} · Due: {{ $invoice->due_date->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <a href="{{ route('operations.invoice.pdf', $invoice->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-xs inline-flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Official PDF
                </a>
            </div>
        </div>

        <!-- Supplier & Customer Details -->
        <div class="grid grid-cols-2 gap-6 text-xs">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="font-bold uppercase text-[10px] text-slate-400">Supplier Details</span>
                <div class="font-bold text-slate-800 text-sm">{{ $invoice->company->name }}</div>
                <div class="text-slate-600">GSTIN: <span class="font-mono font-semibold">{{ $invoice->company->gstin ?? 'xxxxx' }}</span></div>
                <div class="text-slate-500">{{ $invoice->company->address }}, {{ $invoice->company->city }}</div>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <span class="font-bold uppercase text-[10px] text-slate-400">Billed To Customer</span>
                <div class="font-bold text-slate-800 text-sm">{{ $invoice->customer->name }}</div>
                <div class="text-slate-600">Phone: {{ $invoice->customer->phone }}</div>
                <div class="text-slate-500">Booking: #{{ $invoice->booking->booking_number }}</div>
            </div>
        </div>

        <!-- Itemized Table -->
        <div class="border border-slate-200 rounded-xl overflow-hidden text-xs">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3">Description</th>
                        <th class="px-4 py-3">SAC Code</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Rate (₹)</th>
                        <th class="px-4 py-3">Taxable (₹)</th>
                        <th class="px-4 py-3">GST Rate</th>
                        <th class="px-4 py-3 text-right">Tax (₹)</th>
                        <th class="px-4 py-3 text-right">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $item->item_description }}</td>
                            <td class="px-4 py-3 font-mono text-slate-500">{{ $item->sac_code }}</td>
                            <td class="px-4 py-3">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 font-mono">₹{{ number_format($item->unit_rate, 2) }}</td>
                            <td class="px-4 py-3 font-mono">₹{{ number_format($item->total_amount, 2) }}</td>
                            <td class="px-4 py-3">{{ $item->tax_rate }}%</td>
                            <td class="px-4 py-3 font-mono text-right">₹{{ number_format($item->tax_amount, 2) }}</td>
                            <td class="px-4 py-3 font-mono font-bold text-right">₹{{ number_format($item->total_amount + $item->tax_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Balance -->
        <div class="flex justify-end text-xs">
            <div class="w-72 space-y-2 border-t border-slate-200 pt-3">
                <div class="flex justify-between text-slate-500">
                    <span>Taxable Value:</span>
                    <span class="font-mono font-semibold text-slate-800">₹{{ number_format($invoice->taxable_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>CGST (9%):</span>
                    <span class="font-mono text-slate-800">₹{{ number_format($invoice->cgst_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>SGST (9%):</span>
                    <span class="font-mono text-slate-800">₹{{ number_format($invoice->sgst_amount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-sm text-slate-900 pt-2 border-t border-slate-200">
                    <span>Grand Total:</span>
                    <span class="font-mono text-indigo-700">₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-emerald-700 font-semibold">
                    <span>Paid / Adjusted:</span>
                    <span class="font-mono">₹{{ number_format($invoice->paid_amount, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-sm {{ $invoice->balance_due > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                    <span>Balance Due:</span>
                    <span class="font-mono">₹{{ number_format($invoice->balance_due, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Record Payment if Balance Due -->
        @if ($invoice->balance_due > 0)
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Collect Outstanding Payment</h4>
                <form action="{{ route('billing.invoice.payment', $invoice->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 text-xs">
                    @csrf
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Amount to Collect (₹)</label>
                        <input type="number" name="amount" value="{{ $invoice->balance_due }}" max="{{ $invoice->balance_due }}" required class="w-full p-2 rounded-lg border border-slate-300 font-mono font-bold">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Payment Method</label>
                        <select name="payment_method" class="w-full p-2 rounded-lg border border-slate-300">
                            <option value="upi">UPI / QR Code</option>
                            <option value="card">Credit / Debit Card</option>
                            <option value="cash">Cash (Counter)</option>
                            <option value="bank_transfer">NEFT / RTGS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-600 mb-1">Transaction Ref #</label>
                        <input type="text" name="transaction_reference" placeholder="e.g. UPI/628194" class="w-full p-2 rounded-lg border border-slate-300">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full py-2 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection

