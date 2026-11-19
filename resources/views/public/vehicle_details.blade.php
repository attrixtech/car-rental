<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }} | thrumble.car-rental</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 antialiased">
    <!-- Header -->
    <header class="bg-slate-950/90 backdrop-blur-xl border-b border-slate-800 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('public.catalog') }}#fleet" class="flex items-center gap-2 text-indigo-400 hover:text-white font-bold text-xs uppercase tracking-wider transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to Fleet Showcase</span>
            </a>

            <a href="{{ route('public.catalog') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-white font-bold text-sm shadow">
                    <i data-lucide="car" class="w-4 h-4"></i>
                </div>
                <div class="font-display font-bold text-base text-white tracking-tight">thrumble.car-rental</div>
            </a>

            <a href="{{ route('login') }}" class="px-3.5 py-1.5 rounded-xl bg-slate-900 border border-slate-700 text-slate-300 hover:text-white text-xs font-bold transition">
                Sign In
            </a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-10 flex-1 space-y-8">
        <!-- Vehicle Hero Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left 2 Cols: Photography & Specifications -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Vehicle Hero Image Card -->
                <div class="bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden shadow-2xl relative">
                    <div class="h-80 sm:h-96 relative bg-slate-950">
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->model->brand->name }} {{ $vehicle->model->name }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-black/30"></div>

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex items-center gap-2">
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-950/80 backdrop-blur-md text-indigo-400 border border-indigo-500/30">
                                {{ $vehicle->category->name }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-500/90 text-slate-950 flex items-center gap-1 shadow-sm">
                                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                                <span>Verified Sanitized</span>
                            </span>
                        </div>

                        <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-xs">
                            <span class="font-mono bg-slate-950/80 px-2.5 py-1 rounded text-slate-300 border border-slate-700">Reg: {{ $vehicle->registration_number }}</span>
                            <span class="bg-slate-950/80 px-2.5 py-1 rounded text-amber-300 border border-slate-700 font-bold">★ 4.9 Rating (120+ Trips)</span>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="font-display text-3xl sm:text-4xl font-extrabold text-white">
                                    {{ $vehicle->model->brand->name }} {{ $vehicle->model->name }}
                                </h1>
                                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-indigo-400"></i>
                                    <span>Stationed at: <strong class="text-slate-200">{{ $vehicle->branch->name }}</strong> ({{ $vehicle->color }} · {{ $vehicle->manufacture_year }})</span>
                                </p>
                            </div>
                        </div>

                        <!-- Technical Specs Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-slate-800 text-center">
                            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800">
                                <i data-lucide="users" class="w-4 h-4 text-indigo-400 mx-auto mb-1"></i>
                                <span class="text-slate-400 text-[10px] uppercase font-bold block">Capacity</span>
                                <div class="font-bold text-white text-sm mt-0.5">{{ $vehicle->seating_capacity }} Passengers</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800">
                                <i data-lucide="cog" class="w-4 h-4 text-sky-400 mx-auto mb-1"></i>
                                <span class="text-slate-400 text-[10px] uppercase font-bold block">Transmission</span>
                                <div class="font-bold text-white text-sm mt-0.5 capitalize">{{ $vehicle->transmission }}</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800">
                                <i data-lucide="fuel" class="w-4 h-4 text-emerald-400 mx-auto mb-1"></i>
                                <span class="text-slate-400 text-[10px] uppercase font-bold block">Engine Type</span>
                                <div class="font-bold text-white text-sm mt-0.5 capitalize">{{ $vehicle->fuel_type }}</div>
                            </div>
                            <div class="p-3.5 rounded-2xl bg-slate-950/60 border border-slate-800">
                                <i data-lucide="briefcase" class="w-4 h-4 text-amber-400 mx-auto mb-1"></i>
                                <span class="text-slate-400 text-[10px] uppercase font-bold block">Luggage</span>
                                <div class="font-bold text-white text-sm mt-0.5">{{ $vehicle->category->baggage_capacity ?? 3 }} Bags</div>
                            </div>
                        </div>

                        <!-- Features Pill Badges -->
                        @if($vehicle->features && is_array($vehicle->features))
                        <div class="pt-4 border-t border-slate-800 space-y-2">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Included Vehicle Features:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($vehicle->features as $feat)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-950 text-indigo-300 border border-indigo-500/20 text-xs font-medium">
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-indigo-400"></i>
                                        <span>{{ $feat }}</span>
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Policy Inclusions -->
                        <div class="pt-4 border-t border-slate-800 space-y-3 text-xs text-slate-300">
                            <h4 class="font-bold uppercase tracking-wider text-white text-xs">Rental Policies & Inclusions</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                                    <div class="font-bold text-white flex items-center gap-1.5">
                                        <i data-lucide="gauge" class="w-4 h-4 text-emerald-400"></i> Free Daily Distance
                                    </div>
                                    <p class="text-slate-400 text-[11px]">{{ $vehicle->category->free_km_per_day }} km complimentary distance included every 24 hours.</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                                    <div class="font-bold text-white flex items-center gap-1.5">
                                        <i data-lucide="wallet" class="w-4 h-4 text-amber-400"></i> Escrow Security Deposit
                                    </div>
                                    <p class="text-slate-400 text-[11px]">₹{{ number_format($vehicle->category->security_deposit, 0) }} held in escrow, refunded within 24h of return.</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                                    <div class="font-bold text-white flex items-center gap-1.5">
                                        <i data-lucide="shield-check" class="w-4 h-4 text-sky-400"></i> Zero-Dep Insurance
                                    </div>
                                    <p class="text-slate-400 text-[11px]">Comprehensive commercial motor insurance & 24/7 roadside assistance.</p>
                                </div>
                                <div class="p-3.5 rounded-2xl bg-slate-950 border border-slate-800 space-y-1">
                                    <div class="font-bold text-white flex items-center gap-1.5">
                                        <i data-lucide="file-check" class="w-4 h-4 text-purple-400"></i> Digital KYC Check
                                    </div>
                                    <p class="text-slate-400 text-[11px]">Original physical Driving License & Aadhaar verification at check-in.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Col: Booking Box -->
            <div class="space-y-6">
                <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-800 shadow-2xl sticky top-24 space-y-6">
                    <div class="border-b border-slate-800 pb-5">
                        <span class="text-slate-400 text-[11px] uppercase tracking-wider font-bold">Standard Rental Tariff</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="font-display text-4xl font-extrabold text-white">₹{{ number_format($vehicle->daily_rental_price, 0) }}</span>
                            <span class="text-xs text-slate-400 font-medium">/ 24h Day</span>
                        </div>
                        <div class="text-[11px] text-emerald-400 font-semibold mt-1">Includes 18% GST (SAC 997311) + FASTag</div>
                    </div>

                    <!-- Instant Reservation Box -->
                    <div class="space-y-4">
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between text-slate-300">
                                <span>Free KMs / Day:</span>
                                <span class="font-bold text-white">{{ $vehicle->category->free_km_per_day }} km</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Excess KM Rate:</span>
                                <span class="font-bold text-white">₹{{ number_format($vehicle->category->excess_km_rate, 2) }}/km</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Refundable Security Deposit:</span>
                                <span class="font-bold text-amber-400">₹{{ number_format($vehicle->category->security_deposit, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Assistance & FASTag:</span>
                                <span class="font-bold text-emerald-400">Included</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-800 space-y-3">
                            <a href="{{ route('login') }}" class="w-full py-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-bold text-xs uppercase tracking-wider text-center block shadow-lg shadow-indigo-600/30 transition">
                                Sign In & Reserve Vehicle
                            </a>
                            <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                                Instant reservation with immediate AWB/booking confirmation voucher.
                            </p>
                        </div>
                    </div>

                    <!-- Need Help / Call Desk -->
                    <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-xs text-center space-y-1">
                        <div class="text-slate-400">Prefer phone reservation?</div>
                        <div class="font-bold text-white flex items-center justify-center gap-1.5">
                            <i data-lucide="phone-call" class="w-3.5 h-3.5 text-indigo-400"></i>
                            <span>Call Dispatch Desk: xxxxx</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
