@extends('layouts.app')

@section('title', 'Fleet Utilization Analytics')
@section('page-title', 'Fleet Utilization & Capacity Analytics')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Fleet Segment Utilization</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($categories as $cat)
                @php
                    $utilRate = $cat->total_fleet > 0 ? round(($cat->rented_count / $cat->total_fleet) * 100, 1) : 0;
                @endphp
                <div class="p-5 rounded-2xl border border-slate-200/80 bg-slate-50/50 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-sm text-slate-900">{{ $cat->name }}</h4>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-200">
                            {{ $utilRate }}% Active
                        </span>
                    </div>

                    <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden flex">
                        <div class="bg-indigo-600 h-2.5" style="width: {{ $utilRate }}%"></div>
                        <div class="bg-emerald-500 h-2.5" style="width: {{ $cat->total_fleet > 0 ? ($cat->available_count / $cat->total_fleet) * 100 : 0 }}%"></div>
                        <div class="bg-amber-500 h-2.5" style="width: {{ $cat->total_fleet > 0 ? ($cat->maintenance_count / $cat->total_fleet) * 100 : 0 }}%"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-xs pt-1">
                        <div class="p-1.5 rounded-lg bg-white border border-slate-200">
                            <div class="font-bold text-slate-800">{{ $cat->total_fleet }}</div>
                            <div class="text-[10px] text-slate-400">Total</div>
                        </div>
                        <div class="p-1.5 rounded-lg bg-white border border-slate-200">
                            <div class="font-bold text-emerald-600">{{ $cat->available_count }}</div>
                            <div class="text-[10px] text-slate-400">Available</div>
                        </div>
                        <div class="p-1.5 rounded-lg bg-white border border-slate-200">
                            <div class="font-bold text-indigo-600">{{ $cat->rented_count }}</div>
                            <div class="text-[10px] text-slate-400">On-Rent</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

