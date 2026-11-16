<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rental Agreement #{{ $booking->booking_number }}</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; margin: 20px; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; color: #1e1b4b; }
        .meta-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .meta-table td { padding: 6px; vertical-align: top; }
        .section-title { font-size: 13px; font-weight: bold; color: #4338ca; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; margin-top: 15px; margin-bottom: 8px; text-transform: uppercase; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .details-table th, .details-table td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        .details-table th { background-color: #f1f5f9; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .terms { font-size: 9px; color: #475569; text-align: justify; margin-top: 15px; }
        .signatures { margin-top: 40px; width: 100%; border-collapse: collapse; }
        .signatures td { width: 50%; padding: 20px; border-top: 1px dashed #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="company-name">{{ $booking->company->name ?? 'DriveSphere Mobility Solutions Pvt. Ltd.' }}</div>
                    <div>{{ $booking->company->address ?? 'Floor 4, Velocity Tower, Andheri East, Mumbai, India' }}</div>
                    <div>GSTIN: {{ $booking->company->gstin ?? '27AABCD1234E1Z5' }} | Contact: {{ $booking->company->phone ?? '+91 22 6789 4400' }}</div>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <h2 style="margin: 0; color: #4f46e5; font-size: 20px;">RENTAL AGREEMENT</h2>
                    <div style="font-weight: bold; margin-top: 4px;">Agreement #: AGR-{{ $booking->booking_number }}</div>
                    <div>Date: {{ now()->format('d M Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 50%; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <div style="font-weight: bold; color: #475569; font-size: 10px; text-transform: uppercase;">Customer / Renter Details:</div>
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px;">{{ $booking->customer->name }}</div>
                <div>Phone: {{ $booking->customer->phone }} | Email: {{ $booking->customer->email ?? 'N/A' }}</div>
                <div>Driving License: <strong>{{ $booking->customer->driving_license_number ?? 'Verified' }}</strong></div>
                <div>Address: {{ $booking->customer->address ?? '' }}, {{ $booking->customer->city }}, {{ $booking->customer->state }}</div>
            </td>
            <td style="width: 50%; background-color: #f8fafc; border: 1px solid #e2e8f0;">
                <div style="font-weight: bold; color: #475569; font-size: 10px; text-transform: uppercase;">Vehicle & Hub Details:</div>
                <div style="font-size: 13px; font-weight: bold; margin-top: 3px; font-family: monospace;">
                    {{ $booking->assignedVehicle->registration_number ?? 'VEHICLE ALLOCATED' }}
                </div>
                <div>Model: {{ $booking->assignedVehicle->model->brand->name ?? '' }} {{ $booking->assignedVehicle->model->name ?? '' }}</div>
                <div>Pickup Hub: {{ $booking->pickupLocation->name }} ({{ $booking->pickup_datetime->format('d M Y H:i') }})</div>
                <div>Return Hub: {{ $booking->returnLocation->name }} ({{ $booking->return_datetime->format('d M Y H:i') }})</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Rental Terms, Tariffs & Escrow Deposit</div>
    <table class="details-table">
        <thead>
            <tr>
                <th>Service Description</th>
                <th>Complimentary Allowance</th>
                <th>Excess Km Rate</th>
                <th>Escrow Deposit (Held)</th>
                <th style="text-align: right;">Estimated Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>{{ $booking->rentalType->name }}</strong> — {{ $booking->category->name }}</td>
                <td>{{ $booking->category->free_km_per_day * ceil(max(1, $booking->pickup_datetime->diffInHours($booking->return_datetime)) / 24) }} km</td>
                <td>₹{{ number_format($booking->category->excess_km_rate, 2) }}/km</td>
                <td>₹{{ number_format($booking->security_deposit_amount, 2) }}</td>
                <td style="text-align: right; font-weight: bold;">₹{{ number_format($booking->total_estimated_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Legal Terms & Operational Undertaking</div>
    <div class="terms">
        <p>1. <strong>Authorized Driver & Legality:</strong> The Renter agrees that only individuals holding a verified and valid Indian or International driving license shall operate the vehicle. Sub-leasing, racing, commercial haulage, or driving under the influence of alcohol/narcotics is strictly prohibited.</p>
        <p>2. <strong>Speed Limits & Traffic Challans:</strong> The maximum allowable speed is 100 km/h on expressways and 80 km/h on highways. All speeding penalties, e-challans, and toll violations incurred during the rental tenure are the sole liability of the Renter.</p>
        <p>3. <strong>Damage & Security Deposit Forfeiture:</strong> The vehicle must be returned in the same condition as received at handover. In the event of new dents, scratches, glass damage, or fuel shortfall, the repair and refueling expenses shall be directly adjusted against the Escrow Security Deposit. Shortfalls exceeding the deposit amount shall be payable immediately.</p>
        <p>4. <strong>Late Return Policy:</strong> A grace period of 60 minutes is permitted. Extensions must be requested and approved in writing 4 hours prior to scheduled return; unauthorized late returns will be billed at standard hourly excess rates.</p>
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div>Renter's Digital / Physical Signature:</div>
                <div style="margin-top: 25px; font-weight: bold;">{{ $booking->customer->name }}</div>
                <div style="font-size: 9px; color: #64748b;">Accepted via DriveSphere Digital Dispatch Desk</div>
            </td>
            <td>
                <div>For DriveSphere Mobility Solutions Pvt. Ltd.:</div>
                <div style="margin-top: 25px; font-weight: bold;">Authorized Dispatch Officer</div>
                <div style="font-size: 9px; color: #64748b;">{{ $booking->branch->name }}</div>
            </td>
        </tr>
    </table>
</body>
</html>

