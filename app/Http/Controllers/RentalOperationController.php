<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\VehicleHandover;
use App\Models\VehicleReturn;
use App\Models\RentalAgreement;
use App\Models\VehicleDamageReport;
use App\Models\RentalSettlement;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\RentalOperationService;
use Barryvdh\DomPDF\Facade\Pdf;

class RentalOperationController extends Controller
{
    public function __construct(
        protected RentalOperationService $operationService
    ) {}

    public function showHandoverForm(Booking $booking)
    {
        if (!$booking->assigned_vehicle_id) {
            return redirect()->route('bookings.show', $booking->id)->with('error', 'Please allocate a vehicle before initiating handover.');
        }

        $booking->load(['customer', 'assignedVehicle.model.brand', 'rentalType', 'deposit']);
        return view('operations.handover', compact('booking'));
    }

    public function submitHandover(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'odometer_start' => 'required|integer|min:0',
            'fuel_level_start' => 'required|numeric|min:1|max:8',
            'cleanliness_interior' => 'nullable|boolean',
            'cleanliness_exterior' => 'nullable|boolean',
            'spare_tyre_present' => 'nullable|boolean',
            'jack_tools_present' => 'nullable|boolean',
            'documents_in_car' => 'nullable|boolean',
            'customer_signature' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->operationService->processHandover($booking, $validated, auth()->id());

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Vehicle handover completed. Rental is now ACTIVE.');
    }

    public function showReturnForm(Booking $booking)
    {
        if ($booking->booking_status !== 'active') {
            return redirect()->route('bookings.show', $booking->id)->with('error', 'Booking is not currently active for return.');
        }

        $booking->load(['customer', 'assignedVehicle.model.brand', 'handover', 'deposit', 'category']);
        return view('operations.return', compact('booking'));
    }

    public function submitReturn(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'odometer_end' => 'required|integer|gte:' . ($booking->handover->odometer_start ?? 0),
            'fuel_level_end' => 'required|numeric|min:0|max:8',
            'cleaning_required' => 'nullable|boolean',
            'damages' => 'nullable|array',
            'damages.*.severity' => 'nullable|string',
            'damages.*.body_location' => 'nullable|string',
            'damages.*.description' => 'nullable|string',
            'damages.*.estimated_repair_cost' => 'nullable|numeric|min:0',
            'customer_signature' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $result = $this->operationService->processReturn($booking, $validated, auth()->id());

        // Create standard GST Tax Invoice for completed rental
        $this->generateFinalTaxInvoice($booking, $result['settlement']);

        return redirect()->route('bookings.show', $booking->id)->with('success', 'Vehicle check-in completed. Rental settled and deposit adjusted.');
    }

    protected function generateFinalTaxInvoice(Booking $booking, RentalSettlement $settlement): Invoice
    {
        $company = $booking->company;
        $branch = $booking->branch;
        $customer = $booking->customer;

        $invoiceNumber = 'INV-' . date('Y') . '-' . sprintf('%05d', $booking->id);

        $invoice = Invoice::create([
            'company_id' => $company->id ?? 1,
            'branch_id' => $branch->id ?? 1,
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->toDateString(),
            'place_of_supply' => $branch->state ?? 'Maharashtra',
            'customer_gstin' => null,
            'subtotal' => $settlement->gross_final_amount - $settlement->tax_charged,
            'discount_amount' => $settlement->discount_applied,
            'taxable_amount' => $settlement->gross_final_amount - $settlement->tax_charged,
            'cgst_rate' => 9.00,
            'cgst_amount' => round($settlement->tax_charged / 2, 2),
            'sgst_rate' => 9.00,
            'sgst_amount' => round($settlement->tax_charged / 2, 2),
            'total_amount' => $settlement->gross_final_amount,
            'paid_amount' => $settlement->gross_final_amount - $settlement->balance_payable_by_customer,
            'balance_due' => $settlement->balance_payable_by_customer,
            'payment_status' => $settlement->balance_payable_by_customer > 0 ? 'partially_paid' : 'paid',
        ]);

        // Base rental line
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'item_description' => "Vehicle Rental ({$booking->category->name} - {$booking->rentalType->name})",
            'sac_code' => $booking->rentalType->sac_code,
            'quantity' => 1.00,
            'unit_rate' => $settlement->base_rental_charged,
            'total_amount' => $settlement->base_rental_charged,
            'tax_rate' => $booking->rentalType->gst_rate_percent,
            'tax_amount' => round($settlement->base_rental_charged * ($booking->rentalType->gst_rate_percent / 100), 2),
        ]);

        // Excess km line if any
        if ($settlement->excess_km_charged > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_description' => 'Excess Kilometer Charges',
                'sac_code' => $booking->rentalType->sac_code,
                'quantity' => 1.00,
                'unit_rate' => $settlement->excess_km_charged,
                'total_amount' => $settlement->excess_km_charged,
                'tax_rate' => 18.00,
                'tax_amount' => round($settlement->excess_km_charged * 0.18, 2),
            ]);
        }

        // Fuel & cleaning shortfall if any
        if ($settlement->fuel_deficit_charged > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_description' => 'Refueling & Cleaning Surcharges',
                'sac_code' => '997311',
                'quantity' => 1.00,
                'unit_rate' => $settlement->fuel_deficit_charged,
                'total_amount' => $settlement->fuel_deficit_charged,
                'tax_rate' => 18.00,
                'tax_amount' => round($settlement->fuel_deficit_charged * 0.18, 2),
            ]);
        }

        // Damage recovery if any
        if ($settlement->damage_charges > 0) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'item_description' => 'Vehicle Damage Recovery & Repair Assessment',
                'sac_code' => '998714',
                'quantity' => 1.00,
                'unit_rate' => $settlement->damage_charges,
                'total_amount' => $settlement->damage_charges,
                'tax_rate' => 18.00,
                'tax_amount' => round($settlement->damage_charges * 0.18, 2),
            ]);
        }

        return $invoice;
    }

    public function downloadAgreement(Booking $booking)
    {
        $booking->load(['customer', 'assignedVehicle.model.brand', 'rentalType', 'branch.company', 'agreement']);
        $pdf = Pdf::loadView('pdf.rental_agreement', compact('booking'));
        return $pdf->download("Rental_Agreement_{$booking->booking_number}.pdf");
    }

    public function downloadInvoice(Invoice $invoice)
    {
        $invoice->load(['company', 'branch', 'customer', 'booking.assignedVehicle.model.brand', 'items']);
        $pdf = Pdf::loadView('pdf.tax_invoice', compact('invoice'));
        return $pdf->download("Tax_Invoice_{$invoice->invoice_number}.pdf");
    }
}

