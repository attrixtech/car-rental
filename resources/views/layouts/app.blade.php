<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | WebotApp DriveSphere ERP</title>
    <!-- Tailwind CSS & Alpine & Chart.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-800">
    <div class="min-h-full flex">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 z-20 shadow-xl">
            <!-- Brand / Logo -->
            <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-extrabold text-base shadow-md">
                        WA
                    </div>
                    <div>
                        <h1 class="text-white font-extrabold text-sm tracking-wide">WebotApp ERP</h1>
                        <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-wider">DriveSphere Fleet</p>
                    </div>
                </div>
            </div>

            <!-- Role Badge & Hub Status -->
            <div class="px-5 py-3 bg-slate-900/80 border-b border-slate-800 text-xs">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="uppercase tracking-wider font-semibold text-[10px] text-slate-400">Signed In Role</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-950 text-indigo-300 border border-indigo-800/50">
                        {{ auth()->user()->role ?? 'staff' }}
                    </span>
                </div>
                <div class="text-slate-200 font-medium mt-1 truncate">{{ auth()->user()->name ?? 'User' }}</div>
                <div class="text-[11px] text-slate-400">Mumbai CSMIA Hub · BOM-T2</div>
            </div>

            <!-- Role-Tailored Navigation Links -->
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <!-- Dashboard (Role specific view rendered automatically) -->
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    @if (auth()->user()->isDriver())
                        My Duty Portal
                    @elseif (auth()->user()->isCustomer())
                        My Customer Portal
                    @else
                        Dashboard
                    @endif
                </a>

                @if (auth()->user()->isDriver())
                    <!-- DRIVER ONLY NAVIGATION -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">My Driving Operations</div>

                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800 text-slate-300">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Assigned Duty Slips
                    </a>

                    <a href="{{ route('public.catalog') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800 text-slate-300">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Browse Public Fleet
                    </a>

                @elseif (auth()->user()->isCustomer())
                    <!-- CUSTOMER ONLY NAVIGATION -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">My Rentals & Documents</div>

                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800 text-slate-300">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        My Bookings
                    </a>

                    <a href="{{ route('public.catalog') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800 text-slate-300">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Book a New Vehicle
                    </a>

                    <a href="{{ route('billing.invoices') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('billing.invoices*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        My Tax Invoices
                    </a>

                @elseif (auth()->user()->isFleetManager())
                    <!-- FLEET MANAGER NAVIGATION -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Fleet Operations</div>

                    <a href="{{ route('fleet.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('fleet.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        Fleet Registry
                    </a>

                    <a href="{{ route('fleet.calendar') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('fleet.calendar') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Fleet Schedule
                    </a>

                    <a href="{{ route('reports.utilization') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.utilization') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        Fleet Utilization
                    </a>

                    <a href="{{ route('reports.profitability') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.profitability') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Vehicle Costing & ROI
                    </a>

                @elseif (auth()->user()->isCounterStaff())
                    <!-- COUNTER / DISPATCH STAFF NAVIGATION -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Desk Operations</div>

                    <a href="{{ route('bookings.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('bookings.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Bookings & Checkouts
                    </a>

                    <a href="{{ route('bookings.create') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800 text-slate-300">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New Reservation
                    </a>

                    <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Customer CRM & DL
                    </a>

                    <a href="{{ route('fleet.calendar') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('fleet.calendar') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Fleet Schedule
                    </a>

                @else
                    <!-- SUPER ADMIN & COMPANY ADMIN FULL NAVIGATION -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Reservations & Operations</div>
                    
                    <a href="{{ route('bookings.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('bookings.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        All Bookings
                    </a>

                    <a href="{{ route('bookings.create') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition hover:bg-slate-800 text-slate-300">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New Reservation
                    </a>

                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Fleet & Drivers</div>

                    <a href="{{ route('fleet.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('fleet.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        Fleet Registry
                    </a>

                    <a href="{{ route('fleet.calendar') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('fleet.calendar') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Fleet Schedule
                    </a>

                    <a href="{{ route('drivers.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('drivers.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Chauffeur Roster
                    </a>

                    <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Customer CRM & DL
                    </a>

                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Finance & Billing</div>

                    <a href="{{ route('billing.invoices') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('billing.invoices*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        GST Tax Invoices
                    </a>

                    <a href="{{ route('billing.deposits') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('billing.deposits*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Escrow Deposits
                    </a>

                    <a href="{{ route('billing.ledger') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('billing.ledger*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        General Ledger
                    </a>

                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Analytics & BI</div>

                    <a href="{{ route('reports.profitability') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.profitability') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Vehicle Costing & ROI
                    </a>

                    <a href="{{ route('reports.utilization') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reports.utilization') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                        <svg class="w-5 h-5 mr-3 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        Fleet Utilization
                    </a>
                @endif

                <div class="pt-4 pb-2 px-3">
                    <a href="{{ route('public.catalog') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-xs font-semibold bg-slate-800 text-indigo-300 hover:bg-slate-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Public Catalog View
                    </a>
                </div>
            </nav>

            <!-- User Footer -->
            <div class="p-4 bg-slate-950 border-t border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-indigo-500/20 text-indigo-400 font-semibold flex items-center justify-center text-xs border border-indigo-500/30">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <div class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Guest User' }}</div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-wider">{{ auth()->user()->role ?? 'staff' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="text-slate-400 hover:text-rose-400 p-1.5 transition rounded-lg hover:bg-slate-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Top App Bar -->
            <header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-10 flex items-center justify-between px-8 shadow-xs">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-bold text-slate-800">@yield('page-title', 'Car Rental Operations')</h2>
                    @yield('breadcrumbs')
                </div>

                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                        GST Active (18% / 5%)
                    </span>
                    @if (!auth()->user()->isDriver())
                        <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-3.5 py-2 rounded-lg text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            New Booking
                        </a>
                    @endif
                </div>
            </header>

            <!-- Alerts Banner -->
            <div class="px-8 pt-6">
                @if (session('success'))
                    <div class="p-4 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-xs">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between text-sm shadow-xs">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">&times;</button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-4 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
                        <p class="font-semibold mb-1">Please correct the following errors:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <!-- Page Body -->
            <main class="flex-1 px-8 pb-12">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
