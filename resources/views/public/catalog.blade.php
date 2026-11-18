<!DOCTYPE html>
<html lang="en" class="h-full scroll-smooth bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveSphere Mobility | Premium Self-Drive Cars & Chauffeur Fleet India</title>
    <meta name="description" content="Rent premium self-drive cars and chauffeur fleets in Mumbai, Pune, Delhi NCR and Bangalore. Zero deposit anxiety, verified sanitized fleet, 250+ km/day free and 24/7 roadside assistance.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        [x-cloak] { display: none !important; }
        .glow-indigo {
            box-shadow: 0 0 50px -10px rgba(99, 102, 241, 0.35);
        }
        .glow-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.25);
        }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white" x-data="{
    searchTab: 'self_drive',
    selectedLocation: '{{ $locations->first()->id ?? '' }}',
    selectedCategory: '{{ $categoryId ?? '' }}',
    pickupDate: '{{ date('Y-m-d', strtotime('+1 day')) }}',
    returnDate: '{{ date('Y-m-d', strtotime('+3 days')) }}',
    pickupTime: '10:00',
    returnTime: '10:00',
    faqOpen: 1,
    mobileMenu: false
}">

    <!-- Top Announcement Strip -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-700 to-sky-700 text-white text-[11px] sm:text-xs font-semibold py-2 px-4 text-center tracking-wide flex items-center justify-center gap-2">
        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-white/20 text-white font-bold text-[10px] uppercase">
            Special Offer
        </span>
        <span>🎉 Book 3+ days & get 15% instant off on self-drive hatchbacks & SUVs. Use code <strong>DRIVE15</strong> at check-in.</span>
        <span class="hidden md:inline text-indigo-200">| 📞 24/7 Helpline: +91 22 6789 4400</span>
    </div>

    <!-- Navigation Bar -->
    <header class="sticky top-0 z-50 bg-slate-950/85 backdrop-blur-xl border-b border-slate-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('public.catalog') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-sky-500 flex items-center justify-center text-white font-extrabold text-xl shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition">
                    <i data-lucide="car" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="font-display text-xl font-bold tracking-tight text-white flex items-center gap-1.5">
                        DriveSphere <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 font-sans font-semibold">ERP</span>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium tracking-wider uppercase">Enterprise Car Rentals · India</p>
                </div>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-300">
                <a href="#fleet" class="hover:text-indigo-400 transition flex items-center gap-1.5">
                    <i data-lucide="car-front" class="w-4 h-4 text-indigo-400"></i>
                    <span>Fleet Showcase</span>
                </a>
                <a href="#packages" class="hover:text-indigo-400 transition flex items-center gap-1.5">
                    <i data-lucide="tag" class="w-4 h-4 text-sky-400"></i>
                    <span>Rental Plans</span>
                </a>
                <a href="#why-us" class="hover:text-indigo-400 transition flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                    <span>Why DriveSphere</span>
                </a>
                <a href="#how-it-works" class="hover:text-indigo-400 transition flex items-center gap-1.5">
                    <i data-lucide="workflow" class="w-4 h-4 text-amber-400"></i>
                    <span>How It Works</span>
                </a>
                <a href="#reviews" class="hover:text-indigo-400 transition flex items-center gap-1.5">
                    <i data-lucide="star" class="w-4 h-4 text-amber-300"></i>
                    <span>Reviews</span>
                </a>
                <a href="#faq" class="hover:text-indigo-400 transition">FAQs</a>
            </nav>

            <!-- Right CTA Action Buttons -->
            <div class="hidden sm:flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-300 hover:text-white bg-slate-900 hover:bg-slate-800 border border-slate-700/80 transition flex items-center gap-2">
                    <i data-lucide="lock" class="w-3.5 h-3.5 text-indigo-400"></i>
                    <span>Staff & Driver Login</span>
                </a>
                <a href="#fleet" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-indigo-600 via-indigo-500 to-sky-600 hover:from-indigo-500 hover:to-sky-500 transition shadow-lg shadow-indigo-600/30 flex items-center gap-1.5 group">
                    <span>Reserve a Car</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2.5 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white focus:outline-none">
                <i data-lucide="menu" class="w-6 h-6" x-show="!mobileMenu"></i>
                <i data-lucide="x" class="w-6 h-6" x-show="mobileMenu" x-cloak></i>
            </button>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div x-show="mobileMenu" x-transition class="lg:hidden bg-slate-950 border-b border-slate-800 px-6 py-6 space-y-4" x-cloak>
            <nav class="flex flex-col space-y-3 text-sm font-semibold text-slate-300">
                <a @click="mobileMenu = false" href="#fleet" class="p-2 rounded-lg hover:bg-slate-900 text-white flex items-center gap-2">
                    <i data-lucide="car-front" class="w-4 h-4 text-indigo-400"></i> Fleet Showcase
                </a>
                <a @click="mobileMenu = false" href="#packages" class="p-2 rounded-lg hover:bg-slate-900 text-white flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-sky-400"></i> Rental Plans
                </a>
                <a @click="mobileMenu = false" href="#why-us" class="p-2 rounded-lg hover:bg-slate-900 text-white flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i> Why DriveSphere
                </a>
                <a @click="mobileMenu = false" href="#how-it-works" class="p-2 rounded-lg hover:bg-slate-900 text-white flex items-center gap-2">
                    <i data-lucide="workflow" class="w-4 h-4 text-amber-400"></i> How It Works
                </a>
                <a @click="mobileMenu = false" href="#reviews" class="p-2 rounded-lg hover:bg-slate-900 text-white flex items-center gap-2">
                    <i data-lucide="star" class="w-4 h-4 text-amber-300"></i> Customer Reviews
                </a>
                <a @click="mobileMenu = false" href="#faq" class="p-2 rounded-lg hover:bg-slate-900 text-white flex items-center gap-2">
                    <i data-lucide="help-circle" class="w-4 h-4 text-indigo-400"></i> FAQs
                </a>
            </nav>
            <div class="pt-4 border-t border-slate-800 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="w-full py-3 rounded-xl text-center text-xs font-bold text-slate-200 bg-slate-900 border border-slate-700">
                    Staff & Driver ERP Login
                </a>
                <a @click="mobileMenu = false" href="#fleet" class="w-full py-3 rounded-xl text-center text-xs font-bold text-white bg-indigo-600">
                    Reserve a Vehicle
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-12 pb-24 px-4 sm:px-6 overflow-hidden">
        <!-- Background Lighting Glows -->
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1000px] h-[550px] bg-gradient-to-b from-indigo-600/25 via-sky-500/10 to-transparent blur-[120px] pointer-events-none rounded-full"></div>
        <div class="absolute top-1/3 -left-40 w-80 h-80 bg-indigo-600/15 blur-[100px] pointer-events-none rounded-full"></div>
        <div class="absolute top-1/4 -right-40 w-96 h-96 bg-sky-500/15 blur-[120px] pointer-events-none rounded-full"></div>

        <div class="max-w-6xl mx-auto text-center space-y-6 relative z-10">
            <!-- Pill Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-900/90 border border-indigo-500/30 text-indigo-300 text-xs font-semibold shadow-inner">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>India's Most Trusted Car Rental & Chauffeur Fleet</span>
                <span class="text-slate-500 hidden sm:inline">•</span>
                <span class="text-slate-400 hidden sm:inline">Mumbai · Pune · Delhi NCR · Bengaluru</span>
            </div>

            <!-- Primary Headline -->
            <h1 class="font-display text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.1]">
                Drive Your Freedom.<br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-indigo-400 via-sky-300 to-emerald-400 bg-clip-text text-transparent">
                    Zero Deposit Anxiety.
                </span>
            </h1>

            <!-- Subtitle -->
            <p class="max-w-2xl mx-auto text-slate-300 text-sm sm:text-base leading-relaxed">
                Self-drive hatchbacks, executive sedans, 4x4 offroad SUVs & chauffeured outstation fleets. Instant digital KYC, free 250+ km/day, comprehensive insurance & 24/7 roadside assistance.
            </p>

            <!-- Quick Trust Badges Strip -->
            <div class="pt-2 flex flex-wrap items-center justify-center gap-4 sm:gap-8 text-xs font-semibold text-slate-400">
                <div class="flex items-center gap-2 text-slate-300">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400"></i>
                    <span>Zero Hidden Surcharges</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <i data-lucide="zap" class="w-4 h-4 text-amber-400"></i>
                    <span>60-Second Digital KYC</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <i data-lucide="sparkles" class="w-4 h-4 text-sky-400"></i>
                    <span>28-Point Sanitized & Inspected</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <i data-lucide="plane-takeoff" class="w-4 h-4 text-indigo-400"></i>
                    <span>Airport Terminal Delivery</span>
                </div>
            </div>

            <!-- Floating Interactive Booking & Filter Console -->
            <div class="pt-8 max-w-4xl mx-auto">
                <div class="bg-slate-900/90 backdrop-blur-2xl p-6 sm:p-8 rounded-3xl border border-slate-800 shadow-2xl glow-indigo text-left space-y-6">
                    <!-- Service Mode Switcher Tabs -->
                    <div class="flex flex-wrap items-center gap-2 border-b border-slate-800/80 pb-5">
                        <button type="button" @click="searchTab = 'self_drive'" :class="searchTab === 'self_drive' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-800/60 text-slate-400 hover:text-white'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                            <i data-lucide="key" class="w-4 h-4"></i>
                            <span>Self-Drive (Without Driver)</span>
                        </button>
                        <button type="button" @click="searchTab = 'chauffeur'" :class="searchTab === 'chauffeur' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-800/60 text-slate-400 hover:text-white'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                            <span>Chauffeur Driven (With Driver)</span>
                        </button>
                        <button type="button" @click="searchTab = 'airport'" :class="searchTab === 'airport' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-800/60 text-slate-400 hover:text-white'" class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                            <i data-lucide="plane" class="w-4 h-4"></i>
                            <span>Airport Curbside Transfer</span>
                        </button>
                    </div>

                    <!-- Search Inputs Form -->
                    <form action="{{ route('public.catalog') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Pickup Location -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-400"></i>
                                    <span>Pickup Hub</span>
                                </label>
                                <select name="location_id" x-model="selectedLocation" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-3.5 py-3 text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }} ({{ $loc->city }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Vehicle Category -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                    <i data-lucide="car" class="w-3.5 h-3.5 text-sky-400"></i>
                                    <span>Car Category</span>
                                </label>
                                <select name="category_id" x-model="selectedCategory" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-3.5 py-3 text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                                    <option value="">All Categories (Any)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pickup Date -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                    <i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-400"></i>
                                    <span>Pickup Date</span>
                                </label>
                                <input type="date" name="pickup_date" x-model="pickupDate" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-3.5 py-3 text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>

                            <!-- Return Date -->
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1.5">
                                    <i data-lucide="calendar-check" class="w-3.5 h-3.5 text-amber-400"></i>
                                    <span>Return Date</span>
                                </label>
                                <input type="date" name="return_date" x-model="returnDate" class="w-full bg-slate-950 border border-slate-700/80 rounded-xl px-3.5 py-3 text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            </div>
                        </div>

                        <!-- Action Submit Row -->
                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-xs text-slate-400 flex items-center gap-2">
                                <i data-lucide="shield" class="w-4 h-4 text-emerald-400"></i>
                                <span>Includes <strong>250 km/day</strong> complimentary with full comprehensive coverage.</span>
                            </div>

                            <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-500 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2 group">
                                <i data-lucide="search" class="w-4 h-4"></i>
                                <span>Search Live Available Cars</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Metrics Stats Strip -->
    <section class="border-y border-slate-800/80 bg-slate-900/40 py-10 px-4 sm:px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="space-y-1">
                <div class="font-display text-3xl sm:text-4xl font-extrabold text-white">500+</div>
                <div class="text-xs font-semibold text-indigo-400 uppercase tracking-wider">Sanitized Fleet Vehicles</div>
                <p class="text-[11px] text-slate-400">Late models (2023–2025)</p>
            </div>
            <div class="space-y-1">
                <div class="font-display text-3xl sm:text-4xl font-extrabold text-emerald-400">65,000+</div>
                <div class="text-xs font-semibold text-emerald-400 uppercase tracking-wider">Trips Completed</div>
                <p class="text-[11px] text-slate-400">Zero breakdown guarantees</p>
            </div>
            <div class="space-y-1">
                <div class="font-display text-3xl sm:text-4xl font-extrabold text-sky-400">4.9 ★</div>
                <div class="text-xs font-semibold text-sky-400 uppercase tracking-wider">Customer Satisfaction</div>
                <p class="text-[11px] text-slate-400">Google & verified reviews</p>
            </div>
            <div class="space-y-1">
                <div class="font-display text-3xl sm:text-4xl font-extrabold text-amber-400">100%</div>
                <div class="text-xs font-semibold text-amber-400 uppercase tracking-wider">Escrow Deposit Refund</div>
                <p class="text-[11px] text-slate-400">Credited within 24h of return</p>
            </div>
        </div>
    </section>

    <!-- Fleet Showcase & Catalog Section -->
    <section id="fleet" class="py-16 px-4 sm:px-6 max-w-7xl mx-auto w-full space-y-10">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Handpicked Premium Garage</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white mt-1">
                    Available Fleet for Rent
                </h2>
                <p class="text-sm text-slate-400 mt-1 max-w-xl">
                    All cars are verified, comprehensively insured, sanitized before dispatch, and equipped with GPS tracking and all-India FASTag.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 font-medium">Currently viewing:</span>
                <span class="text-xs font-bold text-white bg-slate-800 px-3 py-1.5 rounded-lg border border-slate-700">
                    {{ $vehicles->count() }} Available Vehicles
                </span>
            </div>
        </div>

        <!-- Category & Transmission Filter Pills -->
        <div class="bg-slate-900/80 p-3.5 rounded-2xl border border-slate-800 shadow-md flex flex-wrap items-center justify-between gap-4">
            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto text-xs font-semibold py-1">
                <a href="{{ route('public.catalog') }}#fleet" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ !$categoryId ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    All Categories
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('public.catalog', ['category_id' => $cat->id]) }}#fleet" class="px-4 py-2 rounded-xl transition flex-shrink-0 {{ $categoryId == $cat->id ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>

            <!-- Transmission / Quick Filter -->
            <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-500 text-[11px] uppercase font-bold">Gearbox:</span>
                <a href="{{ route('public.catalog', array_merge(request()->query(), ['transmission' => 'automatic'])) }}#fleet" class="px-3 py-1.5 rounded-lg transition {{ request('transmission') == 'automatic' ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                    Automatic
                </a>
                <a href="{{ route('public.catalog', array_merge(request()->query(), ['transmission' => 'manual'])) }}#fleet" class="px-3 py-1.5 rounded-lg transition {{ request('transmission') == 'manual' ? 'bg-sky-600 text-white' : 'bg-slate-800 text-slate-400 hover:text-white' }}">
                    Manual
                </a>
                @if(request()->hasAny(['category_id', 'transmission', 'fuel_type']))
                    <a href="{{ route('public.catalog') }}#fleet" class="text-rose-400 hover:underline text-[11px] font-semibold ml-2">Reset</a>
                @endif
            </div>
        </div>

        <!-- Vehicle Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse ($vehicles as $v)
                <div class="bg-slate-900/90 rounded-3xl border border-slate-800/90 hover:border-indigo-500/50 shadow-xl glow-card transition-all duration-300 flex flex-col justify-between overflow-hidden group">
                    <!-- Top Media Thumbnail with Badges -->
                    <div class="relative h-56 bg-slate-950 overflow-hidden">
                        <img src="{{ $v->image_url }}" alt="{{ $v->model->brand->name }} {{ $v->model->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-transparent to-black/40"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-3.5 left-3.5 right-3.5 flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-slate-950/80 backdrop-blur-md text-indigo-400 border border-indigo-500/30">
                                {{ $v->category->name }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/90 text-slate-950 flex items-center gap-1 shadow-sm">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                <span>Available</span>
                            </span>
                        </div>

                        <!-- Bottom Overlay Info -->
                        <div class="absolute bottom-3 left-4 right-4 flex items-end justify-between">
                            <div>
                                <span class="text-[11px] font-mono text-slate-300 bg-slate-950/70 px-2 py-0.5 rounded border border-slate-700/60">{{ $v->registration_number }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs font-bold text-amber-300 bg-slate-950/80 px-2 py-1 rounded-lg border border-slate-800">
                                <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-300 text-amber-300"></i>
                                <span>4.9</span>
                                <span class="text-slate-400 text-[10px] font-normal">(120+ trips)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6 space-y-5 flex-1 flex flex-col justify-between">
                        <!-- Model Title & Hub -->
                        <div>
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h3 class="font-display text-xl font-bold text-white group-hover:text-indigo-400 transition">
                                        {{ $v->model->brand->name }} {{ $v->model->name }}
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-400"></i>
                                        <span>Stationed at: <strong>{{ $v->branch->name }}</strong></span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Specs Matrix -->
                        <div class="grid grid-cols-4 gap-2 text-center text-xs py-3 border-y border-slate-800">
                            <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800/80">
                                <i data-lucide="users" class="w-4 h-4 text-indigo-400 mx-auto mb-1"></i>
                                <div class="font-bold text-slate-200 text-xs">{{ $v->seating_capacity }}</div>
                                <div class="text-[9px] text-slate-400 uppercase">Seats</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800/80">
                                <i data-lucide="cog" class="w-4 h-4 text-sky-400 mx-auto mb-1"></i>
                                <div class="font-bold text-slate-200 text-xs capitalize">{{ $v->transmission }}</div>
                                <div class="text-[9px] text-slate-400 uppercase">Gearbox</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800/80">
                                <i data-lucide="fuel" class="w-4 h-4 text-emerald-400 mx-auto mb-1"></i>
                                <div class="font-bold text-slate-200 text-xs capitalize">{{ $v->fuel_type }}</div>
                                <div class="text-[9px] text-slate-400 uppercase">Fuel</div>
                            </div>
                            <div class="p-2 rounded-xl bg-slate-950/60 border border-slate-800/80">
                                <i data-lucide="briefcase" class="w-4 h-4 text-amber-400 mx-auto mb-1"></i>
                                <div class="font-bold text-slate-200 text-xs">{{ $v->category->baggage_capacity ?? 3 }} Bags</div>
                                <div class="text-[9px] text-slate-400 uppercase">Luggage</div>
                            </div>
                        </div>

                        <!-- Included Amenities & Free KMs -->
                        <div class="space-y-1.5 text-xs text-slate-300">
                            <div class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-emerald-400 flex-shrink-0"></i>
                                <span><strong>{{ $v->category->free_km_per_day }} km/day</strong> complimentary distance included</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="shield-check" class="w-4 h-4 text-indigo-400 flex-shrink-0"></i>
                                <span>Zero-Dep Comprehensive Insurance & 24/7 Roadside Aid</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="wallet" class="w-4 h-4 text-amber-400 flex-shrink-0"></i>
                                <span>Refundable Escrow Deposit: <strong>₹{{ number_format($v->category->security_deposit, 0) }}</strong></span>
                            </div>
                        </div>

                        <!-- Price & Booking CTA -->
                        <div class="pt-4 border-t border-slate-800 flex items-center justify-between gap-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Standard Tariff</span>
                                <div class="flex items-baseline gap-1">
                                    <span class="font-display text-2xl font-extrabold text-white">₹{{ number_format($v->daily_rental_price, 0) }}</span>
                                    <span class="text-xs text-slate-400 font-medium">/ 24h Day</span>
                                </div>
                            </div>

                            <a href="{{ route('public.vehicle', $v->id) }}" class="px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-bold text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition flex items-center gap-1.5 group-hover:scale-105">
                                <span>Book Now</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-slate-400 bg-slate-900/50 rounded-3xl border border-slate-800 space-y-3">
                    <i data-lucide="car-off" class="w-12 h-12 text-slate-600 mx-auto"></i>
                    <h3 class="font-display text-lg font-bold text-white">No vehicles found in this category</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">Try clearing your filters or select "All Categories" to view available cars.</p>
                    <a href="{{ route('public.catalog') }}#fleet" class="inline-block px-4 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold">
                        View All Vehicles
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Why Choose DriveSphere Section -->
    <section id="why-us" class="py-20 px-4 sm:px-6 bg-slate-900/50 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Engineered for Excellence</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white">
                    Why Travel With DriveSphere?
                </h2>
                <p class="text-sm text-slate-400">
                    We eliminate the frustration of car rentals with transparent pricing, zero deposit anxiety, and certified spotless cars.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Advantage 1 -->
                <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4 hover:border-indigo-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-white">Zero Deposit Anxiety</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Security deposits are maintained in segregated escrow liability accounts and refunded within 24 hours of vehicle return directly to your bank account.
                    </p>
                </div>

                <!-- Advantage 2 -->
                <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4 hover:border-sky-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-sky-600/20 text-sky-400 flex items-center justify-center border border-sky-500/30">
                        <i data-lucide="smartphone" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-white">360° Digital Inspection</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        No paper checklists or surprise damage claims. Complete a digital walk-around inspection on your smartphone with timestamped photos before key handover.
                    </p>
                </div>

                <!-- Advantage 3 -->
                <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4 hover:border-emerald-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-white">28-Point Sanitization</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Every vehicle undergoes deep mechanical testing, fluid level verification, AC duct ozone disinfection, and vacuum cleaning prior to dispatch.
                    </p>
                </div>

                <!-- Advantage 4 -->
                <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4 hover:border-amber-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-amber-600/20 text-amber-400 flex items-center justify-center border border-amber-500/30">
                        <i data-lucide="gauge" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-white">Generous 250+ KM Daily</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Drive freely without keeping an eye on the odometer. Enjoy 250 to 300 free kilometers per day, with clear, transparent rates for excess distance.
                    </p>
                </div>

                <!-- Advantage 5 -->
                <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4 hover:border-purple-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-purple-600/20 text-purple-400 flex items-center justify-center border border-purple-500/30">
                        <i data-lucide="user-check" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-white">Verified Elite Chauffeurs</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Need to relax? Our professional chauffeurs are police-verified, uniformed, experienced on inter-city highway routes, and trained in hospitality.
                    </p>
                </div>

                <!-- Advantage 6 -->
                <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4 hover:border-rose-500/40 transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-600/20 text-rose-400 flex items-center justify-center border border-rose-500/30">
                        <i data-lucide="life-buoy" class="w-6 h-6"></i>
                    </div>
                    <h3 class="font-display text-lg font-bold text-white">24/7 Roadside SOS Support</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Flat tyre? Battery drain? We have pan-India roadside assistance with 60-minute dispatch & emergency replacement car guarantee anywhere on national highways.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 px-4 sm:px-6 max-w-7xl mx-auto w-full space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Simple 4-Step Process</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white">
                How DriveSphere Works
            </h2>
            <p class="text-sm text-slate-400">
                From booking to key handover in under 3 minutes.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            <!-- Step 1 -->
            <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 text-center space-y-4 relative">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-lg shadow-indigo-600/30">
                    1
                </div>
                <h4 class="font-display text-base font-bold text-white">Select Your Car</h4>
                <p class="text-xs text-slate-400">Choose your preferred model, pickup airport or city hub, and reservation dates.</p>
            </div>

            <!-- Step 2 -->
            <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 text-center space-y-4 relative">
                <div class="w-14 h-14 rounded-2xl bg-sky-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-lg shadow-sky-600/30">
                    2
                </div>
                <h4 class="font-display text-base font-bold text-white">60-Sec Digital KYC</h4>
                <p class="text-xs text-slate-400">Verify your Driving License & Aadhaar online with zero physical paperwork.</p>
            </div>

            <!-- Step 3 -->
            <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 text-center space-y-4 relative">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-lg shadow-emerald-600/30">
                    3
                </div>
                <h4 class="font-display text-base font-bold text-white">Inspect & Drive</h4>
                <p class="text-xs text-slate-400">Quick 360° smartphone inspection at pickup desk, collect keys and hit the road.</p>
            </div>

            <!-- Step 4 -->
            <div class="p-6 rounded-3xl bg-slate-900/80 border border-slate-800 text-center space-y-4 relative">
                <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white font-extrabold text-xl flex items-center justify-center mx-auto shadow-lg shadow-amber-600/30">
                    4
                </div>
                <h4 class="font-display text-base font-bold text-white">Effortless Return</h4>
                <p class="text-xs text-slate-400">Drop off at any designated hub and receive your security deposit refund within 24 hours.</p>
            </div>
        </div>
    </section>

    <!-- Rental Packages Grid -->
    <section id="packages" class="py-20 px-4 sm:px-6 bg-slate-900/40 border-y border-slate-800/80">
        <div class="max-w-7xl mx-auto space-y-12">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Flexible Mobility Options</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white">
                    Tailored Packages For Every Journey
                </h2>
                <p class="text-sm text-slate-400">
                    Whether it's a 4-hour city errand, a weekend getaway to Lonavala, or an executive outstation trip.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Package 1: Daily Self-Drive -->
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl space-y-6 flex flex-col justify-between hover:border-indigo-500/50 transition">
                    <div class="space-y-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                            Most Popular
                        </span>
                        <h3 class="font-display text-2xl font-bold text-white">Daily Self-Drive</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Complete independence for city driving, weekend roadtrips, and personal vacations.
                        </p>
                        <div class="font-display text-3xl font-extrabold text-white pt-2">
                            ₹1,800 <span class="text-xs text-slate-400 font-sans font-normal">/ day onwards</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-300 pt-2 border-t border-slate-800">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Free 250 km included per 24h</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Comprehensive zero-dep insurance</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> All-India commercial permit + FASTag</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Free cancellation up to 6h before pickup</li>
                        </ul>
                    </div>
                    <a href="#fleet" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider text-center transition">
                        Select Self-Drive Car
                    </a>
                </div>

                <!-- Package 2: Chauffeur Outstation -->
                <div class="p-8 rounded-3xl bg-gradient-to-b from-indigo-950/80 to-slate-900 border-2 border-indigo-500 shadow-2xl space-y-6 flex flex-col justify-between relative">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-gradient-to-r from-indigo-500 to-sky-500 text-white shadow-md">
                        Executive Chauffeur
                    </div>
                    <div class="space-y-4 pt-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-500/20 text-sky-300 border border-sky-500/30">
                            Chauffeur-Driven
                        </span>
                        <h3 class="font-display text-2xl font-bold text-white">Outstation Chauffeur</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Sit back and relax in premium Innova Crysta or BMW while our verified highway chauffeur drives.
                        </p>
                        <div class="font-display text-3xl font-extrabold text-white pt-2">
                            ₹3,500 <span class="text-xs text-slate-400 font-sans font-normal">/ day onwards</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-300 pt-2 border-t border-slate-800">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Experienced highway chauffeur in uniform</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Zero security deposit required</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Free 300 km/day package</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Transparent night bata & driver allowance</li>
                        </ul>
                    </div>
                    <a href="#fleet" class="w-full py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-sky-600 hover:from-indigo-500 hover:to-sky-500 text-white font-bold text-xs uppercase tracking-wider text-center transition shadow-lg shadow-indigo-600/30">
                        Book Chauffeur Fleet
                    </a>
                </div>

                <!-- Package 3: Airport Meet & Greet -->
                <div class="p-8 rounded-3xl bg-slate-900 border border-slate-800 shadow-xl space-y-6 flex flex-col justify-between hover:border-indigo-500/50 transition">
                    <div class="space-y-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            Airport Express
                        </span>
                        <h3 class="font-display text-2xl font-bold text-white">Airport Express Pickup</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Dedicated curbside delivery at Mumbai CSMIA T2, Pune, and Delhi Airport terminals.
                        </p>
                        <div class="font-display text-3xl font-extrabold text-white pt-2">
                            ₹1,200 <span class="text-xs text-slate-400 font-sans font-normal">/ transfer onwards</span>
                        </div>
                        <ul class="space-y-2.5 text-xs text-slate-300 pt-2 border-t border-slate-800">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Flight tracking with free 60m wait time</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Terminal arrival gate meeting representative</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Luggage assistance & priority exit</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-emerald-400"></i> Sanitized premium cabin</li>
                        </ul>
                    </div>
                    <a href="#fleet" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs uppercase tracking-wider text-center transition">
                        Select Airport Transfer
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials / Verified Customer Reviews -->
    <section id="reviews" class="py-20 px-4 sm:px-6 max-w-7xl mx-auto w-full space-y-12">
        <div class="text-center max-w-2xl mx-auto space-y-3">
            <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Real Customer Experiences</span>
            <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white">
                Loved by 65,000+ Drivers in India
            </h2>
            <p class="text-sm text-slate-400">
                Rated 4.9/5 stars across Google Reviews & mobility surveys.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review 1 -->
            <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4">
                <div class="flex items-center gap-1 text-amber-400">
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "Booked an Innova Crysta for a 4-day family trip from Mumbai to Mahabaleshwar. The car was spotless, delivered right to T2 airport terminal on time. Zero deposit hassle, deposit refunded within 18 hours. 10/10 service!"
                </p>
                <div class="pt-2 flex items-center gap-3 border-t border-slate-800">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-sky-500 flex items-center justify-center font-bold text-sm text-white">
                        AK
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Anand Kulkarni</div>
                        <div class="text-[10px] text-slate-400">VP Marketing, Mumbai · Innova Crysta VX</div>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4">
                <div class="flex items-center gap-1 text-amber-400">
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "The BMW 330Li was immaculate for my client meetings across BKC and South Mumbai. Digital inspection on mobile took less than 2 minutes. Transparent GST billing made company expense reimbursement seamless."
                </p>
                <div class="pt-2 flex items-center gap-3 border-t border-slate-800">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-sky-500 to-emerald-500 flex items-center justify-center font-bold text-sm text-white">
                        RS
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Rohit Sharma</div>
                        <div class="text-[10px] text-slate-400">Managing Director, Bandra · BMW 330Li</div>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="p-8 rounded-3xl bg-slate-900/90 border border-slate-800 shadow-xl space-y-4">
                <div class="flex items-center gap-1 text-amber-400">
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed italic">
                    "Took the Scorpio-N for a roadtrip into the Western Ghats. Ground clearance, 4Xplor mode, and GPS tracking were top notch. Customer support is active 24/7. Will never use any other car rental service again."
                </p>
                <div class="pt-2 flex items-center gap-3 border-t border-slate-800">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-500 to-rose-500 flex items-center justify-center font-bold text-sm text-white">
                        PM
                    </div>
                    <div>
                        <div class="text-xs font-bold text-white">Pooja Mathur</div>
                        <div class="text-[10px] text-slate-400">Software Architect, Pune · Scorpio-N Z8L</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section (Accordion) -->
    <section id="faq" class="py-20 px-4 sm:px-6 bg-slate-900/50 border-y border-slate-800/80">
        <div class="max-w-4xl mx-auto space-y-10">
            <div class="text-center space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-indigo-400">Got Questions?</span>
                <h2 class="font-display text-3xl sm:text-4xl font-extrabold text-white">
                    Frequently Asked Questions
                </h2>
                <p class="text-sm text-slate-400">
                    Everything you need to know about our self-drive and chauffeur rental services.
                </p>
            </div>

            <div class="space-y-4">
                <!-- Q1 -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-md transition">
                    <button type="button" @click="faqOpen = (faqOpen === 1 ? 0 : 1)" class="w-full flex items-center justify-between text-left text-sm font-bold text-white focus:outline-none">
                        <span>What documents are required to rent a self-drive car?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-indigo-400 transition-transform" :class="faqOpen === 1 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="faqOpen === 1" x-collapse class="mt-3 text-xs text-slate-400 leading-relaxed border-t border-slate-800 pt-3">
                        To drive yourself, you must be at least 21 years of age and hold an original physical Driving License (minimum 1 year old) along with a valid Indian Government ID (Aadhaar or Passport). International travelers require an International Driving Permit (IDP) and Passport.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-md transition">
                    <button type="button" @click="faqOpen = (faqOpen === 2 ? 0 : 2)" class="w-full flex items-center justify-between text-left text-sm font-bold text-white focus:outline-none">
                        <span>How does the security deposit work, and when is it refunded?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-indigo-400 transition-transform" :class="faqOpen === 2 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="faqOpen === 2" x-collapse class="mt-3 text-xs text-slate-400 leading-relaxed border-t border-slate-800 pt-3">
                        A refundable security deposit is held under our escrow accounting system at check-in (₹4,000 for hatchbacks, up to ₹10,000 for luxury SUVs). Upon return, our staff completes an automated digital check (fuel, odometer, scratches), and your deposit is refunded directly to your original payment method within 24 hours.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-md transition">
                    <button type="button" @click="faqOpen = (faqOpen === 3 ? 0 : 3)" class="w-full flex items-center justify-between text-left text-sm font-bold text-white focus:outline-none">
                        <span>Is fuel included in the rental price?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-indigo-400 transition-transform" :class="faqOpen === 3 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="faqOpen === 3" x-collapse class="mt-3 text-xs text-slate-400 leading-relaxed border-t border-slate-800 pt-3">
                        Self-drive bookings operate on a like-to-like fuel policy: cars are dispatched with a specific fuel level (typically 100% full or 6/8 scale) and should be returned with the same fuel level. If returned lower, the actual fuel deficit is adjusted from the security deposit at standard Indian oil pump rates without penalty markups.
                    </div>
                </div>

                <!-- Q4 -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-md transition">
                    <button type="button" @click="faqOpen = (faqOpen === 4 ? 0 : 4)" class="w-full flex items-center justify-between text-left text-sm font-bold text-white focus:outline-none">
                        <span>Can I take the car across state borders in India?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-indigo-400 transition-transform" :class="faqOpen === 4 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="faqOpen === 4" x-collapse class="mt-3 text-xs text-slate-400 leading-relaxed border-t border-slate-800 pt-3">
                        Yes! All DriveSphere vehicles carry valid All-India Commercial Tourism Permits and active FASTag stickers for electronic toll plaza clearance. You can freely drive across state lines (e.g., Maharashtra to Goa or Gujarat). Any state border entry taxes can be paid seamlessly via the state parivahan portal.
                    </div>
                </div>

                <!-- Q5 -->
                <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 shadow-md transition">
                    <button type="button" @click="faqOpen = (faqOpen === 5 ? 0 : 5)" class="w-full flex items-center justify-between text-left text-sm font-bold text-white focus:outline-none">
                        <span>What insurance coverage is included?</span>
                        <i data-lucide="chevron-down" class="w-5 h-5 text-indigo-400 transition-transform" :class="faqOpen === 5 ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="faqOpen === 5" x-collapse class="mt-3 text-xs text-slate-400 leading-relaxed border-t border-slate-800 pt-3">
                        All our fleet cars carry Comprehensive Commercial Motor Insurance with Zero-Depreciation coverage. In the unlikely event of an accident, your maximum liability is strictly capped at the security deposit amount (provided traffic laws and rental terms were followed).
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="max-w-5xl mx-auto rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-600 to-sky-600 p-10 sm:p-14 text-center text-white relative shadow-2xl glow-indigo space-y-6">
            <h2 class="font-display text-3xl sm:text-5xl font-extrabold tracking-tight">
                Ready to Hit the Highway?
            </h2>
            <p class="text-sm sm:text-base text-indigo-100 max-w-xl mx-auto">
                Reserve your car in 60 seconds with instant booking confirmation, verified sanitized fleet, and airport curbside delivery.
            </p>
            <div class="pt-2 flex flex-wrap items-center justify-center gap-4">
                <a href="#fleet" class="px-8 py-3.5 rounded-xl bg-white text-slate-950 font-bold text-xs uppercase tracking-wider hover:bg-slate-100 transition shadow-lg flex items-center gap-2">
                    <i data-lucide="car" class="w-4 h-4 text-indigo-600"></i>
                    <span>Browse Available Cars</span>
                </a>
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-xl bg-slate-950/60 hover:bg-slate-950/80 border border-white/20 text-white font-bold text-xs uppercase tracking-wider transition flex items-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4 text-sky-400"></i>
                    <span>Staff & Driver ERP Sign In</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Comprehensive Footer -->
    <footer class="bg-slate-950 text-slate-400 pt-16 pb-12 px-4 sm:px-6 border-t border-slate-800/80 text-xs">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800/80">
            <!-- Brand Column -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-bold text-base">
                        DS
                    </div>
                    <div>
                        <div class="font-display text-base font-bold text-white tracking-tight">DriveSphere Mobility</div>
                        <p class="text-[10px] text-indigo-400 uppercase font-semibold">Car Rentals India</p>
                    </div>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Enterprise automobile rental and chauffeur fleet software engineered for self-drive freedom, airport transfers, and outstation luxury travel across India.
                </p>
                <div class="text-[11px] text-slate-500 font-mono">
                    GSTIN: 27AABCD1234E1Z5 · CIN: U63030MH2021PTC359871
                </div>
            </div>

            <!-- Hub Locations -->
            <div class="space-y-3">
                <h4 class="font-bold uppercase tracking-wider text-white text-xs">Operating Hubs</h4>
                <ul class="space-y-2 text-slate-400 text-xs">
                    <li>📍 Mumbai CSMIA Airport Terminal 2 Hub</li>
                    <li>📍 Mumbai Bandra West Corporate Office</li>
                    <li>📍 Pune Hinjewadi IT Park Depot</li>
                    <li>📍 Delhi IGI Airport Terminal 3 Hub</li>
                    <li>📍 Bengaluru Indiranagar Mobility Center</li>
                    <li>📍 Goa Dabolim & MOPA Airport Counters</li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div class="space-y-3">
                <h4 class="font-bold uppercase tracking-wider text-white text-xs">Quick Access</h4>
                <ul class="space-y-2 text-slate-400 text-xs">
                    <li><a href="#fleet" class="hover:text-indigo-400 transition">Self-Drive Hatchbacks & Sedans</a></li>
                    <li><a href="#fleet" class="hover:text-indigo-400 transition">SUV & 4x4 Offroad Rentals</a></li>
                    <li><a href="#packages" class="hover:text-indigo-400 transition">Chauffeur Outstation Trips</a></li>
                    <li><a href="#packages" class="hover:text-indigo-400 transition">Airport Curbside Transfer</a></li>
                    <li><a href="{{ route('login') }}" class="text-indigo-400 hover:underline">ERP Operator & Driver Login</a></li>
                </ul>
            </div>

            <!-- Contact & Helpline -->
            <div class="space-y-3">
                <h4 class="font-bold uppercase tracking-wider text-white text-xs">24/7 Roadside Support</h4>
                <p class="text-xs text-slate-400">
                    Velocity Tower, Western Express Highway, Andheri East, Mumbai, Maharashtra 400069
                </p>
                <div class="space-y-1 text-slate-300">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-indigo-400"></i>
                        <span>+91 22 6789 4400 / 4401</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-sky-400"></i>
                        <span>bookings@drivesphere.com</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
            <p>© {{ date('Y') }} DriveSphere Mobility Solutions Pvt. Ltd. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-slate-300 transition">Privacy Policy</a>
                <a href="#" class="hover:text-slate-300 transition">Terms & Rental Conditions</a>
                <a href="#" class="hover:text-slate-300 transition">Zero-Dep Insurance Policy</a>
                <a href="{{ route('login') }}" class="text-indigo-400 hover:underline">Staff Sign In</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
