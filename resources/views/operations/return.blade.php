@extends('layouts.app')

@section('title', 'Vehicle Return & Inspection')
@section('page-title', 'Vehicle Return Inspection — Booking #' . $booking->booking_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="returnInspection()">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Vehicle Return & Check-In Inspection</h3>
                <p class="text-xs text-slate-500">Compare ending meters against dispatch baseline and compute automated settlement</p>
            </div>
            <div class="text-right">
                <span class="font-mono text-sm font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-200">
                    {{ $booking->assignedVehicle->registration_number }}
                </span>
                <div class="text-[11px] text-slate-400 mt-1">Start Odo: {{ number_format($booking->handover->odometer_start ?? 0) }} km · Start Fuel: {{ $booking->handover->fuel_level_start ?? 8 }}/8</div>
            </div>
        </div>

        <form action="{{ route('operations.return.submit', $booking->id) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Return Meters Input -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/70 p-5 rounded-2xl border border-slate-200/70">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Ending Odometer Reading (km) *
                    </label>
                    <input type="number" name="odometer_end" x-model.number="endOdometer" @input="calculateSettlement()" required min="{{ $booking->handover->odometer_start ?? 0 }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 font-mono text-sm font-bold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">
                        Total Driven: <span class="font-bold text-slate-700" x-text="Math.max(0, endOdometer - startOdometer) + ' km'"></span>
                        (Allowed: {{ $freeKm }} km)
                    </p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Return Fuel Level (Eighths of tank) *
                    </label>
                    <select name="fuel_level_end" x-model.number="endFuel" @change="calculateSettlement()" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="8.00">8/8 — Full Tank</option>
                        <option value="7.00">7/8</option>
                        <option value="6.00">6/8 — 75%</option>
                        <option value="5.00">5/8</option>
                        <option value="4.00">4/8 — Half Tank</option>
                        <option value="3.00">3/8</option>
                        <option value="2.00">2/8 — Quarter Tank</option>
                        <option value="1.00">1/8 — Empty Reserve</option>
                    </select>
                    <p class="text-[11px] text-slate-400 mt-1">Start was {{ $booking->handover->fuel_level_start ?? 8 }}/8.</p>
                </div>
            </div>

            <!-- Cleaning & Minor Conditions -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white">
                <label class="flex items-center cursor-pointer text-xs">
                    <input type="checkbox" name="cleaning_required" value="1" x-model="cleaningRequired" @change="calculateSettlement()" class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                    <div>
                        <div class="font-bold text-slate-800">Interior Deep Cleaning Required (+₹500)</div>
                        <div class="text-[11px] text-slate-400">Mud, pet hair, smoking odor, or heavy spills requiring professional upholstery wash</div>
                    </div>
                </label>
            </div>

            <!-- Damage Assessment -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">New Physical Damages Discovered (if any)</h4>
                    <button type="button" @click="addDamageRow()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">+ Add Damage Incident</button>
                </div>

                <template x-for="(dmg, idx) in damages" :key="idx">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-xs">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Location on Car</label>
                            <select :name="'damages[' + idx + '][body_location]'" x-model="dmg.body_location" class="w-full p-2 rounded-lg border border-slate-300">
                                <option value="front_bumper">Front Bumper</option>
                                <option value="rear_bumper">Rear Bumper</option>
                                <option value="left_doors">Left Doors / Fender</option>
                                <option value="right_doors">Right Doors / Quarter</option>
                                <option value="windshield">Windshield / Glass</option>
                                <option value="alloy_wheels">Alloy / Wheels</option>
                                <option value="interior">Interior Dashboard / Seats</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Severity</label>
                            <select :name="'damages[' + idx + '][severity]'" x-model="dmg.severity" class="w-full p-2 rounded-lg border border-slate-300">
                                <option value="minor">Minor Scratch / Scuff</option>
                                <option value="moderate">Moderate Dent / Crack</option>
                                <option value="major">Major Structural Damage</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Estimated Repair Cost (₹)</label>
                            <input type="number" :name="'damages[' + idx + '][estimated_repair_cost]'" x-model.number="dmg.estimated_repair_cost" @input="calculateSettlement()" placeholder="0.00" class="w-full p-2 rounded-lg border border-slate-300 font-mono font-bold">
                        </div>
                        <div class="flex items-end pb-1">
                            <button type="button" @click="removeDamageRow(idx)" class="text-rose-600 hover:text-rose-800 font-semibold text-xs">Remove ✕</button>
                        </div>
                        <div class="md:col-span-4">
                            <input type="text" :name="'damages[' + idx + '][description]'" x-model="dmg.description" placeholder="Describe damage details, e.g. 5cm dent on passenger door" class="w-full p-2 rounded-lg border border-slate-300">
                        </div>
                    </div>
                </template>
            </div>

            <!-- Live Settlement Summary & Escrow Deposit Adjustment -->
            <div class="p-6 rounded-2xl bg-indigo-50/70 border border-indigo-100 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-950 border-b border-indigo-200/60 pb-2">
                    Automated Return Settlement Calculation
                </h4>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500">Excess Distance:</span>
                        <div class="font-bold text-slate-900 mt-0.5" x-text="excessKm + ' km (₹' + excessKmCharge + ')'"></div>
                    </div>
                    <div>
                        <span class="text-slate-500">Fuel Shortfall:</span>
                        <div class="font-bold text-slate-900 mt-0.5" x-text="fuelShortfallCharge > 0 ? '₹' + fuelShortfallCharge : 'None (Full)'"></div>
                    </div>
                    <div>
                        <span class="text-slate-500">Damage Deductions:</span>
                        <div class="font-bold text-slate-900 mt-0.5" x-text="'₹' + totalDamageCost"></div>
                    </div>
                    <div>
                        <span class="text-slate-500">Deep Cleaning:</span>
                        <div class="font-bold text-slate-900 mt-0.5" x-text="cleaningRequired ? '₹500.00' : '₹0.00'"></div>
                    </div>
                </div>

                <div class="pt-3 border-t border-indigo-200/60 flex items-center justify-between text-sm">
                    <div>
                        <span class="text-xs text-slate-600">Total Deductions:</span>
                        <div class="font-bold text-rose-700 text-base" x-text="'₹' + totalDeductions"></div>
                    </div>

                    <div class="text-right">
                        <span class="text-xs text-slate-600">Deposit Held: ₹{{ number_format($booking->deposit?->deposit_amount ?? 5000, 2) }}</span>
                        <div class="font-extrabold text-emerald-700 text-lg" x-text="'Refund Due: ₹' + refundDue"></div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Return Receiving Notes / Remarks
                </label>
                <textarea name="notes" rows="2" placeholder="Inspection verified in presence of customer..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('bookings.show', $booking->id) }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-purple-700 shadow-md shadow-purple-600/20">
                    Finalize Return & Generate Tax Invoice
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function returnInspection() {
    return {
        startOdometer: {{ $booking->handover->odometer_start ?? 0 }},
        endOdometer: {{ ($booking->handover->odometer_start ?? 0) + 120 }},
        startFuel: {{ $booking->handover->fuel_level_start ?? 8.00 }},
        endFuel: 8.00,
        cleaningRequired: false,
        freeKm: {{ $booking->category->free_km_per_day * 2 }},
        excessKmRate: {{ $booking->category->excess_km_rate ?? 12.00 }},
        depositHeld: {{ $booking->deposit?->deposit_amount ?? 5000.00 }},
        damages: [],
        excessKm: 0,
        excessKmCharge: 0,
        fuelShortfallCharge: 0,
        totalDamageCost: 0,
        totalDeductions: 0,
        refundDue: {{ $booking->deposit?->deposit_amount ?? 5000.00 }},

        init() {
            this.calculateSettlement();
        },

        addDamageRow() {
            this.damages.push({
                body_location: 'front_bumper',
                severity: 'minor',
                estimated_repair_cost: 0,
                description: ''
            });
            this.calculateSettlement();
        },

        removeDamageRow(idx) {
            this.damages.splice(idx, 1);
            this.calculateSettlement();
        },

        calculateSettlement() {
            const driven = Math.max(0, this.endOdometer - this.startOdometer);
            this.excessKm = Math.max(0, driven - this.freeKm);
            this.excessKmCharge = Math.round(this.excessKm * this.excessKmRate);

            // Fuel deficit: tank capacity ~45L, fuel price ~105
            const fuelDiff = Math.max(0, this.startFuel - this.endFuel);
            this.fuelShortfallCharge = Math.round((fuelDiff / 8.0) * 45 * 105);

            // Damages
            this.totalDamageCost = this.damages.reduce((sum, d) => sum + (Number(d.estimated_repair_cost) || 0), 0);

            // Cleaning
            const cleaningFee = this.cleaningRequired ? 500 : 0;

            this.totalDeductions = this.excessKmCharge + this.fuelShortfallCharge + this.totalDamageCost + cleaningFee;
            this.refundDue = Math.max(0, this.depositHeld - this.totalDeductions);
        }
    };
}
</script>
@endpush
@endsection

