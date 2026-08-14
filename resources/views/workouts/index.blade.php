<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-white leading-tight tracking-tight flex items-center gap-2">
                    <span class="text-indigo-400">📋</span> Workout History & Logged Sessions
                </h2>
                <p class="text-xs text-slate-400 mt-1">Complete historical record of all your recorded sessions, notes, and activity benchmarks</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition duration-150 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Metric Highlights Bar --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Sessions</span>
                        <span class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-sm font-bold">🏋️</span>
                    </div>
                    <h4 class="text-2xl sm:text-3xl font-black text-white mt-2">{{ number_format($totalCount) }}</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">All-time recorded workouts</p>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Active Time</span>
                        <span class="w-8 h-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center text-sm font-bold">⏱️</span>
                    </div>
                    <h4 class="text-2xl sm:text-3xl font-black text-purple-400 mt-2">{{ $totalHours }} <span class="text-xs font-normal text-slate-400">hrs</span></h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">{{ number_format($totalMinutes) }} total minutes</p>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Energy Burned</span>
                        <span class="w-8 h-8 rounded-lg bg-orange-500/10 text-orange-400 flex items-center justify-center text-sm font-bold">🔥</span>
                    </div>
                    <h4 class="text-2xl sm:text-3xl font-black text-orange-400 mt-2">{{ number_format($totalCalories) }} <span class="text-xs font-normal text-slate-400">kcal</span></h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Total energy output</p>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-xl">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Distance</span>
                        <span class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-sm font-bold">🚴</span>
                    </div>
                    <h4 class="text-2xl sm:text-3xl font-black text-emerald-400 mt-2">{{ number_format($totalDistance, 2) }} <span class="text-xs font-normal text-slate-400">km</span></h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Cycling & treadmill logs</p>
                </div>
            </div>

            {{-- Filters & Search Section --}}
            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-5 sm:p-6 shadow-xl">
                <form action="{{ route('workouts.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between gap-4">
                    
                    {{-- Activity Filter Tabs / Select --}}
                    <div class="flex flex-wrap items-center gap-2 w-full md:w-auto">
                        @php
                            $activities = [
                                'All' => '⚡ All',
                                'Indoor Cycling' => '🚴 Cycling',
                                'Treadmill' => '🏃 Treadmill',
                                'Heavyweight Training' => '🏋️ Heavyweight',
                                'Jump Rope' => '🪢 Jump Rope',
                                'Yoga' => '🧘 Yoga',
                                'Other' => '✨ Other',
                            ];
                        @endphp
                        @foreach($activities as $actKey => $actLabel)
                            <a href="{{ route('workouts.index', array_merge(request()->query(), ['type' => $actKey === 'All' ? null : $actKey, 'page' => null])) }}"
                               class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition duration-150 {{ ($typeFilter == $actKey || (!$typeFilter && $actKey === 'All')) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-slate-950/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-slate-800' }}">
                                {{ $actLabel }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Search Input --}}
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        @if($typeFilter)
                            <input type="hidden" name="type" value="{{ $typeFilter }}">
                        @endif
                        <div class="relative flex-1 md:w-64">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search notes or activity..." class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 text-xs py-2.5 pl-9 pr-3 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition duration-150" />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                        <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition duration-150 cursor-pointer shrink-0">
                            Search
                        </button>
                        @if($search || $typeFilter)
                            <a href="{{ route('workouts.index') }}" class="px-3 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition duration-150 shrink-0" title="Reset Filters">
                                ✕
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Workouts List / Table Card --}}
            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-slate-800">
                    <div>
                        <h3 class="text-lg font-bold text-white tracking-tight">Logged Activity Sessions</h3>
                        <p class="text-xs text-slate-400">Showing {{ $workouts->firstItem() ?? 0 }} - {{ $workouts->lastItem() ?? 0 }} of {{ $workouts->total() }} total entries</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 bg-slate-800 text-indigo-300 rounded-full border border-slate-700">
                        {{ $workouts->total() }} Workouts
                    </span>
                </div>

                @if($workouts->isEmpty())
                    <div class="text-center py-16 px-4 border-2 border-dashed border-slate-800 rounded-2xl bg-slate-950/40">
                        <div class="w-16 h-16 bg-slate-900 border border-slate-800 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-white">No matching workouts found</h4>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto leading-relaxed">
                            @if($search || $typeFilter)
                                Try resetting your search filters or selecting another activity category.
                            @else
                                You have not logged any workout sessions yet.
                            @endif
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-xl shadow-lg transition duration-150">
                                + Log Workout Now
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="pb-3.5 pr-4">Activity & Metrics</th>
                                    <th class="pb-3.5 px-4">Date & Time</th>
                                    <th class="pb-3.5 px-4">Duration</th>
                                    <th class="pb-3.5 px-4">Distance</th>
                                    <th class="pb-3.5 px-4">Calories</th>
                                    <th class="pb-3.5 px-4">Session Notes</th>
                                    <th class="pb-3.5 pl-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm">
                                @foreach($workouts as $workout)
                                    <tr class="hover:bg-slate-800/40 transition duration-150">
                                        
                                        {{-- Activity Badge & Specific Stats --}}
                                        <td class="py-4 pr-4 align-top">
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

                                            {{-- Additional Specific workout stats --}}
                                            @if($workout->type === 'Heavyweight Training' && ($workout->weight_kg || $workout->sets))
                                                <p class="text-xs text-slate-300 font-semibold mt-1.5 flex items-center gap-1">
                                                    @if($workout->weight_kg) <span>{{ number_format($workout->weight_kg, 1) }} kg</span> @endif
                                                    @if($workout->sets && $workout->reps) <span class="text-slate-500">•</span> <span>{{ $workout->sets }} sets × {{ $workout->reps }} reps</span> @endif
                                                </p>
                                            @elseif($workout->type === 'Jump Rope' && $workout->jumps_count)
                                                <p class="text-xs text-slate-300 font-semibold mt-1.5">
                                                    {{ number_format($workout->jumps_count) }} total jumps
                                                </p>
                                            @elseif(in_array($workout->type, ['Indoor Cycling', 'Treadmill']) && $workout->speed_kmh)
                                                <p class="text-xs text-slate-400 mt-1.5">
                                                    Speed: <span class="text-slate-200 font-semibold">{{ number_format($workout->speed_kmh, 1) }} km/h</span>
                                                </p>
                                            @endif
                                        </td>

                                        {{-- Date & Time --}}
                                        <td class="py-4 px-4 align-top whitespace-nowrap text-xs">
                                            <div class="font-bold text-white">{{ $workout->workout_date->setTimezone('Asia/Dhaka')->format('d M, Y') }}</div>
                                            <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                                <span>⏱️</span> {{ $workout->workout_date->setTimezone('Asia/Dhaka')->format('h:i A') }}
                                            </div>
                                        </td>

                                        {{-- Duration --}}
                                        <td class="py-4 px-4 align-top whitespace-nowrap font-bold text-white">
                                            {{ $workout->duration_minutes }} <span class="text-xs font-normal text-slate-400">min</span>
                                        </td>

                                        {{-- Distance --}}
                                        <td class="py-4 px-4 align-top whitespace-nowrap text-slate-300">
                                            @if(in_array($workout->type, ['Indoor Cycling', 'Treadmill']) && $workout->distance_km)
                                                <span class="font-bold text-white">{{ number_format($workout->distance_km, 2) }}</span> <span class="text-xs text-slate-400">km</span>
                                            @else
                                                <span class="text-slate-600 text-xs">—</span>
                                            @endif
                                        </td>

                                        {{-- Calories --}}
                                        <td class="py-4 px-4 align-top whitespace-nowrap text-slate-300">
                                            @if($workout->calories_burned)
                                                <span class="font-bold text-orange-400">{{ number_format($workout->calories_burned) }}</span> <span class="text-xs text-slate-400">kcal</span>
                                            @else
                                                <span class="text-slate-600 text-xs">—</span>
                                            @endif
                                        </td>

                                        {{-- Session Notes (Full Detailed Display) --}}
                                        <td class="py-4 px-4 align-top min-w-[220px]">
                                            @if($workout->notes)
                                                <div class="bg-slate-950/80 border border-slate-800/80 rounded-xl p-3 text-xs text-slate-300 leading-relaxed">
                                                    <div class="flex items-start gap-1.5">
                                                        <span class="text-indigo-400 text-sm leading-none shrink-0">💬</span>
                                                        <p class="italic text-slate-300">{{ $workout->notes }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-slate-600 text-xs italic">No notes recorded</span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="py-4 pl-4 align-top text-right whitespace-nowrap">
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
                    @if($workouts->hasPages())
                        <div class="pt-4 border-t border-slate-800">
                            {{ $workouts->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
