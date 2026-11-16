@extends('layouts.app')

@section('title', 'Bookings & Reservations')
@section('page-title', 'Bookings & Reservations')

@section('content')
<div class="space-y-6">
    <!-- Header with Filters & Actions -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center space-x-1 overflow-x-auto text-xs font-semibold">
            <a href="{{ route('bookings.index') }}" class="px-3.5 py-2 rounded-xl transition {{ !$status ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                All Bookings
            </a>
            <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'confirmed' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Confirmed
            </a>
            <a href="{{ route('bookings.index', ['status' => 'allocated']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'allocated' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Ready for Handover
            </a>
            <a href="{{ route('bookings.index', ['status' => 'active']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'active' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Active On-Trip
            </a>
            <a href="{{ route('bookings.index', ['status' => 'settled']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'settled' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Settled / Returned
            </a>
        </div>

        <!-- Search & New Button -->
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <form action="{{ route('bookings.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search booking # or customer..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs w-64 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
            <a href="{{ route('bookings.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-xs flex items-center">
                + Create Booking
            </a>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Booking #</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Rental Mode</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Assigned Vehicle</th>
                        <th class="px-6 py-3.5">Trip Schedule</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Estimated Total</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-bold text-indigo-600">
                                <a href="{{ route('bookings.show', $b->id) }}">{{ $b->booking_number }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-800">{{ $b->customer->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $b->customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $b->isSelfDrive() ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                                    {{ $b->rentalType->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700 font-medium">
                                {{ $b->category->name }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($b->assignedVehicle)
                                    <div class="font-mono font-bold text-slate-900">{{ $b->assignedVehicle->registration_number }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $b->assignedVehicle->model->brand->name }} {{ $b->assignedVehicle->model->name }}</div>
                                @else
                                    <span class="text-rose-600 font-semibold text-[11px]">Vehicle Not Assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                <div>{{ $b->pickup_datetime->format('d M Y H:i') }}</div>
                                <div class="text-[11px] text-slate-400">Return: {{ $b->return_datetime->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
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
                            <td class="px-6 py-4 font-bold text-slate-900">
                                ₹{{ number_format($b->total_estimated_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                @if ($b->booking_status === 'allocated' || ($b->booking_status === 'confirmed' && $b->assigned_vehicle_id))
                                    <a href="{{ route('operations.handover', $b->id) }}" class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                        Handover →
                                    </a>
                                @elseif ($b->booking_status === 'active')
                                    <a href="{{ route('operations.return', $b->id) }}" class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-purple-600 text-white hover:bg-purple-700 transition">
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
                            <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                No reservations match criteria. <a href="{{ route('bookings.create') }}" class="text-indigo-600 font-semibold underline">Create new booking</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bookings->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

