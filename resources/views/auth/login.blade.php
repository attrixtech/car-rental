<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | WebotApp DriveSphere ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full flex items-center justify-center p-6 bg-radial-gradient">
    <div class="max-w-md w-full">
        <!-- Logo and Brand -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 mx-auto flex items-center justify-center text-white font-extrabold text-2xl shadow-xl shadow-indigo-600/30">
                WA
            </div>
            <h1 class="text-2xl font-bold text-white mt-4 tracking-tight">WebotApp DriveSphere ERP</h1>
            <p class="text-sm text-slate-400 mt-1">Car Rental, Self-Drive, Chauffeur & Fleet Management</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-2xl p-8 shadow-2xl border border-slate-100">
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', 'admin@drivesphere.com') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-600 focus:outline-none transition">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Password</label>
                    </div>
                    <input type="password" name="password" value="password123" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-600 focus:outline-none transition">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center text-xs text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded text-indigo-600 focus:ring-indigo-500 mr-2" checked>
                        Remember session
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700 transition shadow-md shadow-indigo-600/20 mt-2">
                    Sign In to ERP
                </button>
            </form>

            <!-- Quick Demo Credentials Switcher -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <div class="text-center mb-3">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Instant Role Switcher (1-Click)</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <a href="{{ route('quick-login', 'admin') }}" class="p-2.5 rounded-lg border border-indigo-200 bg-indigo-50/50 hover:bg-indigo-100/70 text-indigo-900 font-medium text-center transition">
                        👑 Super Admin
                    </a>
                    <a href="{{ route('quick-login', 'fleet') }}" class="p-2.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-800 font-medium text-center transition">
                        🚗 Fleet Manager
                    </a>
                    <a href="{{ route('quick-login', 'staff') }}" class="p-2.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-800 font-medium text-center transition">
                        📋 Counter Staff
                    </a>
                    <a href="{{ route('quick-login', 'driver') }}" class="p-2.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-800 font-medium text-center transition">
                        🚖 Chauffeur
                    </a>
                    <a href="{{ route('quick-login', 'customer') }}" class="col-span-2 p-2.5 rounded-lg border border-emerald-200 bg-emerald-50/60 hover:bg-emerald-100 text-emerald-900 font-medium text-center transition">
                        👤 Customer Portal
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('public.catalog') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition">
                ← Explore Public Customer Car Catalog
            </a>
        </div>
    </div>
</body>
</html>

