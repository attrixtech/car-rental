@extends('layouts.app')

@section('title', 'Vehicle ' . $vehicle->registration_number)
@section('page-title', 'Vehicle Profile — ' . $vehicle->registration_number)

@section('content')
<div class="space-y-6">
    <!-- Top Action Card -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                🚗
            </div>
            <div>
                <h3 class="font-mono text-lg font-extrabold text-slate-900">{{ $vehicle->registration_number }}</h3>
                <p class="text-xs text-slate-500">{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }} · {{ $vehicle->color }} · {{ $vehicle->manufacture_year }}</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            @php
                $statusClasses = [
                    'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'reserved' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'rented' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                    'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'decommissioned' => 'bg-slate-100 text-slate-600 border-slate-200',
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $statusClasses[$vehicle->status] ?? 'bg-slate-50 text-slate-600' }}">
                {{ $vehicle->status }}
            </span>
            <button onclick="document.getElementById('statusModal').classList.remove('hidden')" class="px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                Update Status
            </button>
        </div>
    </div>

    <!-- Specs Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Current Meter</span>
            <div class="font-mono text-xl font-bold text-slate-900 mt-1">{{ number_format($vehicle->current_odometer) }} km</div>
            <div class="text-[11px] text-slate-400 mt-1">Fuel: {{ $vehicle->current_fuel_level }}/8 Tank</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Tariff Rate</span>
            <div class="text-xl font-bold text-slate-900 mt-1">₹{{ number_format($vehicle->daily_rental_price, 0) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Per 24h Day · Base slab</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Transmission & Fuel</span>
            <div class="text-base font-bold text-slate-900 mt-1">{{ ucfirst($vehicle->transmission) }} · {{ ucfirst($vehicle->fuel_type) }}</div>
            <div class="text-[11px] text-slate-400 mt-1">Capacity: {{ $vehicle->seating_capacity }} passengers</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-slate-400 font-semibold text-[10px] uppercase tracking-wider">Insurance Expiry</span>
            <div class="text-base font-bold {{ $vehicle->insurance_expiry_date && $vehicle->insurance_expiry_date->isPast() ? 'text-rose-600' : 'text-slate-900' }} mt-1">
                {{ $vehicle->insurance_expiry_date ? $vehicle->insurance_expiry_date->format('d M Y') : 'Valid' }}
            </div>
            <div class="text-[11px] text-emerald-600 font-medium mt-1">Compliance Clean</div>
        </div>
    </div>

    <!-- Booking History on this vehicle -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-900">Rental Trip & Reservation History</h4>
            <span class="text-xs text-slate-400">{{ $vehicle->bookings->count() }} completed trips</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Booking #</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Schedule</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($vehicle->bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-3.5 font-bold text-indigo-600">
                                <a href="{{ route('bookings.show', $b->id) }}">{{ $b->booking_number }}</a>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">{{ $b->customer->name }}</td>
                            <td class="px-6 py-3.5 text-slate-600">
                                {{ $b->pickup_datetime->format('d M Y H:i') }} - {{ $b->return_datetime->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border bg-slate-50 text-slate-700">
                                    {{ $b->booking_status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('bookings.show', $b->id) }}" class="text-indigo-600 font-semibold hover:underline">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400">No previous trips recorded for this vehicle.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Change Vehicle Status</h3>
        <form action="{{ route('fleet.update', $vehicle->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="registration_number" value="{{ $vehicle->registration_number }}">
            <input type="hidden" name="branch_id" value="{{ $vehicle->branch_id }}">
            <input type="hidden" name="category_id" value="{{ $vehicle->category_id }}">
            <input type="hidden" name="model_id" value="{{ $vehicle->model_id }}">
            <input type="hidden" name="color" value="{{ $vehicle->color }}">
            <input type="hidden" name="manufacture_year" value="{{ $vehicle->manufacture_year }}">
            <input type="hidden" name="fuel_type" value="{{ $vehicle->fuel_type }}">
            <input type="hidden" name="transmission" value="{{ $vehicle->transmission }}">
            <input type="hidden" name="seating_capacity" value="{{ $vehicle->seating_capacity }}">
            <input type="hidden" name="daily_rental_price" value="{{ $vehicle->daily_rental_price }}">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Select Status</label>
                <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                    <option value="available" {{ $vehicle->status === 'available' ? 'selected' : '' }}>Available for Rental</option>
                    <option value="maintenance" {{ $vehicle->status === 'maintenance' ? 'selected' : '' }}>In Scheduled Maintenance</option>
                    <option value="in_repair" {{ $vehicle->status === 'in_repair' ? 'selected' : '' }}>Under Repair</option>
                    <option value="decommissioned" {{ $vehicle->status === 'decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('statusModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700">Save Status</button>
            </div>
        </form>
    </div>
</div>
@endsection

