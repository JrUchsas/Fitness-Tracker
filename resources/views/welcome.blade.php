<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fitness Tracker - Modern Indoor Fitness & Exercise Tracker</title>

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
    <body class="h-full bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white">
        <div class="relative min-h-screen bg-slate-950 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-950/40 via-slate-950 to-slate-950 overflow-hidden flex flex-col justify-between">
            
            {{-- Background ambient lights --}}
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px] pointer-events-none transform-gpu"></div>
            <div class="absolute top-1/3 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px] pointer-events-none transform-gpu"></div>
            <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-pink-600/15 rounded-full blur-[120px] pointer-events-none transform-gpu"></div>

            {{-- Top Navbar --}}
            <header class="relative z-20 border-b border-slate-800/80 backdrop-blur-xl bg-slate-950/60">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                    {{-- Logo --}}
                    <a href="/" class="flex items-center gap-3 group">
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-white tracking-tight leading-none">Fitness <span class="text-indigo-400">Tracker</span></span>
                            <span class="text-[10px] font-semibold tracking-widest text-slate-400 uppercase">Indoor Tracker</span>
                        </div>
                    </a>

                    {{-- Actions --}}
                    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ route('dashboard') }}" class="whitespace-nowrap px-4 sm:px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-500/25 transition duration-150">
                                    Dashboard &rarr;
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="whitespace-nowrap px-3 sm:px-4 py-2 rounded-xl text-slate-300 hover:text-white font-semibold text-xs sm:text-sm transition duration-150">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="whitespace-nowrap px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-500/25 transition duration-150 transform hover:-translate-y-0.5">
                                        Get Started Free
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </header>

            {{-- Main Hero Section --}}
            <main class="relative z-10 my-auto py-12 lg:py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    
                    {{-- Hero Content Header --}}
                    <div class="text-center max-w-3xl mx-auto space-y-6">
                        
                        {{-- Tagline pill --}}
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-900/90 border border-slate-800 text-indigo-300 text-xs font-semibold uppercase tracking-widest shadow-xl">
                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                            Precision Indoor Fitness & Exercise Analytics
                        </div>

                        {{-- Main Heading --}}
                        <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-none">
                            Master your <span class="bg-gradient-to-r from-indigo-400 via-purple-300 to-pink-400 bg-clip-text text-transparent">indoor workouts</span> with confidence.
                        </h1>

                        {{-- Subtitle --}}
                        <p class="text-base sm:text-lg text-slate-400 max-w-2xl mx-auto leading-relaxed">
                            Log indoor cycling sessions, treadmill runs, rowing splits, and yoga practices. Gain actionable insights on weekly active minutes, distance, and energy output.
                        </p>

                        {{-- Primary Buttons --}}
                        <div class="pt-2 flex flex-row items-center justify-center gap-3 sm:gap-4 flex-nowrap">
                            @auth
                                <a href="{{ route('dashboard') }}" class="whitespace-nowrap px-6 sm:px-8 py-3.5 sm:py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-black text-sm sm:text-base rounded-2xl shadow-xl shadow-indigo-500/30 transform hover:-translate-y-0.5 transition duration-150">
                                    Open Your Dashboard &rarr;
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="whitespace-nowrap px-5 sm:px-8 py-3.5 sm:py-4 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-black text-sm sm:text-base rounded-2xl shadow-xl shadow-indigo-500/30 transform hover:-translate-y-0.5 transition duration-150">
                                    Get Started Free &rarr;
                                </a>
                                <a href="{{ route('login') }}" class="whitespace-nowrap px-5 sm:px-8 py-3.5 sm:py-4 bg-slate-900/90 border border-slate-800 hover:border-slate-700 text-slate-200 hover:text-white font-bold text-sm sm:text-base rounded-2xl transition duration-150">
                                    Log in
                                </a>
                            @endauth
                        </div>

                    </div>

                    {{-- Features Grid --}}
                    <div class="mt-24">
                        <div class="text-center max-w-2xl mx-auto mb-12">
                            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">Everything you need for indoor training</h2>
                            <p class="text-slate-400 text-sm mt-3">Designed with focus, speed, and privacy at its core.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- Feature 1 --}}
                            <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition duration-200">
                                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center mb-4 text-xl">
                                    🚴
                                </div>
                                <h3 class="text-lg font-bold text-white">Indoor Cycling</h3>
                                <p class="text-slate-400 text-sm mt-2 leading-relaxed">Log indoor spin sessions, stationary bike distance, duration, and calories to measure cardiovascular growth.</p>
                            </div>

                            {{-- Feature 2 --}}
                            <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition duration-200">
                                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-4 text-xl">
                                    🏃
                                </div>
                                <h3 class="text-lg font-bold text-white">Treadmill Runs</h3>
                                <p class="text-slate-400 text-sm mt-2 leading-relaxed">Record indoor runs, interval sets, distance covered in kilometers, and active workout timestamps.</p>
                            </div>

                            {{-- Feature 3 --}}
                            <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition duration-200">
                                <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center mb-4 text-xl">
                                    🏋️
                                </div>
                                <h3 class="text-lg font-bold text-white">Heavyweight Training</h3>
                                <p class="text-slate-400 text-sm mt-2 leading-relaxed">Log weight lifted in kg, total sets, reps per set, and progressive strength gains.</p>
                            </div>

                            {{-- Feature 4 --}}
                            <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition duration-200">
                                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center mb-4 text-xl">
                                    🧘
                                </div>
                                <h3 class="text-lg font-bold text-white">Yoga & Flexibility</h3>
                                <p class="text-slate-400 text-sm mt-2 leading-relaxed">Keep track of active recovery, yoga practice duration, mindfulness sessions, and personal notes.</p>
                            </div>

                            {{-- Feature 5 --}}
                            <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition duration-200">
                                <div class="w-12 h-12 rounded-xl bg-pink-500/10 border border-pink-500/20 text-pink-400 flex items-center justify-center mb-4 text-xl">
                                    📊
                                </div>
                                <h3 class="text-lg font-bold text-white">Weekly Analytics</h3>
                                <p class="text-slate-400 text-sm mt-2 leading-relaxed">Automatic calculation of weekly active minutes, distance metrics, total calories, and workout frequency.</p>
                            </div>

                            {{-- Feature 6 --}}
                            <div class="bg-slate-900/80 border border-slate-800 p-6 rounded-2xl hover:border-slate-700 transition duration-200">
                                <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-4 text-xl">
                                    🔒
                                </div>
                                <h3 class="text-lg font-bold text-white">Private & Secure</h3>
                                <p class="text-slate-400 text-sm mt-2 leading-relaxed">Your data remains safe and private. Powered by lightweight SQLite database storage and Laravel 12.</p>
                            </div>

                        </div>
                    </div>

                </div>
            </main>

            {{-- Footer --}}
            <footer class="relative z-10 border-t border-slate-800/80 py-8 bg-slate-950">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-300">Fitness Tracker</span> &copy; {{ date('Y') }} Indoor Fitness & Exercise Tracker. Built with Laravel 12 & Tailwind CSS.
                    </div>
                    <div class="flex items-center gap-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="hover:text-slate-300 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-slate-300 transition-colors">Login</a>
                            <a href="{{ route('register') }}" class="hover:text-slate-300 transition-colors">Register</a>
                        @endauth
                    </div>
                </div>
            </footer>

        </div>
    </body>
</html>
