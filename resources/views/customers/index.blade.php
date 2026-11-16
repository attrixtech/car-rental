@extends('layouts.app')

@section('title', 'Customer CRM & Verification')
@section('page-title', 'Customer CRM & Identity Verification')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <form action="{{ route('customers.index') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search customer, phone, DL..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs w-64 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>

        <a href="{{ route('customers.create') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition shadow-xs">
            + Register Customer
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Customer Code</th>
                        <th class="px-6 py-3.5">Full Name</th>
                        <th class="px-6 py-3.5">Phone & Email</th>
                        <th class="px-6 py-3.5">City & State</th>
                        <th class="px-6 py-3.5">Driving License Verification</th>
                        <th class="px-6 py-3.5">Total Trips</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $c)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                <a href="{{ route('customers.show', $c->id) }}">{{ $c->customer_code }}</a>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $c->name }}
                                <div class="text-[11px] font-normal text-slate-400">{{ ucfirst($c->customer_type) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $c->phone }}</div>
                                <div class="text-[11px] text-slate-400">{{ $c->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $c->city }}, {{ $c->state }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($c->is_dl_verified)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        ✓ DL Verified: {{ $c->driving_license_number }}
                                    </span>
                                @else
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            Pending DL Check
                                        </span>
                                        <form action="{{ route('customers.verify-dl', $c->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-[11px] font-bold text-indigo-600 hover:underline">Verify</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900">
                                {{ $c->bookings_count }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('customers.show', $c->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                                    CRM Profile →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                No customer records found. <a href="{{ route('customers.create') }}" class="text-indigo-600 font-semibold underline">Register customer</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($customers->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

