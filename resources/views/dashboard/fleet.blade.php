@extends('layouts.app')

@section('title', 'Fleet Manager Operations Dashboard')
@section('page-title', 'Fleet Management & Maintenance Command')

@section('content')
<div class="space-y-6">
    <!-- Top Fleet Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Fleet Inventory</p>
                <div class="flex items-baseline space-x-2 mt-1">
                    <h3 class="text-2xl font-bold text-slate-900">{{ $totalVehicles }}</h3>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        {{ $fleetUtilization }}% Utilization
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">All commercial vehicles</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                🚗
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Available (Ready)</p>
                <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $availableVehicles }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Inspected & ready for dispatch</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                ✓
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Currently On-Rent</p>
                <div class="text-2xl font-bold text-indigo-600 mt-1">{{ $rentedVehicles }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Active customer trips</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                🛣️
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Maintenance & Service</p>
                <div class="text-2xl font-bold text-amber-600 mt-1">{{ $maintenanceVehicles }}</div>
                <p class="text-[11px] text-slate-400 mt-1">Workshop service & body shop</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold">
                🔧
            </div>
        </div>
    </div>

    <!-- Compliance Alerts & Scheduled Maintenance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Insurance & Permit Expiring Soon -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <h3 class="text-sm font-bold text-slate-900">Upcoming Insurance Expirations (Next 30 Days)</h3>
                </div>
                <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full">{{ $expiringInsurance->count() }} Alert(s)</span>
            </div>

            <div class="space-y-2 text-xs">
                @forelse ($expiringInsurance as $veh)
                    <div class="p-3 rounded-xl border border-rose-100 bg-rose-50/40 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-bold text-slate-900">{{ $veh->registration_number }}</div>
                            <div class="text-[11px] text-slate-500">{{ $veh->model->brand->name }} {{ $veh->model->name }} ({{ $veh->category->name }})</div>
                        </div>
                        <div class="text-right">
                            <span class="font-bold text-rose-600">{{ $veh->insurance_expiry_date->format('d M Y') }}</span>
                            <div class="text-[10px] text-rose-500">Expires in {{ now()->diffInDays($veh->insurance_expiry_date) }} days</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400">
                        ✓ All fleet insurance and compliance policies are up to date!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Active Maintenance Orders -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-amber-500 font-bold">🔧</span>
                    <h3 class="text-sm font-bold text-slate-900">Active Workshop Work Orders</h3>
                </div>
                <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full">{{ $activeMaintenance->count() }} Orders</span>
            </div>

            <div class="space-y-2 text-xs">
                @forelse ($activeMaintenance as $m)
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-bold text-slate-800">{{ $m->vehicle->registration_number }}</div>
                            <div class="text-[11px] text-slate-500">{{ ucfirst(str_replace('_', ' ', $m->maintenance_type)) }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-amber-100 text-amber-800 border border-amber-200">
                            {{ $m->status }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400">
                        No pending maintenance work orders. All fleet units operational.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Fleet Status Grid -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-900">Recent Fleet Inventory</h4>
            <div class="flex items-center space-x-3">
                <a href="{{ route('fleet.calendar') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Fleet Calendar →</a>
                <a href="{{ route('fleet.create') }}" class="px-3 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700">+ Add Vehicle</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Registration #</th>
                        <th class="px-6 py-3.5">Model</th>
                        <th class="px-6 py-3.5">Segment</th>
                        <th class="px-6 py-3.5">Odometer</th>
                        <th class="px-6 py-3.5">Fuel Level</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($vehicles as $v)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                <a href="{{ route('fleet.show', $v->id) }}">{{ $v->registration_number }}</a>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $v->model->brand->name }} {{ $v->model->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $v->category->name }}</td>
                            <td class="px-6 py-4 font-mono font-semibold">{{ number_format($v->current_odometer) }} km</td>
                            <td class="px-6 py-4 font-semibold text-slate-700">{{ $v->current_fuel_level }}/8 Tank</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $v->status === 'available' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($v->status === 'rented' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-amber-50 text-amber-700 border-amber-200') }}">
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('fleet.show', $v->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Manage Fleet Unit →</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

