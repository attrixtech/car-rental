@extends('layouts.app')

@section('title', 'GST Tax Invoices')
@section('page-title', 'GST Tax Invoices & Billing')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-1 overflow-x-auto text-xs font-semibold">
            <a href="{{ route('billing.invoices') }}" class="px-3.5 py-2 rounded-xl transition {{ !$status ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                All Invoices
            </a>
            <a href="{{ route('billing.invoices', ['status' => 'paid']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'paid' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Paid
            </a>
            <a href="{{ route('billing.invoices', ['status' => 'partially_paid']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'partially_paid' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Partially Paid
            </a>
            <a href="{{ route('billing.invoices', ['status' => 'unpaid']) }}" class="px-3.5 py-2 rounded-xl transition {{ $status === 'unpaid' ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                Unpaid / Due
            </a>
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <form action="{{ route('billing.invoices') }}" method="GET" class="relative">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search invoice # or customer..." class="pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs w-60 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Invoice #</th>
                        <th class="px-6 py-3.5">Date</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Taxable Base</th>
                        <th class="px-6 py-3.5">GST (Tax)</th>
                        <th class="px-6 py-3.5">Invoice Total</th>
                        <th class="px-6 py-3.5">Paid</th>
                        <th class="px-6 py-3.5">Balance Due</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($invoices as $inv)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                <a href="{{ route('billing.invoice.show', $inv->id) }}">{{ $inv->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $inv->invoice_date->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $inv->customer->name }}</div>
                                <div class="text-[11px] text-slate-400">Booking #{{ $inv->booking->booking_number }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono">₹{{ number_format($inv->taxable_amount, 2) }}</td>
                            <td class="px-6 py-4 font-mono text-slate-600">₹{{ number_format($inv->cgst_amount + $inv->sgst_amount + $inv->igst_amount, 2) }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</td>
                            <td class="px-6 py-4 font-mono font-semibold text-emerald-700">₹{{ number_format($inv->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 font-mono font-bold {{ $inv->balance_due > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                ₹{{ number_format($inv->balance_due, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'partially_paid' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'unpaid' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses[$inv->payment_status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $inv->payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('operations.invoice.pdf', $inv->id) }}" target="_blank" class="text-xs font-semibold text-slate-600 hover:text-indigo-600">PDF</a>
                                <a href="{{ route('billing.invoice.show', $inv->id) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">View →</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-slate-400">
                                No invoices generated yet. Invoices are generated upon rental return & settlement.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

