<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-white tracking-tight leading-tight flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="p-2 text-slate-400 hover:text-white hover:bg-slate-900 rounded-xl transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <span>{{ __('Edit Workout Session') }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/90 rounded-3xl border border-slate-800 p-6 sm:p-10 shadow-2xl backdrop-blur-xl" x-data="workoutEditForm('{{ old('type', $workout->type) }}', {{ old('duration_minutes', $workout->duration_minutes) }}, '{{ old('distance_km', $workout->distance_km) }}', '{{ old('speed_kmh', $workout->speed_kmh) }}', '{{ old('calories_burned', $workout->calories_burned) }}')">
                
                <form action="{{ route('workouts.update', $workout) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

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

                    {{-- Duration & Date --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="duration_minutes" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Duration (minutes)</label>
                            <input id="duration_minutes" type="number" name="duration_minutes" x-model="duration" @input="recalculate()" min="1" max="1440" placeholder="e.g. 30" required class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                            <x-input-error :messages="$errors->get('duration_minutes')" class="mt-1" />
                        </div>

                        <div>
                            <label for="workout_date" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Date & Time</label>
                            <div class="relative flex items-center">
                                <input id="workout_date" type="text" name="workout_date" value="{{ old('workout_date', $workout->workout_date->setTimezone('Asia/Dhaka')->format('d/m/Y H:i')) }}" required class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 pl-3.5 pr-10 transition duration-150 cursor-pointer" />
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-indigo-400">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('workout_date')" class="mt-1" />
                        </div>
                    </div>

                    {{-- Conditional Cardio Fields --}}
                    <template x-if="isCardio">
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                        <div class="space-y-6">
                            <div>
                                <label for="weight_kg" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Heavyweight (kg)</label>
                                <input id="weight_kg" type="number" step="0.5" min="0" max="1000" name="weight_kg" value="{{ old('weight_kg', $workout->weight_kg) }}" placeholder="e.g. 80.0" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                <x-input-error :messages="$errors->get('weight_kg')" class="mt-1" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="sets" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Sets</label>
                                    <input id="sets" type="number" min="1" max="500" name="sets" value="{{ old('sets', $workout->sets) }}" placeholder="e.g. 4" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                    <x-input-error :messages="$errors->get('sets')" class="mt-1" />
                                </div>

                                <div>
                                    <label for="reps" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Reps per set</label>
                                    <input id="reps" type="number" min="1" max="5000" name="reps" value="{{ old('reps', $workout->reps) }}" placeholder="e.g. 12" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                                    <x-input-error :messages="$errors->get('reps')" class="mt-1" />
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Conditional Jump Rope Fields --}}
                    <template x-if="type === 'Jump Rope'">
                        <div>
                            <label for="jumps_count" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Total Jumps</label>
                            <input id="jumps_count" type="number" min="1" max="50000" name="jumps_count" value="{{ old('jumps_count', $workout->jumps_count) }}" placeholder="e.g. 1200" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                            <x-input-error :messages="$errors->get('jumps_count')" class="mt-1" />
                        </div>
                    </template>

                    {{-- Calories Input (Manual Entry) --}}
                    <div>
                        <label for="calories_burned" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Calories (kcal)</label>
                        <input id="calories_burned" type="number" min="0" max="20000" name="calories_burned" value="{{ old('calories_burned', $workout->calories_burned) }}" placeholder="e.g. 100" class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150" />
                        <x-input-error :messages="$errors->get('calories_burned')" class="mt-1" />
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">Notes (optional)</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Heart rate, split times, how you felt..." class="block w-full rounded-xl border border-slate-800 bg-slate-950/80 text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm py-3 px-3.5 transition duration-150">{{ old('notes', $workout->notes) }}</textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-4 border-t border-slate-800">
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-sm rounded-xl transition duration-150">
                            Cancel
                        </a>
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-sm rounded-xl shadow-lg shadow-indigo-500/25 transition duration-150 cursor-pointer">
                            Update Workout Session
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Interactive Form Alpine Component Script --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('workoutEditForm', (initialType, initialDuration, initialDistance, initialSpeed, initialCalories) => ({
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
            }
        });
    </script>
</x-app-layout>
