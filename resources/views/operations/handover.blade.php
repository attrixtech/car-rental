@extends('layouts.app')

@section('title', 'Vehicle Handover Dispatch')
@section('page-title', 'Vehicle Handover Checklist — Booking #' . $booking->booking_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Digital Handover Inspection (Check-Out)</h3>
                <p class="text-xs text-slate-500">Record baseline vehicle condition, fuel level, and starting odometer before dispatch</p>
            </div>
            <div class="text-right">
                <span class="font-mono text-sm font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-200">
                    {{ $booking->assignedVehicle->registration_number }}
                </span>
                <div class="text-[11px] text-slate-400 mt-1">{{ $booking->assignedVehicle->model->brand->name }} {{ $booking->assignedVehicle->model->name }}</div>
            </div>
        </div>

        <form action="{{ route('operations.handover.submit', $booking->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Meter & Fuel Readings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/70 p-5 rounded-2xl border border-slate-200/70">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Starting Odometer Reading (km) *
                    </label>
                    <input type="number" name="odometer_start" value="{{ $booking->assignedVehicle->current_odometer }}" required min="0" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Verify odometer on physical dashboard.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Starting Fuel Level (Eighths of tank) *
                    </label>
                    <select name="fuel_level_start" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="8.00" selected>8/8 — 100% Full Tank</option>
                        <option value="7.00">7/8 — 87.5%</option>
                        <option value="6.00">6/8 — 75% (3/4 Tank)</option>
                        <option value="5.00">5/8 — 62.5%</option>
                        <option value="4.00">4/8 — 50% (Half Tank)</option>
                        <option value="3.00">3/8 — 37.5%</option>
                        <option value="2.00">2/8 — 25% (Quarter Tank)</option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Vehicle should return with equal fuel level.</p>
                </div>
            </div>

            <!-- Mandatory Vehicle Inspection Checklist -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 mb-3">Vehicle Physical & Document Checklist</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <label class="flex items-center p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="cleanliness_interior" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                        <div>
                            <div class="font-bold text-slate-800">Interior Clean & Sanitized</div>
                            <div class="text-[11px] text-slate-400">Seats, floor mats, and AC vents clean</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="cleanliness_exterior" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                        <div>
                            <div class="font-bold text-slate-800">Exterior Washed & Polished</div>
                            <div class="text-[11px] text-slate-400">Windshield and mirrors crystal clear</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="spare_tyre_present" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                        <div>
                            <div class="font-bold text-slate-800">Spare Tyre Inspected</div>
                            <div class="text-[11px] text-slate-400">Adequate pressure and thread depth</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer">
                        <input type="checkbox" name="jack_tools_present" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                        <div>
                            <div class="font-bold text-slate-800">Jack & Toolkit in Boot</div>
                            <div class="text-[11px] text-slate-400">Emergency triangle, jack, and spanner</div>
                        </div>
                    </label>

                    <label class="flex items-center p-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer sm:col-span-2">
                        <input type="checkbox" name="documents_in_car" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                        <div>
                            <div class="font-bold text-slate-800">Registration, Insurance & Pollution Certificate in Glove Box</div>
                            <div class="text-[11px] text-slate-400">Mandatory vehicle compliance folder verified</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Pre-existing condition & notes -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Pre-existing Scratch / Dent Notes (Baseline)
                </label>
                <textarea name="notes" rows="2" placeholder="e.g. Minor 2-inch hairline scratch on rear left bumper; interior pristine..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
            </div>

            <!-- Customer Signature Acknowledgment -->
            <div class="p-4 rounded-xl bg-indigo-50/50 border border-indigo-100 flex items-center justify-between">
                <div>
                    <div class="font-bold text-xs text-indigo-950">Customer Verbal & Physical Acknowledgment</div>
                    <div class="text-[11px] text-indigo-700 mt-0.5">
                        Customer {{ $booking->customer->name }} has inspected the car and accepts terms of agreement.
                    </div>
                </div>
                <input type="hidden" name="customer_signature" value="DIGITAL_ACK_{{ time() }}">
            </div>

            <!-- Submit buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('bookings.show', $booking->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-emerald-700 shadow-md shadow-emerald-600/20">
                    Complete Handover & Release Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

