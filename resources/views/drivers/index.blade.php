@extends('layouts.app')

@section('title', 'Chauffeur & Driver Roster')
@section('page-title', 'Chauffeur & Driver Roster')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <form action="{{ route('drivers.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search driver, phone, license..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs w-64 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>

        <a href="{{ route('drivers.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-xs">
            + Register Chauffeur
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Code</th>
                        <th class="px-6 py-3.5">Chauffeur Name</th>
                        <th class="px-6 py-3.5">Phone</th>
                        <th class="px-6 py-3.5">Commercial License</th>
                        <th class="px-6 py-3.5">Bata Rates (Day / Night)</th>
                        <th class="px-6 py-3.5">Rating & Trips</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($drivers as $d)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                <a href="{{ route('drivers.show', $d->id) }}">{{ $d->driver_code }}</a>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $d->name }}
                                <div class="text-[11px] font-normal text-slate-400">{{ $d->city ?? 'Mumbai Hub' }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $d->phone }}</td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-slate-800">{{ $d->license_number }}</div>
                                <div class="text-[11px] text-slate-400">Badge: {{ $d->badge_number ?? 'Verified' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">₹{{ number_format($d->daily_bata_rate, 0) }}</span>
                                <span class="text-slate-400">/day · ₹{{ number_format($d->night_bata_rate, 0) }} /night</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-amber-500">★ {{ $d->rating }}</div>
                                <div class="text-[11px] text-slate-400">{{ $d->total_trips }} trips</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'available' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'on_trip' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'on_leave' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses[$d->status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $d->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('drivers.show', $d->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    Duty Slip & Attendance →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                No drivers registered. <a href="{{ route('drivers.create') }}" class="text-indigo-600 font-semibold underline">Register driver</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($drivers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $drivers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

