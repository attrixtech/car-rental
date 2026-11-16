@extends('layouts.app')

@section('title', 'Register Customer')
@section('page-title', 'Register New Customer')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 mb-6">
            Customer Profile & Identity Details
        </h3>

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Customer Full Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Rahul Verma" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Mobile Phone Number *</label>
                    <input type="text" name="phone" required placeholder="+91 98200 XXXXX" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="customer@example.com" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Customer Type *</label>
                    <select name="customer_type" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                        <option value="individual" selected>Individual Traveler</option>
                        <option value="corporate">Corporate Client Booker</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Driving License Number *</label>
                    <input type="text" name="driving_license_number" placeholder="e.g. MH02 20180024911" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">License Expiry Date</label>
                    <input type="date" name="driving_license_expiry" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Aadhaar / ID Proof Number</label>
                    <input type="text" name="id_proof_number" placeholder="XXXX-XXXX-XXXX" class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">City *</label>
                    <input type="text" name="city" value="Mumbai" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">State *</label>
                    <input type="text" name="state" value="Maharashtra" required class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Postal Code</label>
                    <input type="text" name="postal_code" placeholder="400001" class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-600 focus:outline-none">
                </div>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                <label class="flex items-center cursor-pointer text-xs">
                    <input type="checkbox" name="is_dl_verified" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500 mr-3">
                    <div>
                        <div class="font-bold text-slate-800">Driving License Physical Document Inspected & Verified</div>
                        <div class="text-[11px] text-slate-400">Officer confirms physical license has been sighted and checked against state transport database</div>
                    </div>
                </label>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('customers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 shadow-md shadow-indigo-600/20">
                    Save Customer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

