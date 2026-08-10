<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span>{{ __('Indoor Exercise Dashboard') }}</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Track, log, and analyze your indoor training performance and weekly progress.
                </p>
            </div>
            {{-- Active Week Selector & Streak Badge --}}
            <div class="flex flex-wrap items-center gap-3">
                {{-- Streak Badge --}}
                <div class="px-3.5 py-2 rounded-xl bg-orange-500/10 border border-orange-500/25 text-orange-400 text-xs font-bold flex items-center gap-2 shadow-sm">
                    <span class="text-base animate-bounce">🔥</span>
                    <span>{{ $streakDays }} Day Streak</span>
                </div>

                {{-- Active Week Selector Dropdown --}}
                <form method="GET" action="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        </div>
                        <select name="week_offset" onchange="this.form.submit()" class="block pl-7 pr-8 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs font-bold text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm cursor-pointer transition duration-150">
                            @foreach($weekOptions as $offset => $label)
                                <option value="{{ $offset }}" {{ $selectedWeekOffset == $offset ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-emerald-950/80 border border-emerald-500/30 text-emerald-200 px-5 py-4 rounded-2xl flex items-center justify-between shadow-lg shadow-emerald-950/40 backdrop-blur-xl" role="alert">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Summary Cards Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6">
                
                {{-- Card 1: Total Active Time --}}
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition duration-200">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-xl group-hover:bg-indigo-500/20 transition-all pointer-events-none"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Active Time</p>
                            <h3 class="text-3xl font-black text-white mt-1">
                                @php
                                    $hours = floor($weeklyTimeMinutes / 60);
                                    $mins = $weeklyTimeMinutes % 60;
                                @endphp
                                @if($hours > 0)
                                    {{ $hours }}<span class="text-base font-normal text-slate-400">h</span> {{ $mins }}<span class="text-base font-normal text-slate-400">m</span>
                                @else
                                    {{ $mins }}<span class="text-base font-normal text-slate-400"> mins</span>
                                @endif
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-indigo-500/10 border border-indigo-500/20 rounded-2xl flex items-center justify-center text-indigo-400 shrink-0 shadow-lg shadow-indigo-500/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 10118 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-slate-400 relative z-10 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> Total time active for selected week
                    </div>
                </div>

                {{-- Card 2: Total Distance --}}
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition duration-200">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition-all pointer-events-none"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Distance Logged</p>
                            <h3 class="text-3xl font-black text-white mt-1">
                                {{ number_format($weeklyDistanceKm, 2) }} <span class="text-base font-normal text-slate-400">km</span>
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center justify-center text-emerald-400 shrink-0 shadow-lg shadow-emerald-500/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-slate-400 relative z-10 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Cycling & treadmill distance
                    </div>
                </div>

                {{-- Card 3: Total Calories --}}
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition duration-200">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-orange-500/10 rounded-full blur-xl group-hover:bg-orange-500/20 transition-all pointer-events-none"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Calories Burned</p>
                            <h3 class="text-3xl font-black text-white mt-1">
                                {{ number_format($weeklyCalories) }} <span class="text-base font-normal text-slate-400">kcal</span>
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-orange-500/10 border border-orange-500/20 rounded-2xl flex items-center justify-center text-orange-400 shrink-0 shadow-lg shadow-orange-500/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-slate-400 relative z-10 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Estimated active energy burned
                    </div>
                </div>

                {{-- Card 4: Total Sessions --}}
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition duration-200">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-purple-500/10 rounded-full blur-xl group-hover:bg-purple-500/20 transition-all pointer-events-none"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Workouts</p>
                            <h3 class="text-3xl font-black text-white mt-1">
                                {{ $weeklyCount }} <span class="text-base font-normal text-slate-400">sessions</span>
                            </h3>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/20 rounded-2xl flex items-center justify-center text-purple-400 shrink-0 shadow-lg shadow-purple-500/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 text-xs font-medium text-slate-400 relative z-10 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span> Completed indoor exercises
                    </div>
                </div>

                {{-- Card 5: Current Body Weight --}}
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 shadow-xl relative overflow-hidden group hover:border-slate-700 transition duration-200" x-data="{ openWeightModal: false }">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-cyan-500/10 rounded-full blur-xl group-hover:bg-cyan-500/20 transition-all pointer-events-none"></div>
                    <div class="flex items-center justify-between relative z-10">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Body Weight</p>
                            <h3 class="text-3xl font-black text-white mt-1">
                                {{ Auth::user()->weight_kg ? number_format(Auth::user()->weight_kg, 1) : '--' }} <span class="text-base font-normal text-slate-400">kg</span>
                            </h3>
                        </div>
                        <button @click="openWeightModal = true" class="w-12 h-12 bg-cyan-500/10 border border-cyan-500/20 rounded-2xl flex items-center justify-center text-cyan-400 shrink-0 shadow-lg shadow-cyan-500/10 hover:bg-cyan-500/20 cursor-pointer transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4 text-xs font-medium text-slate-400 relative z-10 flex items-center justify-between">
                        <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-cyan-400"></span> Track body weight progress</span>
                        <button @click="openWeightModal = true" class="text-[11px] font-bold text-cyan-400 hover:underline cursor-pointer">+ Log Weight</button>
                    </div>

                    {{-- Log Weight Modal --}}
                    <div x-show="openWeightModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
                        <div @click.away="openWeightModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                <h3 class="text-lg font-bold text-white">Log Body Weight</h3>
                                <button @click="openWeightModal = false" class="text-slate-400 hover:text-white text-lg">&times;</button>
                            </div>

                            <form action="{{ route('weight-logs.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Body Weight (kg)</label>
                                    <input type="number" step="0.1" min="1" max="500" name="weight_kg" value="{{ Auth::user()->weight_kg }}" placeholder="e.g. 72.5" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Date</label>
                                    <div class="relative flex items-center">
                                        <input id="weight_logged_date" type="text" name="logged_date" value="{{ date('d/m/Y') }}" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 pl-3 pr-10 cursor-pointer" required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-cyan-400">
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Notes (Optional)</label>
                                    <input type="text" name="notes" placeholder="e.g. Morning weigh-in" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" />
                                </div>
                                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                                    <button type="button" @click="openWeightModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-bold rounded-xl">Cancel</button>
                                    <button type="submit" class="px-5 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold rounded-xl cursor-pointer">Save Entry</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>



            {{-- Health & Fitness Metrics Card (BMI, BMR, TDEE, Ideal Weight) --}}
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 p-6 sm:p-7 shadow-2xl backdrop-blur-xl" x-data="{ openHealthModal: false }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-slate-800 gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                            <span class="text-emerald-400">⚕️</span> Automatic Health & Biometric Metrics
                        </h3>
                        <p class="text-xs text-slate-400">Calculated BMI, resting BMR, maintenance TDEE, and ideal weight benchmarks</p>
                    </div>
                    <button type="button" @click="openHealthModal = true" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-xs font-bold transition duration-150 flex items-center gap-1.5 cursor-pointer shadow-md shadow-emerald-600/20 self-start sm:self-auto">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Update Biometrics
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-6">
                    {{-- 1. BMI Card --}}
                    <div class="bg-slate-950/80 p-5 rounded-2xl border border-slate-800/80 flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Body Mass Index</span>
                            @php $bmiCat = Auth::user()->bmi_category; @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border {{ $bmiCat['class'] }}">
                                {{ $bmiCat['label'] }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-white">
                                {{ Auth::user()->bmi ? number_format(Auth::user()->bmi, 1) : '--' }}
                                <span class="text-xs font-normal text-slate-400">kg/m²</span>
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-1">Height: {{ Auth::user()->height_cm ? (Auth::user()->height_cm == round(Auth::user()->height_cm) ? number_format(Auth::user()->height_cm) : number_format(Auth::user()->height_cm, 1)).' cm' : 'Not set' }}</p>
                        </div>
                        {{-- Gauge Visual Indicator --}}
                        <div class="w-full bg-slate-900 h-2 rounded-full overflow-hidden flex border border-slate-800">
                            <div class="bg-blue-500 h-full w-[25%]" title="Underweight (<18.5)"></div>
                            <div class="bg-emerald-500 h-full w-[35%]" title="Normal (18.5-24.9)"></div>
                            <div class="bg-amber-500 h-full w-[25%]" title="Overweight (25-29.9)"></div>
                            <div class="bg-rose-500 h-full w-[15%]" title="Obese (>=30)"></div>
                        </div>
                    </div>

                    {{-- 2. BMR Card --}}
                    <div class="bg-slate-950/80 p-5 rounded-2xl border border-slate-800/80 flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Resting BMR</span>
                            <span class="w-7 h-7 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center text-xs font-bold">🔥</span>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-orange-400">
                                {{ Auth::user()->bmr ? number_format(Auth::user()->bmr) : '--' }}
                                <span class="text-xs font-normal text-slate-400">kcal/day</span>
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-1">Baseline calories burned at rest</p>
                        </div>
                        <div class="text-[10px] text-slate-500 flex items-center gap-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Mifflin-St Jeor standard
                        </div>
                    </div>

                    {{-- 3. Maintenance TDEE Card --}}
                    <div class="bg-slate-950/80 p-5 rounded-2xl border border-slate-800/80 flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Daily Maintenance (TDEE)</span>
                            <span class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-xs font-bold">⚡</span>
                        </div>
                        <div>
                            <h4 class="text-3xl font-black text-indigo-400">
                                {{ Auth::user()->tdee ? number_format(Auth::user()->tdee) : '--' }}
                                <span class="text-xs font-normal text-slate-400">kcal/day</span>
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-1">Activity: <span class="text-slate-200 font-semibold uppercase text-[10px]">{{ str_replace('_', ' ', Auth::user()->activity_level ?? 'moderately_active') }}</span></p>
                        </div>
                        <div class="text-[10px] text-slate-500 flex items-center gap-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> Calorie maintenance target
                        </div>
                    </div>

                    {{-- 4. Ideal Target Weight Card --}}
                    <div class="bg-slate-950/80 p-5 rounded-2xl border border-slate-800/80 flex flex-col justify-between space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Ideal Target Weight</span>
                            <span class="w-7 h-7 rounded-lg bg-teal-500/10 text-teal-400 flex items-center justify-center text-xs font-bold">🎯</span>
                        </div>
                        @php $ideal = Auth::user()->ideal_weight_range; @endphp
                        <div>
                            <h4 class="text-2xl font-black text-teal-400">
                                @if($ideal)
                                    {{ $ideal['min'] }} - {{ $ideal['max'] }} <span class="text-xs font-normal text-slate-400">kg</span>
                                @else
                                    -- <span class="text-xs font-normal text-slate-400">kg</span>
                                @endif
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-1">
                                Optimal weight: <span class="text-white font-bold">{{ $ideal ? $ideal['ideal'].' kg' : 'Set height first' }}</span>
                            </p>
                        </div>
                        <div class="text-[10px] text-slate-500 flex items-center gap-1 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Normal BMI range (18.5 - 24.9)
                        </div>
                    </div>
                </div>

                {{-- Update Health Profile Popup Modal --}}
                <div x-show="openHealthModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div @click.away="openHealthModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-5" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-emerald-400">⚕️</span> Update Health & Biometric Profile
                                </h3>
                                <p class="text-xs text-slate-400">Configure your parameters for accurate BMI & BMR</p>
                            </div>
                            <button type="button" @click="openHealthModal = false" class="text-slate-400 hover:text-white text-lg">&times;</button>
                        </div>

                        <form action="{{ route('health-profile.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Height (cm)</label>
                                    <input type="number" step="any" name="height_cm" value="{{ Auth::user()->height_cm }}" min="50" max="300" placeholder="e.g. 177.8" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Current Weight (kg)</label>
                                    <input type="number" step="any" name="weight_kg" value="{{ Auth::user()->weight_kg }}" min="1" max="500" placeholder="e.g. 72 or 72.5" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Age (Years)</label>
                                    <input type="number" step="1" pattern="\d*" name="age" value="{{ Auth::user()->age ?? 25 }}" min="1" max="120" placeholder="e.g. 28" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Gender</label>
                                    <select name="gender" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3">
                                        <option value="Male" {{ Auth::user()->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ Auth::user()->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ Auth::user()->gender === 'Other' ? 'selected' : '' }}>Other</option>
                                        <option value="Prefer not to say" {{ Auth::user()->gender === 'Prefer not to say' ? 'selected' : '' }}>Prefer not to say</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Daily Activity Level</label>
                                <select name="activity_level" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3">
                                    <option value="sedentary" {{ (Auth::user()->activity_level ?? 'moderately_active') === 'sedentary' ? 'selected' : '' }}>Sedentary (Little or no exercise)</option>
                                    <option value="lightly_active" {{ (Auth::user()->activity_level ?? 'moderately_active') === 'lightly_active' ? 'selected' : '' }}>Lightly Active (1-3 days/week)</option>
                                    <option value="moderately_active" {{ (Auth::user()->activity_level ?? 'moderately_active') === 'moderately_active' ? 'selected' : '' }}>Moderately Active (3-5 days/week)</option>
                                    <option value="very_active" {{ (Auth::user()->activity_level ?? 'moderately_active') === 'very_active' ? 'selected' : '' }}>Very Active (6-7 days/week)</option>
                                    <option value="extra_active" {{ (Auth::user()->activity_level ?? 'moderately_active') === 'extra_active' ? 'selected' : '' }}>Extra Active (Hard physical job/training)</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                                <button type="button" @click="openHealthModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-bold rounded-xl">Cancel</button>
                                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl cursor-pointer">Save Biometrics</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Weekly Goals Progress Section & Trophy Room --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start" x-data="{ openGoalsModal: false }">
                
                {{-- Weekly Fitness Goals Progress Bars (7 Cols) --}}
                <div class="lg:col-span-7 bg-slate-900/90 rounded-3xl border border-slate-800 p-6 sm:p-7 shadow-2xl backdrop-blur-xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                                <span class="text-indigo-400">🎯</span> Weekly Fitness Goals
                            </h3>
                            <p class="text-xs text-slate-400">Track your progress toward target fitness benchmarks</p>
                        </div>
                        <button @click="openGoalsModal = true" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition duration-150 flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            Edit Goals
                        </button>
                    </div>

                    <div class="space-y-5">
                        {{-- Goal 1: Active Duration --}}
                        @php
                            $minutesGoal = Auth::user()->weekly_minutes_goal ?? 150;
                            $minutesPercent = min(100, round(($weeklyTimeMinutes / $minutesGoal) * 100));
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-300 uppercase tracking-wider flex items-center gap-1.5">⏱️ Active Time</span>
                                <span class="text-indigo-400">{{ $weeklyTimeMinutes }} / {{ $minutesGoal }} mins <span class="text-slate-500 font-normal">({{ $minutesPercent }}%)</span></span>
                            </div>
                            <div class="w-full bg-slate-950 h-3 rounded-full overflow-hidden border border-slate-800">
                                <div class="bg-gradient-to-r from-indigo-600 to-indigo-400 h-full rounded-full transition-all duration-500" style="width: {{ $minutesPercent }}%"></div>
                            </div>
                        </div>

                        {{-- Goal 2: Energy Output --}}
                        @php
                            $caloriesGoal = Auth::user()->weekly_calories_goal ?? 2000;
                            $caloriesPercent = min(100, round(($weeklyCalories / $caloriesGoal) * 100));
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-300 uppercase tracking-wider flex items-center gap-1.5">🔥 Energy Burned</span>
                                <span class="text-orange-400">{{ number_format($weeklyCalories) }} / {{ number_format($caloriesGoal) }} kcal <span class="text-slate-500 font-normal">({{ $caloriesPercent }}%)</span></span>
                            </div>
                            <div class="w-full bg-slate-950 h-3 rounded-full overflow-hidden border border-slate-800">
                                <div class="bg-gradient-to-r from-orange-600 to-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $caloriesPercent }}%"></div>
                            </div>
                        </div>

                        {{-- Goal 3: Workouts Count --}}
                        @php
                            $workoutsGoal = Auth::user()->weekly_workouts_goal ?? 4;
                            $workoutsPercent = min(100, round(($weeklyCount / $workoutsGoal) * 100));
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                                <span class="text-slate-300 uppercase tracking-wider flex items-center gap-1.5">🏋️ Workout Sessions</span>
                                <span class="text-purple-400">{{ $weeklyCount }} / {{ $workoutsGoal }} sessions <span class="text-slate-500 font-normal">({{ $workoutsPercent }}%)</span></span>
                            </div>
                            <div class="w-full bg-slate-950 h-3 rounded-full overflow-hidden border border-slate-800">
                                <div class="bg-gradient-to-r from-purple-600 to-pink-500 h-full rounded-full transition-all duration-500" style="width: {{ $workoutsPercent }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Goals Edit Modal --}}
                    <div x-show="openGoalsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
                        <div @click.away="openGoalsModal = false" class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                <h3 class="text-lg font-bold text-white">Edit Weekly Goals</h3>
                                <button @click="openGoalsModal = false" class="text-slate-400 hover:text-white text-lg">&times;</button>
                            </div>

                            <form action="{{ route('goals.update') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Weekly Active Minutes Goal</label>
                                    <input type="number" name="weekly_minutes_goal" value="{{ Auth::user()->weekly_minutes_goal ?? 150 }}" min="10" max="10000" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Weekly Calories Goal (kcal)</label>
                                    <input type="number" name="weekly_calories_goal" value="{{ Auth::user()->weekly_calories_goal ?? 2000 }}" min="100" max="100000" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1">Weekly Sessions Goal</label>
                                    <input type="number" name="weekly_workouts_goal" value="{{ Auth::user()->weekly_workouts_goal ?? 4 }}" min="1" max="100" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 text-sm py-2.5 px-3" required />
                                </div>
                                <div class="flex justify-end gap-3 pt-3 border-t border-slate-800">
                                    <button type="button" @click="openGoalsModal = false" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-bold rounded-xl">Cancel</button>
                                    <button type="submit" class="px-5 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl cursor-pointer">Save Goals</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Personal Records / Trophy Room Badges (5 Cols) --}}
                <div class="lg:col-span-5 bg-slate-900/90 rounded-3xl border border-slate-800 p-6 sm:p-7 shadow-2xl backdrop-blur-xl">
                    <div class="mb-6 pb-4 border-b border-slate-800">
                        <h3 class="text-lg font-bold text-white tracking-tight flex items-center gap-2">
                            <span class="text-amber-400">🏆</span> Personal Records
                        </h3>
                        <p class="text-xs text-slate-400">All-time peak fitness milestones</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-950/80 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                            <span class="text-2xl">🏋️</span>
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400">Max Weight</span>
                                <p class="text-sm font-black text-white">{{ number_format($personalRecords['maxWeight'], 1) }} <span class="text-[10px] font-normal text-slate-400">kg</span></p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                            <span class="text-2xl">🚴</span>
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400">Max Distance</span>
                                <p class="text-sm font-black text-white">{{ number_format($personalRecords['maxDistance'], 2) }} <span class="text-[10px] font-normal text-slate-400">km</span></p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                            <span class="text-2xl">🔥</span>
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400">Max Calories</span>
                                <p class="text-sm font-black text-orange-400">{{ number_format($personalRecords['maxCalories']) }} <span class="text-[10px] font-normal text-slate-400">kcal</span></p>
                            </div>
                        </div>

                        <div class="bg-slate-950/80 p-3.5 rounded-2xl border border-slate-800/80 flex items-center gap-3">
                            <span class="text-2xl">⏱️</span>
                            <div>
                                <span class="text-[10px] font-bold uppercase text-slate-400">Max Duration</span>
                                <p class="text-sm font-black text-indigo-400">{{ $personalRecords['maxDuration'] }} <span class="text-[10px] font-normal text-slate-400">mins</span></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start" x-data="workoutForm('{{ old('type', 'Indoor Cycling') }}', {{ old('duration_minutes', 30) }}, '{{ old('distance_km', '') }}', '{{ old('speed_kmh', '') }}', '{{ old('calories_burned', '') }}')">
                
                {{-- Form Section (5 Columns) --}}
                <div class="lg:col-span-5 bg-slate-900/90 rounded-3xl border border-slate-800 p-6 sm:p-7 shadow-2xl backdrop-blur-xl">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-800">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-indigo-500/25">
                            +
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-tight">Log Workout Session</h3>
                            <p class="text-xs text-slate-400">Add your latest indoor activity details</p>
                        </div>
                    </div>

                    <form action="{{ route('workouts.store') }}" method="POST" class="space-y-4">
                        @csrf

                        {{-- Exercise Type --}}
                        <div>
                            <label for="type" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Exercise Type</label>
                            <select id="type" name="type" x-model="type" @change="onTypeChange()" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" required>
                                <option value="Indoor Cycling">🚴 Indoor Cycling</option>
                                <option value="Treadmill">🏃 Treadmill</option>
                                <option value="Heavyweight Training">🏋️ Heavyweight Training</option>
                                <option value="Jump Rope">🪢 Jump Rope</option>
                                <option value="Yoga">🧘 Yoga</option>
                                <option value="Other">⚡ Other</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-1" />
                        </div>

                        {{-- Duration & Date (2 cols) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="duration_minutes" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Duration (mins)</label>
                                <input id="duration_minutes" type="number" name="duration_minutes" x-model="duration" @input="recalculate()" min="1" max="1440" placeholder="e.g. 30" required class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                <x-input-error :messages="$errors->get('duration_minutes')" class="mt-1" />
                            </div>

                            <div>
                                <label for="workout_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Date & Time</label>
                                <div class="relative flex items-center">
                                    <input id="workout_date" type="text" name="workout_date" value="{{ old('workout_date', now()->format('d/m/Y H:i')) }}" required class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 pl-3.5 pr-10 transition duration-150 cursor-pointer" />
                                    <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-indigo-400">
                                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('workout_date')" class="mt-1" />
                            </div>
                        </div>

                        {{-- Conditional Cardio Fields: Indoor Cycling & Treadmill --}}
                        <template x-if="isCardio">
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="distance_km" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Distance (km)</label>
                                        <input id="distance_km" type="number" step="0.01" min="0" max="999.99" name="distance_km" x-model="distance" @input="onDistanceInput()" placeholder="e.g. 12.5" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                        <x-input-error :messages="$errors->get('distance_km')" class="mt-1" />
                                    </div>

                                    <div>
                                        <label for="speed_kmh" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Speed (km/h)</label>
                                        <input id="speed_kmh" type="number" step="0.1" min="0" max="200" name="speed_kmh" x-model="speed" @input="onSpeedInput()" placeholder="e.g. 25.0" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                        <x-input-error :messages="$errors->get('speed_kmh')" class="mt-1" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Conditional Heavyweight Training Fields --}}
                        <template x-if="type === 'Heavyweight Training'">
                            <div class="space-y-4">
                                <div>
                                    <label for="weight_kg" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Heavyweight (kg)</label>
                                    <input id="weight_kg" type="number" step="0.5" min="0" max="1000" name="weight_kg" value="{{ old('weight_kg') }}" placeholder="e.g. 80.0" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                    <x-input-error :messages="$errors->get('weight_kg')" class="mt-1" />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="sets" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Sets</label>
                                        <input id="sets" type="number" min="1" max="500" name="sets" value="{{ old('sets', 4) }}" placeholder="e.g. 4" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                        <x-input-error :messages="$errors->get('sets')" class="mt-1" />
                                    </div>

                                    <div>
                                        <label for="reps" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Reps per set</label>
                                        <input id="reps" type="number" min="1" max="5000" name="reps" value="{{ old('reps', 12) }}" placeholder="e.g. 12" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                        <x-input-error :messages="$errors->get('reps')" class="mt-1" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Conditional Jump Rope Fields --}}
                        <template x-if="type === 'Jump Rope'">
                            <div>
                                <label for="jumps_count" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Total Jumps</label>
                                <input id="jumps_count" type="number" min="1" max="50000" name="jumps_count" value="{{ old('jumps_count') }}" placeholder="e.g. 1200" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                <x-input-error :messages="$errors->get('jumps_count')" class="mt-1" />
                            </div>
                        </template>

                        {{-- Calories Input (Manual Entry) --}}
                        <div>
                            <label for="calories_burned" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Calories (kcal)</label>
                            <input id="calories_burned" type="number" min="0" max="20000" name="calories_burned" value="{{ old('calories_burned') }}" placeholder="e.g. 100" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                            <x-input-error :messages="$errors->get('calories_burned')" class="mt-1" />
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Notes (optional)</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Heart rate, focus areas, how you felt..." class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-indigo-600 via-indigo-500 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/25 transform hover:-translate-y-0.5 transition duration-150 flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Save Workout Session
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Recent Workouts Table Section (7 Columns) --}}
                <div class="lg:col-span-7 bg-slate-900/90 rounded-3xl border border-slate-800 p-6 sm:p-7 shadow-2xl backdrop-blur-xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-tight">Recent Workouts</h3>
                            <p class="text-xs text-slate-400">Your logged activity history and details</p>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 bg-slate-800 text-slate-300 rounded-full border border-slate-700">
                            {{ $workouts->total() }} Total Logged
                        </span>
                    </div>

                    @if($workouts->isEmpty())
                        <div class="text-center py-14 px-4 border-2 border-dashed border-slate-800 rounded-2xl bg-slate-950/40">
                            <div class="w-16 h-16 bg-slate-900 border border-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-white">No workouts logged yet</h4>
                            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed">Fill out the form on the left to start recording your indoor cycling, treadmill, heavyweight training, or yoga sessions!</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                        <th class="pb-3 pr-3">Activity</th>
                                        <th class="pb-3 px-3">Date & Time</th>
                                        <th class="pb-3 px-3">Duration</th>
                                        <th class="pb-3 px-3">Distance</th>
                                        <th class="pb-3 px-3">Calories</th>
                                        <th class="pb-3 pl-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/60 text-sm">
                                    @foreach($workouts as $workout)
                                        <tr class="hover:bg-slate-800/40 transition duration-150">
                                            
                                            {{-- Activity Badge & Details --}}
                                            <td class="py-4 pr-3">
                                                @php
                                                    $badgeColors = [
                                                        'Indoor Cycling' => 'bg-blue-500/10 text-blue-300 border-blue-500/30',
                                                        'Treadmill' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/30',
                                                        'Heavyweight Training' => 'bg-rose-500/10 text-rose-300 border-rose-500/30',
                                                        'Jump Rope' => 'bg-amber-500/10 text-amber-300 border-amber-500/30',
                                                        'Yoga' => 'bg-purple-500/10 text-purple-300 border-purple-500/30',
                                                        'Other' => 'bg-slate-800 text-slate-300 border-slate-700',
                                                    ];
                                                    $badgeClass = $badgeColors[$workout->type] ?? $badgeColors['Other'];
                                                @endphp
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $badgeClass }}">
                                                    @if($workout->type === 'Indoor Cycling') 🚴
                                                    @elseif($workout->type === 'Treadmill') 🏃
                                                    @elseif($workout->type === 'Heavyweight Training') 🏋️
                                                    @elseif($workout->type === 'Jump Rope') 🪢
                                                    @elseif($workout->type === 'Yoga') 🧘
                                                    @else ⚡
                                                    @endif
                                                    {{ $workout->type }}
                                                </span>

                                                {{-- Specific Workout Details --}}
                                                @if($workout->type === 'Heavyweight Training' && ($workout->weight_kg || $workout->sets))
                                                    <p class="text-xs text-slate-300 font-semibold mt-1">
                                                        @if($workout->weight_kg) {{ number_format($workout->weight_kg, 1) }} kg @endif
                                                        @if($workout->sets && $workout->reps) • {{ $workout->sets }} sets × {{ $workout->reps }} reps @endif
                                                    </p>
                                                @elseif($workout->type === 'Jump Rope' && $workout->jumps_count)
                                                    <p class="text-xs text-slate-300 font-semibold mt-1">
                                                        {{ number_format($workout->jumps_count) }} jumps
                                                    </p>
                                                @elseif(in_array($workout->type, ['Indoor Cycling', 'Treadmill']) && $workout->speed_kmh)
                                                    <p class="text-xs text-slate-400 mt-1">
                                                        Speed: <span class="text-slate-200 font-semibold">{{ number_format($workout->speed_kmh, 1) }} km/h</span>
                                                    </p>
                                                @endif

                                                @if($workout->notes)
                                                    <p class="text-xs text-slate-400 mt-0.5 line-clamp-1 italic max-w-xs" title="{{ $workout->notes }}">
                                                        "{{ $workout->notes }}"
                                                    </p>
                                                @endif
                                            </td>

                                            {{-- Date --}}
                                            <td class="py-4 px-3 whitespace-nowrap text-xs text-slate-300">
                                                <div class="font-bold text-white">{{ $workout->workout_date->setTimezone('Asia/Dhaka')->format('d/m/Y') }}</div>
                                                <div class="text-[11px] text-slate-400">{{ $workout->workout_date->setTimezone('Asia/Dhaka')->format('h:i A') }}</div>
                                            </td>

                                            {{-- Duration --}}
                                            <td class="py-4 px-3 whitespace-nowrap font-bold text-white">
                                                {{ $workout->duration_minutes }} <span class="text-xs font-normal text-slate-400">min</span>
                                            </td>

                                            {{-- Distance (Cycling / Treadmill only) --}}
                                            <td class="py-4 px-3 whitespace-nowrap text-slate-300">
                                                @if(in_array($workout->type, ['Indoor Cycling', 'Treadmill']) && $workout->distance_km)
                                                    <span class="font-bold text-white">{{ number_format($workout->distance_km, 2) }}</span> <span class="text-xs text-slate-400">km</span>
                                                @else
                                                    <span class="text-slate-600 text-xs">—</span>
                                                @endif
                                            </td>

                                            {{-- Calories --}}
                                            <td class="py-4 px-3 whitespace-nowrap text-slate-300">
                                                @if($workout->calories_burned)
                                                    <span class="font-bold text-orange-400">{{ number_format($workout->calories_burned) }}</span> <span class="text-xs text-slate-400">kcal</span>
                                                @else
                                                    <span class="text-slate-600 text-xs">—</span>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="py-4 pl-3 text-right whitespace-nowrap">
                                                <div class="flex items-center justify-end gap-1.5">
                                                    <a href="{{ route('workouts.edit', $workout) }}" class="p-2 text-slate-400 hover:text-indigo-400 transition-colors rounded-lg hover:bg-slate-800" title="Edit Workout">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('workouts.destroy', $workout) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this workout?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 transition-colors rounded-lg hover:bg-red-950/40 cursor-pointer" title="Delete Workout">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-5 pt-4 border-t border-slate-800">
                            {{ $workouts->links() }}
                        </div>
                    @endif
                </div>

            </div>


    </div>

    {{-- Interactive Form Alpine Component Script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('workoutForm', (initialType, initialDuration, initialDistance, initialSpeed, initialCalories) => ({
                type: initialType || 'Indoor Cycling',
                duration: initialDuration || 30,
                distance: initialDistance || '',
                speed: initialSpeed || '',
                calories: initialCalories || '',

                get isCardio() {
                    return ['Indoor Cycling', 'Treadmill'].includes(this.type);
                },

                onTypeChange() {
                    if (!this.isCardio) {
                        this.distance = '';
                        this.speed = '';
                    }
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (typeof flatpickr !== 'undefined') {
                flatpickr('#workout_date', {
                    enableTime: true,
                    dateFormat: 'd/m/Y H:i',
                    altInput: true,
                    altFormat: 'd/m/Y h:i K',
                    time_24hr: false,
                    closeOnSelect: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        instance.close();
                    }
                });

                flatpickr('#weight_logged_date', {
                    dateFormat: 'd/m/Y',
                    altInput: true,
                    altFormat: 'd/m/Y',
                    closeOnSelect: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        instance.close();
                    }
                });
            }
        });
    </script>
</x-app-layout>
