<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tax Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; margin: 20px; }
        .header { border-bottom: 2px solid #0f172a; padding-bottom: 12px; margin-bottom: 15px; }
        .company-name { font-size: 17px; font-weight: bold; color: #0f172a; }
        .meta-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .meta-table td { padding: 6px; vertical-align: top; }
        .box { background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 8px; }
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .item-table th, .item-table td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        .item-table th { background-color: #f1f5f9; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .totals-table { width: 45%; margin-left: auto; border-collapse: collapse; margin-bottom: 20px; }
        .totals-table td { padding: 4px 8px; }
        .footer { font-size: 9px; color: #64748b; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 10px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company-name">{{ $invoice->company->name ?? 'Agra Car Rental Service' }}</div>
                    <div>{{ $invoice->company->address }}, {{ $invoice->company->city }}</div>
                    <div><strong>GSTIN:</strong> {{ $invoice->company->gstin ?? 'xxxxx' }} | <strong>PAN:</strong> {{ $invoice->company->pan ?? 'AABCD1234E' }}</div>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <h2 style="margin: 0; color: #0f172a; font-size: 18px;">TAX INVOICE</h2>
                    <div style="font-weight: bold; font-family: monospace; font-size: 12px; margin-top: 4px;">{{ $invoice->invoice_number }}</div>
                    <div>Date: {{ $invoice->invoice_date->format('d M Y') }}</div>
                    <div>Place of Supply: {{ $invoice->place_of_supply }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 50%;">
                <div class="box">
                    <div style="font-weight: bold; font-size: 10px; color: #64748b; text-transform: uppercase;">Billed To (Customer):</div>
                    <div style="font-weight: bold; font-size: 12px; margin-top: 2px;">{{ $invoice->customer->name }}</div>
                    <div>Phone: {{ $invoice->customer->phone }} | Email: {{ $invoice->customer->email ?? 'N/A' }}</div>
                    <div>Address: {{ $invoice->customer->address ?? '' }}, {{ $invoice->customer->city }}, {{ $invoice->customer->state }}</div>
                    <div>Customer GSTIN: {{ $invoice->customer_gstin ?? 'Unregistered Consumer' }}</div>
                </div>
            </td>
            <td style="width: 50%;">
                <div class="box">
                    <div style="font-weight: bold; font-size: 10px; color: #64748b; text-transform: uppercase;">Booking Reference:</div>
                    <div style="font-weight: bold; font-size: 12px; margin-top: 2px;">Booking #: {{ $invoice->booking->booking_number }}</div>
                    <div>Vehicle: {{ $invoice->booking->assignedVehicle->registration_number ?? 'N/A' }} ({{ $invoice->booking->assignedVehicle->model->name ?? '' }})</div>
                    <div>Pickup: {{ $invoice->booking->pickup_datetime->format('d M Y') }} | Return: {{ $invoice->booking->return_datetime->format('d M Y') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Item / Service Description</th>
                <th style="width: 12%;">HSN/SAC</th>
                <th style="width: 8%;">Qty</th>
                <th style="width: 15%; text-align: right;">Unit Rate (₹)</th>
                <th style="width: 15%; text-align: right;">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><strong>{{ $item->item_description }}</strong></td>
                    <td style="font-family: monospace;">{{ $item->sac_code }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="text-align: right; font-family: monospace;">{{ number_format($item->unit_rate, 2) }}</td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold;">{{ number_format($item->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td><strong>Taxable Value:</strong></td>
            <td style="text-align: right; font-family: monospace;">₹{{ number_format($invoice->taxable_amount, 2) }}</td>
        </tr>
        <tr>
            <td>CGST (9%):</td>
            <td style="text-align: right; font-family: monospace;">₹{{ number_format($invoice->cgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td>SGST (9%):</td>
            <td style="text-align: right; font-family: monospace;">₹{{ number_format($invoice->sgst_amount, 2) }}</td>
        </tr>
        <tr style="border-top: 1px solid #cbd5e1; border-bottom: 2px solid #0f172a; font-weight: bold; font-size: 13px;">
            <td>Total Invoice Amount:</td>
            <td style="text-align: right; font-family: monospace; color: #1e1b4b;">₹{{ number_format($invoice->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="color: #047857; font-weight: bold;">Amount Paid / Deposit Adjusted:</td>
            <td style="text-align: right; font-family: monospace; color: #047857;">₹{{ number_format($invoice->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; color: {{ $invoice->balance_due > 0 ? '#b91c1c' : '#64748b' }};">Balance Payable:</td>
            <td style="text-align: right; font-family: monospace; font-weight: bold; color: {{ $invoice->balance_due > 0 ? '#b91c1c' : '#64748b' }};">
                ₹{{ number_format($invoice->balance_due, 2) }}
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>This is a computer generated legal tax invoice issued in accordance with Section 31 of CGST Act, 2017.</p>
        <p>{{ $invoice->company->name ?? 'Agra Car Rental Service' }} · Registered Office: 307, Prateek Centre, Sanjay Place, Agra 282002</p>
    </div>
</body>
</html>

