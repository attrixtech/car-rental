@extends('layouts.app')

@section('title', 'Vehicle Profitability & Costing')
@section('page-title', 'Vehicle Unit Economics & Fleet Profitability')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Per-Vehicle P&L Breakdown</h3>
                <p class="text-xs text-slate-500">Trace gross rental revenue against direct fleet maintenance and operational costs</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Plate Number</th>
                        <th class="px-6 py-3.5">Vehicle Model</th>
                        <th class="px-6 py-3.5">Category</th>
                        <th class="px-6 py-3.5">Trips</th>
                        <th class="px-6 py-3.5">Rental Revenue</th>
                        <th class="px-6 py-3.5">Maintenance & Operating</th>
                        <th class="px-6 py-3.5">Net Contribution</th>
                        <th class="px-6 py-3.5 text-right">Profit Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($vehicles as $row)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                <a href="{{ route('fleet.show', $row['vehicle']->id) }}" class="text-indigo-600 hover:underline">
                                    {{ $row['vehicle']->registration_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $row['vehicle']->model->brand->name }} {{ $row['vehicle']->model->name }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $row['vehicle']->category->name }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $row['total_trips'] }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-emerald-700">₹{{ number_format($row['revenue'], 2) }}</td>
                            <td class="px-6 py-4 font-mono text-rose-600">₹{{ number_format($row['total_expenses'], 2) }}</td>
                            <td class="px-6 py-4 font-mono font-bold {{ $row['net_profit'] >= 0 ? 'text-indigo-700' : 'text-rose-700' }}">
                                ₹{{ number_format($row['net_profit'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $row['roi_percentage'] > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $row['roi_percentage'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

