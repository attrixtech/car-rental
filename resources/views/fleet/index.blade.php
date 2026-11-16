@extends('layouts.app')

@section('title', 'Fleet Management')
@section('page-title', 'Fleet Inventory & Vehicle Registry')

@section('content')
<div class="space-y-6">
    <!-- Filters & Action Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center space-x-1 overflow-x-auto text-xs font-semibold">
            <a href="{{ route('fleet.index') }}" class="px-3.5 py-2 rounded-xl transition {{ !$status ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                All Vehicles
            </a>
            <a href="{{ route('fleet.index', ['status' => 'available']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'available' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Available
            </a>
            <a href="{{ route('fleet.index', ['status' => 'rented']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'rented' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                On-Rent
            </a>
            <a href="{{ route('fleet.index', ['status' => 'maintenance']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'maintenance' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                In Service
            </a>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <form action="{{ route('fleet.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search plate #, VIN, model..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs w-60 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            <a href="{{ route('fleet.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-xs flex items-center">
                + Add Vehicle
            </a>
        </div>
    </div>

    <!-- Fleet Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Registration Plate</th>
                        <th class="px-6 py-3.5">Model & Trim</th>
                        <th class="px-6 py-3.5">Segment</th>
                        <th class="px-6 py-3.5">Hub / Branch</th>
                        <th class="px-6 py-3.5">Fuel & Odometer</th>
                        <th class="px-6 py-3.5">Tariff (Daily)</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($vehicles as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('fleet.show', $v->id) }}" class="text-indigo-600 hover:underline">
                                    {{ $v->registration_number }}
                                </a>
                                <div class="text-[11px] font-sans font-normal text-slate-400">VIN: {{ substr($v->vin_number, -8) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $v->model->brand->name }} {{ $v->model->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $v->color }} · {{ $v->manufacture_year }} · {{ ucfirst($v->transmission) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                    {{ $v->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $v->branch->name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono font-semibold text-slate-800">{{ number_format($v->current_odometer) }} km</div>
                                <div class="text-[11px] text-slate-400">{{ ucfirst($v->fuel_type) }} · {{ $v->current_fuel_level }}/8 Tank</div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                ₹{{ number_format($v->daily_rental_price, 0) }}/day
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'reserved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'rented' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'maintenance' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'decommissioned' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses[$v->status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('fleet.show', $v->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    Profile & History →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                No vehicles found. <a href="{{ route('fleet.create') }}" class="text-indigo-600 font-semibold underline">Add first fleet vehicle</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($vehicles->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

