<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSphere Mobility | Premium Self-Drive & Chauffeur Fleet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full flex flex-col text-slate-800">
    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-extrabold text-lg shadow-sm">
                    DS
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900 tracking-tight">DRIVESPHERE</h1>
                    <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider">Car Rentals India</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-slate-700 hover:text-indigo-600 transition">
                    Staff & Driver ERP Login
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition shadow-xs">
                    Sign In →
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-white py-14 px-6 text-center">
        <div class="max-w-3xl mx-auto space-y-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                Self-Drive & Chauffeur Services · Verified Sanitized Fleet
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight">
                Premium Automobile Rentals in Mumbai, Pune & Delhi
            </h2>
            <p class="text-slate-300 text-sm max-w-xl mx-auto">
                Clean verified cars, zero hidden fees, comprehensive insurance coverage, and 24/7 roadside assistance.
            </p>
        </div>
    </section>

    <!-- Filter Bar & Fleet Catalog -->
    <main class="max-w-7xl mx-auto px-6 py-10 flex-1 space-y-8">
        <!-- Filter Tabs -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-2 overflow-x-auto text-xs font-semibold">
                <a href="{{ route('public.catalog') }}" class="px-3.5 py-2 rounded-xl transition {{ !$categoryId ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    All Categories
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('public.catalog', ['category_id' => $cat->id]) }}" class="px-3.5 py-2 rounded-xl transition {{ $categoryId == $cat->id ? 'bg-indigo-600 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <div class="text-xs text-slate-500 font-medium">
                Showing <span class="font-bold text-slate-900">{{ $vehicles->count() }}</span> available vehicles
            </div>
        </div>

        <!-- Vehicles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($vehicles as $v)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-lg transition overflow-hidden flex flex-col justify-between">
                    <div class="p-6 space-y-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                                    {{ $v->category->name }}
                                </span>
                                <h3 class="text-lg font-bold text-slate-900 mt-2">{{ $v->model->brand->name }} {{ $v->model->name }}</h3>
                                <p class="text-xs text-slate-500 font-mono">{{ $v->registration_number }} · {{ $v->color }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-extrabold text-indigo-700">₹{{ number_format($v->daily_rental_price, 0) }}</span>
                                <div class="text-[10px] text-slate-400">per 24h day</div>
                            </div>
                        </div>

                        <!-- Specs Badges -->
                        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center text-xs">
                            <div class="p-2 rounded-xl bg-slate-50">
                                <div class="font-bold text-slate-800">{{ $v->seating_capacity }} Seats</div>
                                <div class="text-[10px] text-slate-400">Capacity</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-50">
                                <div class="font-bold text-slate-800">{{ ucfirst($v->fuel_type) }}</div>
                                <div class="text-[10px] text-slate-400">Fuel</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-50">
                                <div class="font-bold text-slate-800">{{ ucfirst($v->transmission) }}</div>
                                <div class="text-[10px] text-slate-400">Gearbox</div>
                            </div>
                        </div>

                        <!-- Included Features -->
                        <div class="text-xs text-slate-600 space-y-1">
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $v->category->free_km_per_day }} km complimentary per day
                            </div>
                            <div class="flex items-center">
                                <svg class="w-3.5 h-3.5 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Comprehensive Zero-Dep Insurance included
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">Hub: {{ $v->branch->name }}</span>
                        <a href="{{ route('public.vehicle', $v->id) }}" class="px-4 py-2 rounded-xl bg-indigo-600 text-white font-bold text-xs hover:bg-indigo-700 transition shadow-xs">
                            Book This Car →
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-slate-400">
                    No vehicles currently available matching this category filter.
                </div>
            @endforelse
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 px-6 text-xs text-center border-t border-slate-800">
        <p>© 2026 DriveSphere Mobility Solutions Pvt. Ltd. All rights reserved. GST Reg: 27AABCD1234E1Z5.</p>
    </footer>
</body>
</html>

