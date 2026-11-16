@extends('layouts.app')

@section('title', 'General Ledger & Chart of Accounts')
@section('page-title', 'Double-Entry General Ledger & Accounts')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Chart of Accounts Card -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Standard Chart of Accounts</h3>
            <div class="space-y-2 text-xs">
                @foreach ($accounts as $acc)
                    <div class="flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 transition">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded text-[11px]">{{ $acc->account_code }}</span>
                            <span class="font-semibold text-slate-800">{{ $acc->account_name }}</span>
                        </div>
                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600">{{ $acc->account_type }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Journal Entries -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Recent Balanced Journal Postings</h3>
            <div class="space-y-3 text-xs">
                @forelse ($journalEntries as $entry)
                    <div class="p-3 rounded-xl border border-slate-200 bg-slate-50/50 space-y-2">
                        <div class="flex items-center justify-between font-bold text-slate-800">
                            <span>{{ $entry->entry_number }}</span>
                            <span class="text-slate-400 font-normal">{{ $entry->entry_date->format('d M Y') }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500">{{ $entry->narration }}</p>
                        <div class="space-y-1 pt-1 border-t border-slate-200">
                            @foreach ($entry->lines as $line)
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-slate-700">{{ $line->account->account_name }}</span>
                                    <span class="font-mono">
                                        {{ $line->debit > 0 ? 'Dr ₹' . number_format($line->debit, 2) : 'Cr ₹' . number_format($line->credit, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400">
                        No manual journal vouchers posted. Operating entries post automatically on invoice payments and settlements.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

