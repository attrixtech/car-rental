@extends('layouts.app')

@section('title', 'Customer ' . $customer->name)
@section('page-title', 'Customer CRM — ' . $customer->name)

@section('content')
<div class="space-y-6">
    <!-- Top Customer Summary -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 font-bold text-xl flex items-center justify-center border border-indigo-100">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">{{ $customer->name }}</h3>
                <p class="text-xs text-slate-500">{{ $customer->customer_code }} · {{ $customer->phone }} · {{ $customer->city }}, {{ $customer->state }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    @if ($customer->is_dl_verified)
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ DL Verified: {{ $customer->driving_license_number }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                            ⚠ DL Pending Verification
                        </span>
                        <form action="{{ route('customers.verify-dl', $customer->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-indigo-600 text-white text-[10px] font-bold">Verify DL Now</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('bookings.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 shadow-xs">
                + Create Reservation for Customer
            </a>
        </div>
    </div>

    <!-- Booking History -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-slate-900">Rental Trip History</h4>
            <span class="text-xs text-slate-400">{{ $customer->bookings->count() }} bookings</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Booking #</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Vehicle</th>
                        <th class="px-6 py-3">Schedule</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customer->bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-3.5 font-bold text-indigo-600">
                                <a href="{{ route('bookings.show', $b->id) }}">{{ $b->booking_number }}</a>
                            </td>
                            <td class="px-6 py-3.5">{{ $b->category->name }}</td>
                            <td class="px-6 py-3.5 font-mono">{{ $b->assignedVehicle->registration_number ?? 'Not assigned' }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $b->pickup_datetime->format('d M Y') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border bg-slate-50 text-slate-700">
                                    {{ $b->booking_status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('bookings.show', $b->id) }}" class="text-indigo-600 font-semibold hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">No rental history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

