@extends('layouts.app')

@section('title', 'Add Fleet Vehicle')
@section('page-title', 'Add New Fleet Vehicle')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 mb-6">
            Vehicle Registration & Technical Specifications
        </h3>

        <form action="{{ route('fleet.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Registration Number (Plate) *</label>
                    <input type="text" name="registration_number" placeholder="e.g. MH 02 FB 4521" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono font-bold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">VIN / Chassis Number</label>
                    <input type="text" name="vin_number" placeholder="17-digit VIN" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Assigned Branch / Yard *</label>
                    <select name="branch_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Vehicle Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Model & Trim *</label>
                    <select name="model_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        @foreach ($models as $m)
                            <option value="{{ $m->id }}">{{ $m->brand->name }} {{ $m->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Exterior Color *</label>
                    <input type="text" name="color" value="Pearl White" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Manufacture Year *</label>
                    <input type="number" name="manufacture_year" value="2024" min="2015" max="2026" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Fuel Type *</label>
                    <select name="fuel_type" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="petrol">Petrol</option>
                        <option value="diesel" selected>Diesel</option>
                        <option value="electric">Electric (EV)</option>
                        <option value="cng">CNG</option>
                        <option value="hybrid">Strong Hybrid</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Transmission *</label>
                    <select name="transmission" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="manual">Manual</option>
                        <option value="automatic" selected>Automatic</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Seating Capacity *</label>
                    <input type="number" name="seating_capacity" value="5" min="2" max="12" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Current Odometer (km) *</label>
                    <input type="number" name="current_odometer" value="0" min="0" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Fuel Tank Capacity (L) *</label>
                    <input type="number" name="fuel_tank_capacity" value="45" min="10" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Daily Rental Tariff (₹) *</label>
                    <input type="number" name="daily_rental_price" value="2500" min="100" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-bold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Hourly Tariff (₹)</label>
                    <input type="number" name="hourly_rental_price" value="200" min="10" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Insurance Expiry Date</label>
                    <input type="date" name="insurance_expiry_date" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Vehicle Equipment & Notes</label>
                <textarea name="notes" rows="2" placeholder="e.g. Reverse parking sensor, Android Auto, Fastag installed..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-600 focus:outline-none"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('fleet.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 shadow-md shadow-indigo-600/20">
                    Register Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

