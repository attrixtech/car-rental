@extends('layouts.app')

@section('title', 'Fleet & Rental Operations Dashboard')
@section('page-title', 'Fleet & Rental Operations Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Top KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Fleet Card -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Fleet</p>
                <div class="flex items-baseline space-x-2 mt-1">
                    <h3 class="text-2xl font-bold text-slate-900">{{ $totalVehicles }}</h3>
                    <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        {{ $fleetUtilization }}% Rented
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">{{ $availableVehicles }} Available · {{ $maintenanceVehicles }} In Service</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
            </div>
        </div>

        <!-- Active Bookings -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active Bookings</p>
                <div class="flex items-baseline space-x-2 mt-1">
                    <h3 class="text-2xl font-bold text-slate-900">{{ $activeBookings }}</h3>
                    <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                        {{ $todaysBookings }} Today
                    </span>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">{{ $totalCustomers }} Verified Customers</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        <!-- Escrow Deposits -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Escrow Deposits Held</p>
                <div class="flex items-baseline space-x-2 mt-1">
                    <h3 class="text-2xl font-bold text-slate-900">₹{{ number_format($activeDepositsHeld, 0) }}</h3>
                </div>
                <p class="text-[11px] text-amber-600 font-medium mt-1">Segregated Escrow Liability</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Revenue Collected -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Revenue Settled</p>
                <div class="flex items-baseline space-x-2 mt-1">
                    <h3 class="text-2xl font-bold text-slate-900">₹{{ number_format($totalRevenue, 0) }}</h3>
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Pending: ₹{{ number_format($pendingReceivables, 0) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Fleet Status Chart -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <h4 class="text-sm font-bold text-slate-900 mb-4">Live Fleet Availability Status</h4>
            <div class="h-60 relative flex items-center justify-center">
                <canvas id="fleetStatusChart"></canvas>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-4 text-center text-xs">
                <div class="p-2 rounded-lg bg-emerald-50 text-emerald-800">
                    <div class="font-bold text-lg">{{ $availableVehicles }}</div>
                    <div class="text-[10px] uppercase font-semibold">Available</div>
                </div>
                <div class="p-2 rounded-lg bg-indigo-50 text-indigo-800">
                    <div class="font-bold text-lg">{{ $rentedVehicles }}</div>
                    <div class="text-[10px] uppercase font-semibold">On-Rent</div>
                </div>
                <div class="p-2 rounded-lg bg-amber-50 text-amber-800">
                    <div class="font-bold text-lg">{{ $maintenanceVehicles }}</div>
                    <div class="text-[10px] uppercase font-semibold">Service</div>
                </div>
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-sm font-bold text-slate-900">Fleet Inventory by Segment</h4>
                <a href="{{ route('fleet.create') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">+ Add Vehicle</a>
            </div>
            <div class="h-64">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Reservations & Live Operations -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Recent Booking Reservations</h4>
                <p class="text-xs text-slate-500">Live operational status, dispatches, and returns</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('bookings.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View All ({{ $activeBookings }}) →</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Booking #</th>
                        <th class="px-6 py-3">Customer & KYC</th>
                        <th class="px-6 py-3">Rental Type & Category</th>
                        <th class="px-6 py-3">Assigned Vehicle</th>
                        <th class="px-6 py-3">Dates</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Amount</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($recentBookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-3.5 font-bold text-indigo-600">
                                <a href="{{ route('bookings.show', $b->id) }}">{{ $b->booking_number }}</a>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="font-semibold text-slate-800">{{ $b->customer->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $b->customer->phone }}</div>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="font-medium text-slate-700">{{ $b->rentalType->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $b->category->name }}</div>
                            </td>
                            <td class="px-6 py-3.5">
                                @if ($b->assignedVehicle)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded font-mono font-medium text-xs bg-slate-100 text-slate-800">
                                        {{ $b->assignedVehicle->registration_number }}
                                    </span>
                                @else
                                    <span class="text-amber-600 font-medium text-[11px]">Pending Allocation</span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">
                                <div>{{ $b->pickup_datetime->format('d M H:i') }}</div>
                                <div class="text-[11px] text-slate-400">to {{ $b->return_datetime->format('d M H:i') }}</div>
                            </td>
                            <td class="px-6 py-3.5">
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
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses[$b->booking_status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $b->booking_status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-slate-900">
                                ₹{{ number_format($b->total_estimated_amount, 0) }}
                            </td>
                            <td class="px-6 py-3.5 text-right space-x-2">
                                @if ($b->booking_status === 'allocated' || ($b->booking_status === 'confirmed' && $b->assigned_vehicle_id))
                                    <a href="{{ route('operations.handover', $b->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                        Handover →
                                    </a>
                                @elseif ($b->booking_status === 'active')
                                    <a href="{{ route('operations.return', $b->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-600 text-white hover:bg-purple-700 transition">
                                        Return & Inspect →
                                    </a>
                                @else
                                    <a href="{{ route('bookings.show', $b->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                        View Details
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-slate-400">
                                No active bookings recorded. <a href="{{ route('bookings.create') }}" class="text-indigo-600 font-semibold underline">Create first reservation</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Fleet Status Donut Chart
    const statusCtx = document.getElementById('fleetStatusChart')?.getContext('2d');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'On-Rent', 'In Maintenance'],
                datasets: [{
                    data: [{{ $availableVehicles }}, {{ $rentedVehicles }}, {{ $maintenanceVehicles }}],
                    backgroundColor: ['#10B981', '#6366F1', '#F59E0B'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '72%'
            }
        });
    }

    // Category Bar Chart
    const catCtx = document.getElementById('categoryChart')?.getContext('2d');
    if (catCtx) {
        new Chart(catCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($categoryCounts->pluck('category.name')) !!},
                datasets: [{
                    label: 'Vehicles Count',
                    data: {!! json_encode($categoryCounts->pluck('count')) !!},
                    backgroundColor: '#6366F1',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
});
</script>
@endpush
@endsection

