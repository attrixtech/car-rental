@extends('layouts.app')

@section('title', 'Fleet Schedule & Allocation Calendar')
@section('page-title', 'Fleet Schedule & Allocation Calendar')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Current Fleet Allocation Timeline</h3>
                <p class="text-xs text-slate-500">Real-time status overview preventing double booking overlaps</p>
            </div>
            <div class="flex items-center space-x-2 text-xs">
                <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                    ● Available (Ready)
                </span>
                <span class="inline-flex items-center px-2 py-1 rounded bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200">
                    ● Active Trip
                </span>
                <span class="inline-flex items-center px-2 py-1 rounded bg-amber-50 text-amber-700 font-semibold border border-amber-200">
                    ● Maintenance
                </span>
            </div>
        </div>

        <div class="space-y-3">
            @foreach ($vehicles as $v)
                <div class="p-4 rounded-xl border border-slate-200/80 hover:border-indigo-300 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="w-72 flex-shrink-0">
                        <div class="font-mono font-bold text-slate-900 text-sm">
                            <a href="{{ route('fleet.show', $v->id) }}" class="text-indigo-600 hover:underline">{{ $v->registration_number }}</a>
                        </div>
                        <div class="text-xs text-slate-600">{{ $v->model->brand->name }} {{ $v->model->name }} ({{ $v->category->name }})</div>
                        <div class="text-[11px] text-slate-400">Odo: {{ number_format($v->current_odometer) }} km · Hub: {{ $v->branch->name }}</div>
                    </div>

                    <div class="flex-1">
                        @if ($v->status === 'rented')
                            @php $activeBooking = $v->bookings->first(); @endphp
                            <div class="p-2.5 rounded-lg bg-indigo-50 border border-indigo-100 text-xs text-indigo-900 flex items-center justify-between">
                                <div>
                                    <span class="font-bold">Active On-Trip:</span>
                                    <span>Booking {{ $activeBooking?->booking_number ?? 'In Progress' }} ({{ $activeBooking?->customer?->name }})</span>
                                </div>
                                <span class="text-[11px] font-mono font-semibold">Expected Return: {{ $activeBooking?->return_datetime?->format('d M H:i') }}</span>
                            </div>
                        @elseif ($v->status === 'available')
                            <div class="p-2.5 rounded-lg bg-emerald-50 border border-emerald-100 text-xs text-emerald-800 flex items-center justify-between">
                                <div>
                                    <span class="font-bold">Ready for Dispatch:</span>
                                    <span>Clean, inspected, fuel {{ $v->current_fuel_level }}/8</span>
                                </div>
                                <a href="{{ route('bookings.create') }}" class="font-bold underline text-emerald-900">Book Vehicle →</a>
                            </div>
                        @elseif ($v->status === 'maintenance')
                            <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-100 text-xs text-amber-800">
                                <span class="font-bold">Under Scheduled Service:</span>
                                <span>Vehicle locked out from reservation calendar</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

