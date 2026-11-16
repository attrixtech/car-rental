@extends('layouts.app')

@section('title', 'Customer Self-Service Portal')
@section('page-title', 'My Customer Portal & Rental Bookings')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Customer Welcome Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 font-extrabold text-2xl flex items-center justify-center border border-indigo-100">
                👤
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">Welcome, {{ $customer->name }}!</h3>
                <p class="text-xs text-slate-500">{{ $customer->customer_code }} · Phone: {{ $customer->phone }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    @if ($customer->is_dl_verified)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ✓ Driving License Verified: {{ $customer->driving_license_number }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                            ⚠ Driving License Pending Counter Verification
                        </span>
                    @endif
                    <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                        ⭐ {{ $customer->loyalty_points }} Loyalty Points
                    </span>
                </div>
            </div>
        </div>

        <div>
            <a href="{{ route('public.catalog') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-xs flex items-center">
                + Browse & Book a Car →
            </a>
        </div>
    </div>

    <!-- Active Ongoing Rental (if any) -->
    @if ($activeBooking)
        <div class="bg-white rounded-2xl p-6 border-2 border-indigo-600 shadow-md space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-ping"></span>
                    <h3 class="text-base font-extrabold text-slate-900">ACTIVE ON-RENT VEHICLE</h3>
                </div>
                <span class="font-mono font-bold text-sm text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg">
                    Booking #{{ $activeBooking->booking_number }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Assigned Vehicle</span>
                    <div class="font-mono text-base font-extrabold text-slate-900 mt-1">
                        {{ $activeBooking->assignedVehicle->registration_number ?? 'Allocated Car' }}
                    </div>
                    <div class="text-slate-700 font-semibold mt-0.5">
                        {{ $activeBooking->assignedVehicle->model->brand->name ?? '' }} {{ $activeBooking->assignedVehicle->model->name ?? '' }}
                    </div>
                    <div class="text-slate-400 text-[11px] mt-1">{{ ucfirst($activeBooking->assignedVehicle->fuel_type ?? 'petrol') }} · {{ ucfirst($activeBooking->assignedVehicle->transmission ?? 'manual') }}</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Scheduled Return</span>
                    <div class="font-bold text-slate-900 text-sm mt-1">
                        {{ $activeBooking->return_datetime->format('d M Y') }}
                    </div>
                    <div class="text-slate-600 font-medium text-[11px] mt-0.5">
                        Time: {{ $activeBooking->return_datetime->format('H:i A') }}
                    </div>
                    <div class="text-slate-400 text-[11px] mt-1">Drop Hub: {{ $activeBooking->returnLocation->name }}</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-[10px] uppercase font-bold text-slate-400">Escrow Security Deposit</span>
                    <div class="font-mono text-base font-bold text-slate-900 mt-1">
                        ₹{{ number_format($activeBooking->security_deposit_amount, 2) }}
                    </div>
                    <div class="text-emerald-700 font-semibold text-[11px] mt-0.5">Status: HELD IN ESCROW</div>
                    <div class="text-slate-400 text-[10px] mt-1">Refunded immediately upon return inspection.</div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Need immediate roadside assistance? Contact 24/7 Helpline: +91 22 6789 4400</span>
                <a href="{{ route('operations.agreement.pdf', $activeBooking->id) }}" target="_blank" class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 font-bold hover:bg-indigo-100 transition inline-flex items-center">
                    Download Signed Agreement (PDF)
                </a>
            </div>
        </div>
    @endif

    <!-- Upcoming Bookings & Invoices -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Reservations -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Upcoming Reservations</h4>
            <div class="space-y-3 text-xs">
                @forelse ($upcomingBookings as $b)
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                        <div>
                            <div class="font-bold text-indigo-600">Booking #{{ $b->booking_number }}</div>
                            <div class="text-slate-800 font-semibold mt-0.5">{{ $b->category->name }} ({{ $b->pickup_datetime->format('d M Y H:i') }})</div>
                            <div class="text-slate-400 text-[11px]">Pickup Hub: {{ $b->pickupLocation->name }}</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $b->booking_status }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        No upcoming bookings. <a href="{{ route('public.catalog') }}" class="text-indigo-600 font-bold underline">Explore fleet to book</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Invoices & Receipts -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">My Invoices & Tax Receipts</h4>
            <div class="space-y-2 text-xs">
                @forelse ($invoices as $inv)
                    <div class="p-3 rounded-xl border border-slate-100 hover:bg-slate-50 flex items-center justify-between">
                        <div>
                            <div class="font-mono font-bold text-slate-800">{{ $inv->invoice_number }}</div>
                            <div class="text-[11px] text-slate-400">Date: {{ $inv->invoice_date->format('d M Y') }}</div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="font-mono font-bold text-slate-900">₹{{ number_format($inv->total_amount, 2) }}</span>
                            <a href="{{ route('operations.invoice.pdf', $inv->id) }}" target="_blank" class="text-indigo-600 font-bold hover:underline">PDF</a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        No invoices generated yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

