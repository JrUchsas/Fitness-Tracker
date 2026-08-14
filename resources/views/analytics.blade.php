<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-purple-500/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span>{{ __('Fitness Analytics & Graphical Stats') }}</span>
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Visual insights, body weight progression, calorie trends, and performance breakdown over time.
                </p>
            </div>
            {{-- Time Range Selector Dropdown --}}
            <form method="GET" action="{{ route('analytics') }}" class="inline-flex items-center gap-2">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-purple-400">
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                    </div>
                    <select name="days" onchange="this.form.submit()" class="block pl-7 pr-8 py-2.5 bg-slate-900 border border-slate-800 rounded-xl text-xs font-bold text-slate-200 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 shadow-sm cursor-pointer transition duration-150">
                        @foreach($dayOptions as $daysCount => $label)
                            <option value="{{ $daysCount }}" {{ $selectedDays == $daysCount ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Metric Highlight Badges Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Body Weight</span>
                    <h4 class="text-2xl font-black text-cyan-400 mt-1">{{ $latestWeight ? number_format($latestWeight, 1) : '--' }} <span class="text-xs font-normal text-slate-400">kg</span></h4>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Workouts</span>
                    <h4 class="text-2xl font-black text-white mt-1">{{ number_format($totalWorkouts) }} <span class="text-xs font-normal text-slate-400">sessions</span></h4>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Energy</span>
                    <h4 class="text-2xl font-black text-orange-400 mt-1">{{ number_format($totalCalories) }} <span class="text-xs font-normal text-slate-400">kcal</span></h4>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Distance</span>
                    <h4 class="text-2xl font-black text-emerald-400 mt-1">{{ number_format($totalDistance, 2) }} <span class="text-xs font-normal text-slate-400">km</span></h4>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Heavyweight Volume</span>
                    <h4 class="text-2xl font-black text-rose-400 mt-1">{{ number_format($totalHeavyweightVolume) }} <span class="text-xs font-normal text-slate-400">kg</span></h4>
                </div>

                <div class="bg-slate-900 p-5 rounded-2xl border border-slate-800 shadow-lg">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Jumps</span>
                    <h4 class="text-2xl font-black text-amber-400 mt-1">{{ number_format($totalJumps) }} <span class="text-xs font-normal text-slate-400">reps</span></h4>
                </div>
            </div>

            {{-- Main Charts Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                {{-- Chart 1: Daily Calorie Burned Trend (Bar Chart) --}}
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                                <span class="text-orange-400">🔥</span> Daily Calories Burned
                            </h3>
                            <p class="text-xs text-slate-400">Daily energy output in kcal (Last {{ $selectedDays }} Days)</p>
                        </div>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="caloriesChart"></canvas>
                    </div>
                </div>

                {{-- Chart 2: Daily Distance Covered Trend (Line Chart) --}}
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                                <span class="text-emerald-400">🚴</span> Daily Distance Logged (km)
                            </h3>
                            <p class="text-xs text-slate-400">Indoor cycling & treadmill distance trajectory</p>
                        </div>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="distanceChart"></canvas>
                    </div>
                </div>

                {{-- Chart 3: Exercise Type Distribution (Doughnut Chart) --}}
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                                <span class="text-purple-400">📊</span> Activity Distribution
                            </h3>
                            <p class="text-xs text-slate-400">Workout sessions grouped by exercise type</p>
                        </div>
                    </div>
                    <div class="relative h-72 w-full flex items-center justify-center">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>

                {{-- Chart 4: Daily Active Time (Bar Chart) --}}
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 shadow-2xl">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                                <span class="text-indigo-400">⏱️</span> Daily Active Duration (mins)
                            </h3>
                            <p class="text-xs text-slate-400">Total active minutes spent exercising each day</p>
                        </div>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="durationChart"></canvas>
                    </div>
                </div>

                {{-- Chart 5: Body Weight Progression Line Chart (Placed right above monthly comparison) --}}
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 shadow-2xl lg:col-span-2">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div>
                            <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                                <span class="text-cyan-400">⚖️</span> Body Weight Progression (kg)
                            </h3>
                            <p class="text-xs text-slate-400">Body weight logs trajectory over time</p>
                        </div>
                        <span class="text-xs font-bold text-slate-300">
                            Net Change: <span class="{{ $weightChange <= 0 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $weightChange >= 0 ? '+'.$weightChange : $weightChange }} kg</span>
                        </span>
                    </div>
                    <div class="relative h-72 w-full">
                        <canvas id="weightChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- Monthly Growth Comparison Grid (Placed at the bottom) --}}
            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 shadow-2xl">
                <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-800">
                    <div>
                        <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                            <span class="text-indigo-400">📈</span> Monthly Performance Comparison
                        </h3>
                        <p class="text-xs text-slate-400">Current calendar month vs. previous month growth</p>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700">
                        {{ date('F Y') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Active Time Growth --}}
                    <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                        <span class="text-[11px] font-bold uppercase text-slate-400">Active Time</span>
                        <p class="text-2xl font-black text-white mt-1">
                            @php
                                $mHrs = floor($monthlyGrowth['minutesCurrent'] / 60);
                                $mMns = $monthlyGrowth['minutesCurrent'] % 60;
                            @endphp
                            @if($mHrs > 0)
                                {{ $mHrs }}<span class="text-xs font-normal text-slate-400">h</span> {{ $mMns }}<span class="text-xs font-normal text-slate-400">m</span>
                            @else
                                {{ $mMns }}<span class="text-xs font-normal text-slate-400"> mins</span>
                            @endif
                        </p>
                        <span class="text-xs font-bold mt-1 inline-flex items-center gap-1 {{ $monthlyGrowth['minutesGrowth'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $monthlyGrowth['minutesGrowth'] >= 0 ? '▲ +'.$monthlyGrowth['minutesGrowth'] : '▼ '.$monthlyGrowth['minutesGrowth'] }}% vs last month
                        </span>
                    </div>

                    {{-- Calories Growth --}}
                    <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                        <span class="text-[11px] font-bold uppercase text-slate-400">Calories Burned</span>
                        <p class="text-2xl font-black text-orange-400 mt-1">{{ number_format($monthlyGrowth['caloriesCurrent']) }} <span class="text-xs font-normal text-slate-400">kcal</span></p>
                        <span class="text-xs font-bold mt-1 inline-flex items-center gap-1 {{ $monthlyGrowth['caloriesGrowth'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $monthlyGrowth['caloriesGrowth'] >= 0 ? '▲ +'.$monthlyGrowth['caloriesGrowth'] : '▼ '.$monthlyGrowth['caloriesGrowth'] }}% vs last month
                        </span>
                    </div>

                    {{-- Distance Growth --}}
                    <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                        <span class="text-[11px] font-bold uppercase text-slate-400">Distance Logged</span>
                        <p class="text-2xl font-black text-emerald-400 mt-1">{{ number_format($monthlyGrowth['distanceCurrent'], 2) }} <span class="text-xs font-normal text-slate-400">km</span></p>
                        <span class="text-xs font-bold mt-1 inline-flex items-center gap-1 {{ $monthlyGrowth['distanceGrowth'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $monthlyGrowth['distanceGrowth'] >= 0 ? '▲ +'.$monthlyGrowth['distanceGrowth'] : '▼ '.$monthlyGrowth['distanceGrowth'] }}% vs last month
                        </span>
                    </div>

                    {{-- Sessions Growth --}}
                    <div class="bg-slate-950/80 p-4 rounded-2xl border border-slate-800">
                        <span class="text-[11px] font-bold uppercase text-slate-400">Completed Sessions</span>
                        <p class="text-2xl font-black text-purple-400 mt-1">{{ $monthlyGrowth['sessionsCurrent'] }} <span class="text-xs font-normal text-slate-400">sessions</span></p>
                        <span class="text-xs font-bold mt-1 inline-flex items-center gap-1 {{ $monthlyGrowth['sessionsGrowth'] >= 0 ? 'text-emerald-400' : 'text-rose-400' }}">
                            {{ $monthlyGrowth['sessionsGrowth'] >= 0 ? '▲ +'.$monthlyGrowth['sessionsGrowth'] : '▼ '.$monthlyGrowth['sessionsGrowth'] }}% vs last month
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.font.family = "'Outfit', sans-serif";

            const labels = @json($dailyLabels);
            const caloriesData = @json($dailyCalories);
            const distanceData = @json($dailyDistance);
            const durationData = @json($dailyDuration);
            const typeBreakdown = @json($typeBreakdown);
            const weightLabels = @json($weightLabels);
            const weightData = @json($weightData);

            // Chart 1: Calories Bar Chart
            const ctxCalories = document.getElementById('caloriesChart').getContext('2d');
            new Chart(ctxCalories, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Calories (kcal)',
                        data: caloriesData,
                        backgroundColor: 'rgba(249, 115, 22, 0.75)',
                        borderColor: '#f97316',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        hoverBackgroundColor: '#fb923c'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(51, 65, 85, 0.4)' }, beginAtZero: true }
                    }
                }
            });

            // Chart 2: Distance Line Chart
            const ctxDistance = document.getElementById('distanceChart').getContext('2d');
            new Chart(ctxDistance, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Distance (km)',
                        data: distanceData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#34d399',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(51, 65, 85, 0.4)' }, beginAtZero: true }
                    }
                }
            });

            // Chart 3: Activity Distribution Doughnut Chart
            const ctxDistribution = document.getElementById('distributionChart').getContext('2d');
            const typeLabels = typeBreakdown.map(item => item.type);
            const typeCounts = typeBreakdown.map(item => item.count);
            const colorMap = {
                'Indoor Cycling': '#3b82f6',
                'Treadmill': '#10b981',
                'Heavyweight Training': '#f43f5e',
                'Jump Rope': '#f59e0b',
                'Yoga': '#a855f7',
                'Other': '#64748b'
            };
            const sliceColors = typeLabels.map(t => colorMap[t] || '#64748b');

            new Chart(ctxDistribution, {
                type: 'doughnut',
                data: {
                    labels: typeLabels,
                    datasets: [{
                        data: typeCounts,
                        backgroundColor: sliceColors,
                        borderWidth: 2,
                        borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { boxWidth: 14, padding: 16 }
                        }
                    }
                }
            });

            // Chart 4: Duration Bar Chart
            const ctxDuration = document.getElementById('durationChart').getContext('2d');
            new Chart(ctxDuration, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Duration (mins)',
                        data: durationData,
                        backgroundColor: 'rgba(99, 102, 241, 0.75)',
                        borderColor: '#6366f1',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        hoverBackgroundColor: '#818cf8'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(51, 65, 85, 0.4)' }, beginAtZero: true }
                    }
                }
            });

            // Chart 5: Weight Line Chart
            const ctxWeight = document.getElementById('weightChart').getContext('2d');
            new Chart(ctxWeight, {
                type: 'line',
                data: {
                    labels: weightLabels.length ? weightLabels : labels,
                    datasets: [{
                        label: 'Body Weight (kg)',
                        data: weightData.length ? weightData : [],
                        borderColor: '#06b6d4',
                        backgroundColor: 'rgba(6, 182, 212, 0.15)',
                        borderWidth: 3,
                        tension: 0.3,
                        fill: true,
                        pointBackgroundColor: '#22d3ee',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(51, 65, 85, 0.4)' } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
