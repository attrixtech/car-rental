@extends('layouts.app')

@section('title', 'Counter Operations & Dispatch')
@section('page-title', 'Rental Counter Operations & Vehicle Dispatch')

@section('content')
<div class="space-y-6">
    <!-- Counter Quick Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Scheduled Pickups</span>
            <div class="text-2xl font-bold text-indigo-600 mt-1">{{ $todayPickups->count() }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Pending customer check-out</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Today's Expected Returns</span>
            <div class="text-2xl font-bold text-purple-600 mt-1">{{ $todayReturns->count() }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Due for return inspection</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Unallocated Reservations</span>
            <div class="text-2xl font-bold {{ $pendingAllocations->count() > 0 ? 'text-amber-600' : 'text-slate-800' }} mt-1">{{ $pendingAllocations->count() }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Needs vehicle assignment</p>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Overdue Returns</span>
            <div class="text-2xl font-bold {{ $overdueReturns->count() > 0 ? 'text-rose-600' : 'text-emerald-600' }} mt-1">{{ $overdueReturns->count() }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Exceeded scheduled window</p>
        </div>
    </div>

    <!-- Active Operational Queues -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Ready for Handover / Pickups -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-emerald-500 font-bold">📋</span>
                    <h3 class="text-sm font-bold text-slate-900">Today's Checkouts (Handovers)</h3>
                </div>
                <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">{{ $todayPickups->count() }} Ready</span>
            </div>

            <div class="space-y-3 text-xs">
                @forelse ($todayPickups as $b)
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900">{{ $b->customer->name }} ({{ $b->customer->phone }})</div>
                            <div class="text-[11px] text-slate-500 mt-0.5">Booking #{{ $b->booking_number }} · {{ $b->category->name }}</div>
                            <div class="font-mono text-[11px] font-semibold text-indigo-600 mt-0.5">
                                Assigned: {{ $b->assignedVehicle->registration_number ?? 'Vehicle Not Allocated' }}
                            </div>
                        </div>
                        <div>
                            @if ($b->assigned_vehicle_id)
                                <a href="{{ route('operations.handover', $b->id) }}" class="px-3.5 py-1.5 rounded-lg bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 shadow-xs inline-flex items-center">
                                    Handover →
                                </a>
                            @else
                                <a href="{{ route('bookings.show', $b->id) }}" class="px-3 py-1.5 rounded-lg bg-amber-500 text-white font-bold text-xs hover:bg-amber-600">
                                    Allocate Car
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        No checkouts scheduled for today.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Returns Due Today -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="text-purple-500 font-bold">🔄</span>
                    <h3 class="text-sm font-bold text-slate-900">Expected Returns Due Today</h3>
                </div>
                <span class="text-xs font-semibold text-purple-700 bg-purple-50 px-2.5 py-0.5 rounded-full">{{ $todayReturns->count() }} Due</span>
            </div>

            <div class="space-y-3 text-xs">
                @forelse ($todayReturns as $b)
                    <div class="p-3.5 rounded-xl border border-purple-100 bg-purple-50/30 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900">{{ $b->customer->name }} ({{ $b->customer->phone }})</div>
                            <div class="font-mono text-xs font-bold text-indigo-700">{{ $b->assignedVehicle->registration_number ?? 'Vehicle' }}</div>
                            <div class="text-[11px] text-slate-500">Scheduled: {{ $b->return_datetime->format('H:i A') }}</div>
                        </div>
                        <div>
                            <a href="{{ route('operations.return', $b->id) }}" class="px-3.5 py-1.5 rounded-lg bg-purple-600 text-white font-bold text-xs hover:bg-purple-700 shadow-xs inline-flex items-center">
                                Inspect Return →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        No active bookings due for return today.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

