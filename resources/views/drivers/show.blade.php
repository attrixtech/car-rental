@extends('layouts.app')

@section('title', 'Driver ' . $driver->name)
@section('page-title', 'Chauffeur Profile — ' . $driver->name)

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 font-bold text-xl flex items-center justify-center border border-amber-100">
                🚖
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">{{ $driver->name }}</h3>
                <p class="text-xs text-slate-500">{{ $driver->driver_code }} · Phone: {{ $driver->phone }} · Hub: {{ $driver->branch->name }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        License: {{ $driver->license_number }}
                    </span>
                    <span class="text-xs font-bold text-amber-500">★ {{ $driver->rating }} Rating ({{ $driver->total_trips }} trips)</span>
                </div>
            </div>
        </div>

        <div>
            <button onclick="document.getElementById('attendanceModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 shadow-xs">
                + Mark Daily Attendance
            </button>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div id="attendanceModal" class="hidden fixed inset-0 bg-slate-900/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-slate-100 space-y-4">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2">Record Chauffeur Attendance</h3>
            <form action="{{ route('drivers.attendance', $driver->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Date</label>
                    <input type="date" name="attendance_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                        <option value="present">Present (On-Duty)</option>
                        <option value="on_trip">Dispatched On-Trip</option>
                        <option value="leave">Leave / Weekly Off</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="document.getElementById('attendanceModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700">Save Attendance</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Assigned Trips -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h4 class="text-sm font-bold text-slate-900">Assigned Chauffeur Trips</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3">Booking #</th>
                        <th class="px-6 py-3">Customer</th>
                        <th class="px-6 py-3">Schedule</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($driver->bookings as $b)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-3.5 font-bold text-indigo-600">
                                <a href="{{ route('bookings.show', $b->id) }}">{{ $b->booking_number }}</a>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">{{ $b->customer->name }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $b->pickup_datetime->format('d M Y H:i') }}</td>
                            <td class="px-6 py-3.5 font-bold uppercase text-[10px]">{{ $b->booking_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400">No trips assigned yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

