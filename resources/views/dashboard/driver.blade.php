@extends('layouts.app')

@section('title', 'Chauffeur Portal Dashboard')
@section('page-title', 'Chauffeur Duty & Trip Portal')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Driver Identity Header -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-600 font-extrabold text-2xl flex items-center justify-center border border-amber-200">
                🚖
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="text-lg font-bold text-slate-900">{{ $driver->name }}</h3>
                    <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $driver->driver_code }}</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Commercial Badge: {{ $driver->badge_number ?? 'BOM/TR/94821' }} · License: {{ $driver->license_number }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="text-xs font-bold text-amber-500">★ {{ $driver->rating }} Rating</span>
                    <span class="text-slate-300">·</span>
                    <span class="text-xs font-semibold text-slate-600">{{ $driver->total_trips }} Completed Trips</span>
                </div>
            </div>
        </div>

        <!-- Attendance & Allowance Summary -->
        <div class="flex items-center space-x-3 text-xs">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-center">
                <span class="text-[10px] uppercase font-bold text-slate-400 block">Today's Duty</span>
                <span class="font-bold text-emerald-700 uppercase">{{ $attendanceToday->status ?? 'Present (On-Duty)' }}</span>
            </div>
            <div class="p-3 rounded-xl bg-indigo-50 border border-indigo-100 text-center">
                <span class="text-[10px] uppercase font-bold text-indigo-400 block">Total Batas Earned</span>
                <span class="font-bold font-mono text-indigo-700 text-sm">₹{{ number_format($totalAllowancesEarned, 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Active Trip Duty Slip (If currently on trip) -->
    @if ($activeTrip)
        <div class="bg-white rounded-2xl p-6 border-2 border-indigo-600 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                    <h3 class="text-base font-extrabold text-slate-900">ACTIVE TRIP IN PROGRESS</h3>
                </div>
                <span class="font-mono font-bold text-sm text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg">
                    Booking #{{ $activeTrip->booking_number }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                <!-- Vehicle -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Assigned Vehicle</span>
                    <div class="font-mono text-base font-extrabold text-slate-900 mt-1">
                        {{ $activeTrip->assignedVehicle->registration_number ?? 'Assigned Car' }}
                    </div>
                    <div class="text-slate-600 mt-0.5">
                        {{ $activeTrip->assignedVehicle->model->brand->name ?? '' }} {{ $activeTrip->assignedVehicle->model->name ?? '' }}
                    </div>
                    <div class="text-slate-400 text-[11px] mt-1">Fuel: {{ $activeTrip->assignedVehicle->current_fuel_level ?? 8 }}/8 Tank</div>
                </div>

                <!-- Customer Details -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Passenger / Booker</span>
                    <div class="text-base font-bold text-slate-900 mt-1">
                        {{ $activeTrip->customer->name }}
                    </div>
                    <div class="text-slate-700 font-semibold mt-0.5">
                        📞 {{ $activeTrip->customer->phone }}
                    </div>
                    <div class="text-slate-400 text-[11px] mt-1">Special: {{ $activeTrip->notes ?? 'Standard VIP Transfer' }}</div>
                </div>

                <!-- Schedule & Route -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Schedule & Hub</span>
                    <div class="font-bold text-slate-800 mt-1">
                        Pickup: {{ $activeTrip->pickupLocation->name }}
                    </div>
                    <div class="text-slate-500 text-[11px] mt-0.5">
                        Start: {{ $activeTrip->pickup_datetime->format('d M H:i A') }}
                    </div>
                    <div class="text-slate-500 text-[11px]">
                        Return: {{ $activeTrip->returnLocation->name }}
                    </div>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-indigo-50 border border-indigo-200/60 flex items-center justify-between text-xs">
                <div>
                    <span class="font-bold text-indigo-950">Chauffeur Instructions:</span>
                    <span class="text-indigo-800">Ensure AC is pre-cooled, bottled water placed, and preserve toll receipts for trip settlement.</span>
                </div>
                <a href="{{ route('operations.return', $activeTrip->id) }}" class="px-4 py-2 rounded-xl bg-purple-600 text-white font-bold hover:bg-purple-700 shadow-xs flex-shrink-0">
                    Complete Trip Return →
                </a>
            </div>
        </div>
    @else
        <div class="p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs text-center space-y-2">
            <span class="text-3xl">☕</span>
            <h4 class="font-bold text-slate-800 text-sm">No Active Trips Right Now</h4>
            <p class="text-xs text-slate-500">You are currently stationed and marked <strong>AVAILABLE</strong> for new dispatch assignments.</p>
        </div>
    @endif

    <!-- Upcoming Assignments & Past Trip Log -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Assignments -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Upcoming Assigned Trips</h4>
            <div class="space-y-3 text-xs">
                @forelse ($upcomingTrips as $trip)
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900">{{ $trip->pickup_datetime->format('d M Y - H:i A') }}</div>
                            <div class="text-slate-600 text-[11px]">{{ $trip->customer->name }} · {{ $trip->category->name }}</div>
                            <div class="text-slate-400 text-[10px]">Pickup: {{ $trip->pickupLocation->name }}</div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $trip->booking_status }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400">
                        No upcoming assignments scheduled.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Earned Bata & Allowances Log -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Earned Driver Batas & Trip Allowances</h4>
            <div class="space-y-2 text-xs">
                @forelse ($allowances as $alw)
                    <div class="p-3 rounded-xl border border-slate-100 hover:bg-slate-50 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-800">{{ ucfirst(str_replace('_', ' ', $alw->allowance_type)) }}</div>
                            <div class="text-[11px] text-slate-400">Duty Date: {{ $alw->duty_date->format('d M Y') }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono font-bold text-emerald-700">+₹{{ number_format($alw->amount, 2) }}</div>
                            <span class="text-[10px] font-semibold text-emerald-600">✓ Settled</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400">
                        No allowance disbursements recorded yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

