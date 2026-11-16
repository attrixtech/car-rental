@extends('layouts.app')

@section('title', 'Register Chauffeur')
@section('page-title', 'Register New Chauffeur / Driver')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 mb-6">
            Chauffeur Profile & Professional Licensing
        </h3>

        <form action="{{ route('drivers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Suresh Shinde" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Mobile Phone *</label>
                    <input type="text" name="phone" required placeholder="+91 97690 XXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Assigned Branch Hub *</label>
                    <select name="branch_id" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Commercial License Number *</label>
                    <input type="text" name="license_number" required placeholder="MH02 201500XXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">License Category *</label>
                    <select name="license_type" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="commercial" selected>Commercial Heavy / Passenger</option>
                        <option value="transport">Transport Badge</option>
                        <option value="lmv">LMV Light Motor Vehicle</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">License Expiry Date *</label>
                    <input type="date" name="license_expiry_date" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Day Bata Rate (₹) *</label>
                    <input type="number" name="daily_bata_rate" value="350" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-bold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Night Halt Bata Rate (₹) *</label>
                    <input type="number" name="night_bata_rate" value="350" required class="w-full px-3 py-2 rounded-xl border border-slate-300 font-bold focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('drivers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 shadow-md shadow-indigo-600/20">
                    Register Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

