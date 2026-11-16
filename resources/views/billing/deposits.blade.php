@extends('layouts.app')

@section('title', 'Escrow Security Deposits')
@section('page-title', 'Customer Security Deposits (Escrow Liabilities)')

@section('content')
<div class="space-y-6">
    <!-- Notice Banner -->
    <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 text-amber-900 text-xs flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-3">
            <span class="text-xl">🛡️</span>
            <div>
                <span class="font-bold">Accounting Rule 12 Compliance:</span>
                <span>Security deposits are held in a segregated escrow liability account (2001) and never recognized as revenue until damage or deficit forfeiture occurs.</span>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3.5">Deposit #</th>
                        <th class="px-6 py-3.5">Booking & Vehicle</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Held Amount</th>
                        <th class="px-6 py-3.5">Deductions</th>
                        <th class="px-6 py-3.5">Refunded</th>
                        <th class="px-6 py-3.5">Escrow Status</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($deposits as $d)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                {{ $d->deposit_number }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('bookings.show', $d->booking->id) }}" class="font-semibold text-slate-800 hover:text-indigo-600">
                                    {{ $d->booking->booking_number }}
                                </a>
                                <div class="text-[11px] font-mono text-slate-400">{{ $d->booking->assignedVehicle->registration_number ?? 'Vehicle unallocated' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $d->customer->name }}</div>
                                <div class="text-[11px] text-slate-400">{{ $d->customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                ₹{{ number_format($d->deposit_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold {{ $d->deductions_amount > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                ₹{{ number_format($d->deductions_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 font-mono font-semibold {{ $d->refunded_amount > 0 ? 'text-emerald-600' : 'text-slate-400' }}">
                                ₹{{ number_format($d->refunded_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'held' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'partially_deducted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'refunded' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'forfeited' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses[$d->status] ?? 'bg-slate-50 text-slate-600' }}">
                                    {{ $d->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($d->status === 'held' || $d->status === 'partially_deducted')
                                    <button onclick="openRefundModal('{{ $d->id }}', '{{ $d->deposit_number }}', '{{ $d->deposit_amount - $d->deductions_amount }}')" class="px-3 py-1 rounded-lg bg-emerald-600 text-white font-semibold text-xs hover:bg-emerald-700">
                                        Issue Refund
                                    </button>
                                @else
                                    <span class="text-slate-400 text-xs">Settled {{ $d->refunded_at?->format('d M') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                No security deposits recorded yet. Deposits are initialized automatically upon self-drive bookings.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Issue Security Deposit Refund</h3>
        <p class="text-xs text-slate-500" id="refundDescription"></p>

        <form id="refundForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Bank / UPI Refund Reference *</label>
                <input type="text" name="refund_reference" required placeholder="e.g. UPI/REF/9821034" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Audit Notes</label>
                <textarea name="notes" rows="2" placeholder="Returned after full vehicle inspection..." class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('refundModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">Process Refund</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRefundModal(depositId, depositNumber, refundAmount) {
    document.getElementById('refundDescription').innerText = `Issuing escrow refund of ₹${Number(refundAmount).toLocaleString('en-IN')} for deposit ${depositNumber}.`;
    document.getElementById('refundForm').action = `/deposits/${depositId}/refund`;
    document.getElementById('refundModal').classList.remove('hidden');
}
</script>
@endpush
@endsection

