@extends('layouts.app')

@section('title', 'New Vehicle Reservation')
@section('page-title', 'Create New Vehicle Reservation')

@section('content')
<div class="max-w-5xl mx-auto space-y-6" x-data="bookingWizard()">
    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Form Inputs -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Step 1: Customer & Rental Type -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center mr-2">1</span>
                        Customer & Rental Mode
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Select Customer *</label>
                            <select name="customer_id" x-model="customerId" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                <option value="">-- Choose Customer --</option>
                                @foreach ($customers as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->name }} ({{ $c->phone }}) {{ $c->is_dl_verified ? '✓ DL Verified' : '⚠ DL Unverified' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-slate-400 mt-1">Need a new customer? <a href="{{ route('customers.create') }}" target="_blank" class="text-indigo-600 font-semibold underline">Register customer</a></p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Rental Type / Service *</label>
                            <select name="rental_type_id" x-model="rentalTypeId" @change="recalculatePricing()" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                @foreach ($rentalTypes as $rt)
                                    <option value="{{ $rt->id }}" data-is-chauffeur="{{ $rt->is_chauffeur_included ? '1' : '0' }}">
                                        {{ $rt->name }} (GST {{ $rt->gst_rate_percent }}%)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Time Window & Location -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center mr-2">2</span>
                        Schedule & Pickup Hub
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pickup Date & Time *</label>
                            <input type="datetime-local" name="pickup_datetime" x-model="pickupDatetime" @change="onScheduleChange()" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Return Date & Time *</label>
                            <input type="datetime-local" name="return_datetime" x-model="returnDatetime" @change="onScheduleChange()" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pickup Location *</label>
                            <select name="pickup_location_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Return Location *</label>
                            <select name="return_location_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                                @foreach ($locations as $loc)
                                    <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Vehicle Category & Available Allocation -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center">
                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center mr-2">3</span>
                        Vehicle Category & Physical Allocation
                    </h3>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Select Vehicle Category *</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($categories as $cat)
                                <label class="border rounded-xl p-3 flex flex-col justify-between cursor-pointer transition hover:border-indigo-500"
                                       :class="categoryId == '{{ $cat->id }}' ? 'border-indigo-600 bg-indigo-50/40 ring-2 ring-indigo-600/20' : 'border-slate-200 bg-white'">
                                    <input type="radio" name="vehicle_category_id" value="{{ $cat->id }}" x-model="categoryId" @change="onScheduleChange()" class="sr-only" required>
                                    <div>
                                        <div class="font-bold text-xs text-slate-900">{{ $cat->name }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $cat->seating_capacity }} Seats · {{ $cat->free_km_per_day }} km/day</div>
                                    </div>
                                    <div class="mt-2 text-xs font-bold text-indigo-700">₹{{ number_format($cat->base_daily_rate, 0) }}/day</div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Live Available Vehicles List -->
                    <div class="pt-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            Assign Specific Vehicle (Optional - Can allocate later)
                            <span x-show="availableVehicles.length > 0" class="text-emerald-600 text-xs font-normal" x-text="'(' + availableVehicles.length + ' vehicles currently available)'"></span>
                        </label>
                        <select name="assigned_vehicle_id" x-model="assignedVehicleId" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                            <option value="">-- Allocate Later / Auto Match --</option>
                            <template x-for="v in availableVehicles" :key="v.id">
                                <option :value="v.id" x-text="v.registration_number + ' - ' + v.model.brand.name + ' ' + v.model.name + ' (' + v.fuel_type + ' / ' + v.transmission + ')'"></option>
                            </template>
                        </select>
                        <p x-show="availableVehicles.length === 0 && categoryId" class="text-xs text-rose-500 mt-1">
                            ⚠ No fleet vehicles currently free in this segment for selected dates. You may proceed and allocate once a vehicle returns.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Trip Instructions / Special Requests</label>
                        <textarea name="notes" rows="2" placeholder="e.g. Flight AI 642 arrival, booster seat required..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Col: Live Pricing Estimate & Summary -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs sticky top-24 space-y-4">
                    <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Price & Deposit Estimate</h3>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between text-slate-500">
                            <span>Billed Duration:</span>
                            <span class="font-bold text-slate-800" x-text="pricing.total_days ? pricing.total_days + ' Day(s) (' + pricing.total_hours + ' hrs)' : '—'"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Complimentary Km:</span>
                            <span class="font-bold text-slate-800" x-text="pricing.free_km ? pricing.free_km + ' km' : '—'"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Base Rental:</span>
                            <span class="font-bold text-slate-800" x-text="'₹' + (pricing.base_rental_amount || 0).toLocaleString('en-IN')"></span>
                        </div>
                        <div class="flex justify-between text-slate-500" x-show="pricing.driver_allowance_amount > 0">
                            <span>Chauffeur Bata:</span>
                            <span class="font-bold text-slate-800" x-text="'₹' + (pricing.driver_allowance_amount || 0).toLocaleString('en-IN')"></span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>GST (Tax):</span>
                            <span class="font-bold text-slate-800" x-text="'₹' + (pricing.total_tax || 0).toLocaleString('en-IN')"></span>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex justify-between items-baseline">
                            <span class="text-sm font-bold text-slate-900">Rental Total:</span>
                            <span class="text-lg font-extrabold text-indigo-600" x-text="'₹' + (pricing.total_estimated_amount || 0).toLocaleString('en-IN')"></span>
                        </div>

                        <!-- Escrow Security Deposit Badge -->
                        <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200 text-amber-900 mt-2 space-y-1">
                            <div class="flex items-center justify-between font-bold text-xs">
                                <span>Security Deposit:</span>
                                <span x-text="'₹' + (pricing.security_deposit_amount || 0).toLocaleString('en-IN')"></span>
                            </div>
                            <p class="text-[10px] text-amber-700 leading-tight">
                                Held in segregated Escrow Liability account. Fully refundable upon vehicle inspection.
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20 mt-4">
                        Confirm & Create Reservation
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
function bookingWizard() {
    const now = new Date();
    const defaultStart = new Date(now.getTime() + 2 * 60 * 60 * 1000).toISOString().slice(0, 16);
    const defaultEnd = new Date(now.getTime() + 50 * 60 * 60 * 1000).toISOString().slice(0, 16);

    return {
        customerId: '',
        rentalTypeId: '{{ $rentalTypes->first()->id ?? 1 }}',
        categoryId: '{{ $categories->first()->id ?? 1 }}',
        pickupDatetime: defaultStart,
        returnDatetime: defaultEnd,
        assignedVehicleId: '',
        availableVehicles: [],
        pricing: {
            total_days: 2,
            total_hours: 48,
            free_km: 500,
            base_rental_amount: 3600,
            driver_allowance_amount: 0,
            total_tax: 648,
            total_estimated_amount: 4248,
            security_deposit_amount: 4000
        },

        init() {
            this.onScheduleChange();
        },

        onScheduleChange() {
            this.checkAvailability();
            this.recalculatePricing();
        },

        checkAvailability() {
            if (!this.categoryId || !this.pickupDatetime || !this.returnDatetime) return;

            fetch('{{ route('api.availability') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    category_id: this.categoryId,
                    pickup_datetime: this.pickupDatetime,
                    return_datetime: this.returnDatetime
                })
            })
            .then(res => res.json())
            .then(data => {
                this.availableVehicles = data.vehicles || [];
            })
            .catch(err => console.error(err));
        },

        recalculatePricing() {
            if (!this.categoryId || !this.rentalTypeId || !this.pickupDatetime || !this.returnDatetime) return;

            fetch('{{ route('api.pricing') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    category_id: this.categoryId,
                    rental_type_id: this.rentalTypeId,
                    pickup_datetime: this.pickupDatetime,
                    return_datetime: this.returnDatetime
                })
            })
            .then(res => res.json())
            .then(data => {
                this.pricing = data;
            })
            .catch(err => console.error(err));
        }
    };
}
</script>
@endpush
@endsection

