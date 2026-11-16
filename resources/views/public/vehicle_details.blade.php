<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }} | DriveSphere Rentals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full flex flex-col text-slate-800">
    <!-- Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="{{ route('public.catalog') }}" class="flex items-center space-x-2 text-indigo-600 font-bold text-sm">
                ← Back to Fleet Catalog
            </a>
            <div class="font-extrabold text-slate-900 tracking-tight">DRIVESPHERE</div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-6 py-10 flex-1 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Car Details -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl p-8 border border-slate-200/80 shadow-xs space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            {{ $vehicle->category->name }}
                        </span>
                        <h2 class="text-3xl font-extrabold text-slate-900 mt-3">{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }}</h2>
                        <p class="text-sm text-slate-500 font-mono">{{ $vehicle->registration_number }} · {{ $vehicle->color }} · {{ $vehicle->manufacture_year }}</p>
                    </div>

                    <!-- Specs -->
                    <div class="grid grid-cols-3 gap-3 text-center text-xs">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400">Transmission</span>
                            <div class="font-bold text-slate-800 text-sm mt-0.5">{{ ucfirst($vehicle->transmission) }}</div>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400">Fuel Engine</span>
                            <div class="font-bold text-slate-800 text-sm mt-0.5">{{ ucfirst($vehicle->fuel_type) }}</div>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <span class="text-slate-400">Seating</span>
                            <div class="font-bold text-slate-800 text-sm mt-0.5">{{ $vehicle->seating_capacity }} Passengers</div>
                        </div>
                    </div>

                    <!-- Terms & Policies -->
                    <div class="space-y-3 text-xs text-slate-600 border-t border-slate-100 pt-6">
                        <h4 class="font-bold uppercase tracking-wider text-slate-900">Rental Policies & Inclusions</h4>
                        <ul class="space-y-2">
                            <li class="flex items-center">✓ {{ $vehicle->category->free_km_per_day }} km complimentary distance included every 24 hours.</li>
                            <li class="flex items-center">✓ Excess kilometer rate: ₹{{ number_format($vehicle->category->excess_km_rate, 2) }}/km after allowance.</li>
                            <li class="flex items-center">✓ Refundable Escrow Security Deposit: ₹{{ number_format($vehicle->category->security_deposit, 0) }} required at dispatch.</li>
                            <li class="flex items-center">✓ Mandatory Driving License (original physical card) and government Aadhaar verification at check-in.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Col: Booking Box -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-md sticky top-24 space-y-4">
                    <div class="border-b border-slate-100 pb-4">
                        <span class="text-slate-400 text-xs font-semibold">Standard Tariff</span>
                        <div class="flex items-baseline space-x-1 mt-1">
                            <span class="text-3xl font-extrabold text-indigo-700">₹{{ number_format($vehicle->daily_rental_price, 0) }}</span>
                            <span class="text-xs text-slate-500">/ 24h Day</span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <p class="text-slate-600 leading-relaxed">
                            Ready to reserve this car? Use our unified operator dispatch desk or sign in to complete booking with online payment.
                        </p>
                        <a href="{{ route('login') }}" class="block w-full py-3 rounded-xl bg-indigo-600 text-white font-bold text-xs uppercase tracking-wider text-center hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20">
                            Sign In to Reserve Vehicle
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

