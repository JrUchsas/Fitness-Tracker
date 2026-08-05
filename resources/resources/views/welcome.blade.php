<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FitPulse - Modern Indoor Fitness & Exercise Tracker</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>

        <!-- Scripts / Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white">
        
        {{-- Background Glow Gradients --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-gradient-to-tr from-indigo-600/30 via-purple-600/20 to-pink-500/10 rounded-full blur-3xl opacity-70"></div>
            <div class="absolute top-1/3 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 flex flex-col min-h-screen">
            
            {{-- Navigation --}}
            <header class="max-w-7xl w-full mx-auto px-6 sm:px-8 py-6 flex items-center justify-between">
                <a href="/" class="inline-flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-white tracking-tight">Fit<span class="text-indigo-400">Pulse</span></span>
                </a>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm rounded-xl transition duration-150 shadow-md shadow-indigo-500/25">
                                Go to Dashboard &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-slate-300 hover:text-white font-semibold text-sm transition-colors">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/25 transition duration-150 transform hover:-translate-y-0.5">
                                    Get Started Free
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>

            {{-- Hero Section --}}
            <main class="flex-1 max-w-7xl w-full mx-auto px-6 sm:px-8 py-12 lg:py-20 flex flex-col lg:flex-row items-center justify-between gap-12">
                
                {{-- Hero Text --}}
                <div class="max-w-2xl text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-6">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        Precision Indoor Exercise Tracker
                    </div>

                    <h1 class="text-5xl sm:text-6xl font-black text-white leading-none tracking-tight">
                        Transform how you log your <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">indoor workouts.</span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-400 leading-relaxed max-w-xl">
                        Log indoor cycling, treadmill running, rowing, and yoga sessions effortlessly. Monitor your weekly duration, distance, and calorie burns with real-time stats.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold text-base rounded-2xl shadow-xl shadow-indigo-500/30 transition transform hover:-translate-y-0.5 text-center">
                                Open Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-extrabold text-base rounded-2xl shadow-xl shadow-indigo-500/30 transition transform hover:-translate-y-0.5 text-center">
                                Start Logging Free &rarr;
                            </a>
                            <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-4 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 font-bold text-base rounded-2xl transition text-center">
                                Existing Member Login
                            </a>
                        @endauth
                    </div>

                    {{-- Quick Badges --}}
                    <div class="mt-10 pt-8 border-t border-slate-900 flex items-center justify-center lg:justify-start gap-8 text-xs text-slate-400 font-medium">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>100% Free & Local SQLite</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Weekly Analytics</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Instant Setup</span>
                        </div>
                    </div>
                </div>

                {{-- Hero Visual Card Mockup --}}
                <div class="w-full max-w-lg relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur-xl opacity-30"></div>
                    
                    <div class="relative bg-slate-900/90 border border-slate-800/80 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
                        
                        <div class="flex items-center justify-between pb-6 border-b border-slate-800">
                            <div>
                                <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Weekly Dashboard Preview</div>
                                <div class="text-lg font-bold text-white mt-0.5">Current Week Stats</div>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-500/10 text-indigo-400 rounded-full border border-indigo-500/20">Live Sync</span>
                        </div>

                        {{-- Stats Preview Grid --}}
                        <div class="grid grid-cols-2 gap-4 my-6">
                            <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                                <div class="text-xs text-slate-400 font-semibold">Weekly Time</div>
                                <div class="text-2xl font-black text-white mt-1">4h 15m</div>
                                <div class="text-[11px] text-indigo-400 mt-1">🚴 3 Sessions</div>
                            </div>

                            <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                                <div class="text-xs text-slate-400 font-semibold">Total Distance</div>
                                <div class="text-2xl font-black text-emerald-400 mt-1">32.5 km</div>
                                <div class="text-[11px] text-emerald-400/80 mt-1">🏃 Treadmill & Bike</div>
                            </div>

                            <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                                <div class="text-xs text-slate-400 font-semibold">Calories Burned</div>
                                <div class="text-2xl font-black text-orange-400 mt-1">2,150 kcal</div>
                                <div class="text-[11px] text-orange-400/80 mt-1">🔥 Estimated</div>
                            </div>

                            <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                                <div class="text-xs text-slate-400 font-semibold">Workouts Logged</div>
                                <div class="text-2xl font-black text-purple-400 mt-1">6 Sessions</div>
                                <div class="text-[11px] text-purple-400/80 mt-1">🧘 Yoga & Rowing</div>
                            </div>
                        </div>

                        {{-- Sample Activity Item --}}
                        <div class="bg-slate-950/60 p-3.5 rounded-xl border border-slate-800/60 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🚴</span>
                                <div>
                                    <div class="text-xs font-bold text-white">Indoor Cycling</div>
                                    <div class="text-[10px] text-slate-400">Today, 08:30 AM &bull; 45 mins &bull; 18.2 km</div>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-emerald-400">450 kcal</span>
                        </div>

                    </div>
                </div>

            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-900 py-8 text-center text-xs text-slate-500">
                FitPulse &bull; Indoor Exercise Tracking & Analytics Built with Laravel & Tailwind CSS.
            </footer>

        </div>
    </body>
</html>
