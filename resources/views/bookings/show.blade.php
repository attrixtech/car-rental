@extends('layouts.app')

@section('title', 'Booking ' . $booking->booking_number)
@section('page-title', 'Booking #' . $booking->booking_number)

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <span class="text-xs text-slate-400 font-medium">Status:</span>
            @php
                $statusClasses = [
                    'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'allocated' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'returned' => 'bg-purple-50 text-purple-700 border-purple-200',
                    'settled' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusClasses[$booking->booking_status] ?? 'bg-slate-50 text-slate-600' }}">
                {{ $booking->booking_status }}
            </span>
            <span class="text-xs text-slate-400">· Source: {{ ucfirst($booking->booking_source) }}</span>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Download Agreement -->
            <a href="{{ route('operations.agreement.pdf', $booking->id) }}" target="_blank" class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition flex items-center">
                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Rental Agreement PDF
            </a>

            <!-- Action buttons based on status -->
            @if ($booking->booking_status === 'confirmed' && !$booking->assigned_vehicle_id)
                <button onclick="document.getElementById('allocationModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-xs">
                    Assign Fleet Vehicle
                </button>
            @elseif ($booking->booking_status === 'allocated' || ($booking->booking_status === 'confirmed' && $booking->assigned_vehicle_id))
                <a href="{{ route('operations.handover', $booking->id) }}" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition shadow-xs flex items-center">
                    Initiate Handover →
                </a>
            @elseif ($booking->booking_status === 'active')
                <a href="{{ route('operations.return', $booking->id) }}" class="px-4 py-2 rounded-xl bg-purple-600 text-white text-xs font-semibold hover:bg-purple-700 transition shadow-xs flex items-center">
                    Vehicle Return & Inspection →
                </a>
            @endif

            @if ($booking->booking_status !== 'cancelled' && $booking->booking_status !== 'settled')
                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                    @csrf
                    <input type="hidden" name="reason" value="Cancelled by admin">
                    <button type="submit" class="px-3.5 py-2 rounded-xl border border-rose-200 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition">
                        Cancel
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Booking Overview Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1 & 2: Trip & Operational Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Schedule Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Trip & Schedule Specification</h3>
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 font-medium">Pickup Window:</span>
                        <div class="font-bold text-slate-800 text-sm mt-0.5">{{ $booking->pickup_datetime->format('l, d M Y - H:i A') }}</div>
                        <div class="text-slate-500 mt-1">Hub: {{ $booking->pickupLocation->name }} ({{ $booking->pickupLocation->city }})</div>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium">Scheduled Return:</span>
                        <div class="font-bold text-slate-800 text-sm mt-0.5">{{ $booking->return_datetime->format('l, d M Y - H:i A') }}</div>
                        <div class="text-slate-500 mt-1">Return Hub: {{ $booking->returnLocation->name }} ({{ $booking->returnLocation->city }})</div>
                    </div>
                </div>
            </div>

            <!-- Vehicle & Driver Allocation Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Vehicle & Chauffeur Assignment</h3>
                    @if ($booking->booking_status !== 'settled' && $booking->booking_status !== 'cancelled')
                        <button onclick="document.getElementById('allocationModal').classList.remove('hidden')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                            {{ $booking->assignedVehicle ? 'Reallocate Fleet' : '+ Allocate Fleet' }}
                        </button>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                    <!-- Vehicle Info -->
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <span class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Fleet Vehicle</span>
                        @if ($booking->assignedVehicle)
                            <div class="font-mono text-base font-bold text-slate-900 mt-1">
                                {{ $booking->assignedVehicle->registration_number }}
                            </div>
                            <div class="font-medium text-slate-700 mt-0.5">
                                {{ $booking->assignedVehicle->model->brand->name }} {{ $booking->assignedVehicle->model->name }}
                            </div>
                            <div class="text-slate-500 mt-1">
                                {{ ucfirst($booking->assignedVehicle->fuel_type) }} · {{ ucfirst($booking->assignedVehicle->transmission) }} · Odo: {{ number_format($booking->assignedVehicle->current_odometer) }} km
                            </div>
                        @else
                            <div class="text-rose-600 font-bold mt-1">Vehicle Not Yet Assigned</div>
                            <p class="text-slate-400 mt-1">Requested Segment: {{ $booking->category->name }}</p>
                        @endif
                    </div>

                    <!-- Driver Info -->
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                        <span class="text-slate-400 font-medium uppercase tracking-wider text-[10px]">Chauffeur / Driver</span>
                        @if ($booking->assignedDriver)
                            <div class="text-base font-bold text-slate-900 mt-1">
                                {{ $booking->assignedDriver->name }}
                            </div>
                            <div class="text-slate-500 mt-0.5">
                                Phone: {{ $booking->assignedDriver->phone }} · Badge: {{ $booking->assignedDriver->badge_number ?? 'Commercial' }}
                            </div>
                        @else
                            @if ($booking->isChauffeurDriven())
                                <div class="text-amber-600 font-bold mt-1">Driver Pending Assignment</div>
                            @else
                                <div class="text-slate-600 font-semibold mt-1">Self-Drive Rental (No Chauffeur)</div>
                                <p class="text-slate-400 mt-1">Customer drives directly</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Handover & Return Details if available -->
            @if ($booking->handover || $booking->vehicleReturn)
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Operational Handover & Return Audits</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        @if ($booking->handover)
                            <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100 space-y-2">
                                <span class="text-[10px] uppercase font-bold text-emerald-800">Vehicle Handover (Check-Out)</span>
                                <div class="font-bold text-slate-800">Dispatched: {{ $booking->handover->handover_datetime->format('d M Y H:i') }}</div>
                                <div class="text-slate-600">Start Odometer: <span class="font-mono font-bold">{{ number_format($booking->handover->odometer_start) }} km</span></div>
                                <div class="text-slate-600">Start Fuel: <span class="font-bold">{{ $booking->handover->fuel_level_start }}/8 Tank</span></div>
                                <div class="text-emerald-700 text-[11px]">✓ Checklist, Spare Wheel & Tools Verified</div>
                            </div>
                        @endif

                        @if ($booking->vehicleReturn)
                            <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-100 space-y-2">
                                <span class="text-[10px] uppercase font-bold text-purple-800">Vehicle Return (Check-In)</span>
                                <div class="font-bold text-slate-800">Received: {{ $booking->vehicleReturn->return_datetime->format('d M Y H:i') }}</div>
                                <div class="text-slate-600">End Odometer: <span class="font-mono font-bold">{{ number_format($booking->vehicleReturn->odometer_end) }} km</span></div>
                                <div class="text-slate-600">End Fuel: <span class="font-bold">{{ $booking->vehicleReturn->fuel_level_end }}/8 Tank</span></div>
                                <div class="text-purple-700 text-[11px]">Excess Km: {{ $booking->vehicleReturn->excess_km }} km · Late: {{ $booking->vehicleReturn->late_hours }} hr(s)</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Col 3: Customer & Financials -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900">Customer Profile</h3>
                    <a href="{{ route('customers.show', $booking->customer->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View CRM →</a>
                </div>
                <div class="text-xs space-y-2">
                    <div class="font-bold text-sm text-slate-900">{{ $booking->customer->name }}</div>
                    <div class="text-slate-600">Phone: <span class="font-medium text-slate-800">{{ $booking->customer->phone }}</span></div>
                    <div class="text-slate-600">Email: <span class="font-medium text-slate-800">{{ $booking->customer->email ?? 'N/A' }}</span></div>
                    <div class="pt-2">
                        @if ($booking->customer->is_dl_verified)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                ✓ DL Verified: {{ $booking->customer->driving_license_number }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                ⚠ Driving License Pending Verification
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Financial Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Billing & Financial Breakdown</h3>

                <div class="space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Base Rental:</span>
                        <span class="font-medium text-slate-800">₹{{ number_format($booking->base_rental_amount, 2) }}</span>
                    </div>
                    @if ($booking->driver_allowance_amount > 0)
                        <div class="flex justify-between text-slate-500">
                            <span>Chauffeur Bata:</span>
                            <span class="font-medium text-slate-800">₹{{ number_format($booking->driver_allowance_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-slate-500">
                        <span>GST ({{ $booking->rentalType->gst_rate_percent }}%):</span>
                        <span class="font-medium text-slate-800">₹{{ number_format($booking->tax_amount, 2) }}</span>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                        <span class="font-bold text-slate-900 text-sm">Rental Total:</span>
                        <span class="font-extrabold text-indigo-600 text-base">₹{{ number_format($booking->total_estimated_amount, 2) }}</span>
                    </div>

                    <!-- Escrow Security Deposit -->
                    <div class="mt-4 p-3.5 rounded-xl border {{ $booking->deposit?->status === 'refunded' ? 'bg-slate-50 border-slate-200' : 'bg-amber-50/80 border-amber-200' }}">
                        <div class="flex items-center justify-between font-bold text-xs text-amber-900">
                            <span>Escrow Security Deposit:</span>
                            <span>₹{{ number_format($booking->security_deposit_amount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-amber-700 mt-1">
                            <span>Status:</span>
                            <span class="font-bold uppercase">{{ $booking->deposit?->status ?? 'Held' }}</span>
                        </div>
                    </div>
                </div>

                @if ($booking->invoices->isNotEmpty())
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-xs font-semibold text-slate-700 block mb-2">Generated Tax Invoices:</span>
                        @foreach ($booking->invoices as $inv)
                            <div class="flex items-center justify-between text-xs p-2 rounded-lg bg-slate-50 border border-slate-200">
                                <span class="font-bold text-indigo-600">{{ $inv->invoice_number }}</span>
                                <a href="{{ route('operations.invoice.pdf', $inv->id) }}" target="_blank" class="text-indigo-600 font-semibold hover:underline">Download PDF</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Fleet Allocation Modal -->
<div id="allocationModal" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Allocate Vehicle & Driver</h3>
            <button onclick="document.getElementById('allocationModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>

        <form action="{{ route('bookings.allocate', $booking->id) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Select Available Vehicle *</label>
                <select name="vehicle_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    <option value="">-- Choose Fleet Vehicle --</option>
                    @foreach ($availableVehicles as $v)
                        <option value="{{ $v->id }}" {{ $booking->assigned_vehicle_id == $v->id ? 'selected' : '' }}>
                            {{ $v->registration_number }} — {{ $v->model->brand->name }} {{ $v->model->name }} ({{ $v->color }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-slate-400 mt-1">Only vehicles without scheduling conflicts in this category are shown.</p>
            </div>

            @if ($booking->isChauffeurDriven())
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Assign Chauffeur *</label>
                    <select name="driver_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="">-- Choose Driver --</option>
                        @foreach ($availableDrivers as $d)
                            <option value="{{ $d->id }}" {{ $booking->assigned_driver_id == $d->id ? 'selected' : '' }}>
                                {{ $d->name }} ({{ $d->phone }}) — Rating {{ $d->rating }}★
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('allocationModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 shadow-xs">
                    Confirm Allocation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

