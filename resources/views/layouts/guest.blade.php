<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ trim(preg_replace('/^APP_NAME\s*=\s*/i', '', config('app.name', 'Fitness Tracker')), '"\' ') ?: 'Fitness Tracker' }} - Indoor Exercise Tracker</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-slate-950 text-gray-100 antialiased selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col lg:flex-row">
            
            {{-- Left Column: Hero Branding --}}
            <div class="lg:w-1/2 relative bg-gradient-to-br from-indigo-950 via-slate-900 to-purple-950 p-8 lg:p-16 flex flex-col justify-between overflow-hidden border-b lg:border-b-0 lg:border-r border-indigo-900/30">
                
                {{-- Decorative background glow --}}
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl pointer-events-none"></div>

                {{-- Header / Brand --}}
                <div class="relative z-10">
                    <a href="/" class="inline-flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight">Fitness <span class="text-indigo-400">Tracker</span></span>
                    </a>
                </div>

                {{-- Hero Copy --}}
                <div class="relative z-10 my-12 lg:my-0 max-w-lg">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-wider mb-6">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        Indoor Exercise Tracking
                    </div>
                    <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight tracking-tight">
                        Log every watt, rep, and breath.
                    </h1>
                    <p class="mt-4 text-slate-400 text-base leading-relaxed">
                        Track indoor cycling, treadmill runs, heavyweight training, yoga sessions, and calorie burn with beautiful analytics designed for peak performance.
                    </p>

                    {{-- Activity Badges Preview --}}
                    <div class="mt-8 flex flex-wrap gap-2.5">
                        <span class="px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs font-medium text-slate-300 flex items-center gap-1.5">🚴 Indoor Cycling</span>
                        <span class="px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs font-medium text-slate-300 flex items-center gap-1.5">🏃 Treadmill</span>
                        <span class="px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs font-medium text-slate-300 flex items-center gap-1.5">🏋️ Heavyweight</span>
                        <span class="px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs font-medium text-slate-300 flex items-center gap-1.5">🧘 Yoga</span>
                    </div>
                </div>

                {{-- Footer badge --}}
                <div class="relative z-10 text-xs text-slate-500">
                    &copy; {{ date('Y') }} Fitness Tracker. Precision Indoor Fitness Analytics.
                </div>
            </div>

            {{-- Right Column: Form Container --}}
            <div class="lg:w-1/2 p-6 sm:p-12 lg:p-16 flex items-center justify-center bg-slate-950 relative">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>

