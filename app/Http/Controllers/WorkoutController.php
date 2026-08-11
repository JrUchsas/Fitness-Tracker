<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Models\User;
use App\Models\Workout;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkoutController extends Controller
{
    /**
     * Display the fitness dashboard with summary and recent workouts.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $selectedWeekOffset = (int) $request->input('week_offset', 0);

        $baseStartOfWeek = now()->startOfWeek();
        $startOfWeek = (clone $baseStartOfWeek)->addWeeks($selectedWeekOffset)->startOfDay();
        $endOfWeek = (clone $startOfWeek)->endOfWeek()->endOfDay();

        // Build week options for dropdown selector (current week up to 8 weeks back)
        $weekOptions = [];
        for ($i = 0; $i >= -8; $i--) {
            $start = (clone $baseStartOfWeek)->addWeeks($i);
            $end = (clone $start)->endOfWeek();

            if ($i === 0) {
                $label = 'Current Week ('.$start->format('d M').' - '.$end->format('d M Y').')';
            } elseif ($i === -1) {
                $label = 'Last Week ('.$start->format('d M').' - '.$end->format('d M Y').')';
            } else {
                $label = abs($i).' Weeks Ago ('.$start->format('d M').' - '.$end->format('d M Y').')';
            }
            $weekOptions[$i] = $label;
        }

        $workouts = $user->workouts()
            ->orderBy('workout_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(10)
            ->withQueryString();

        $weeklyWorkouts = $user->workouts()
            ->whereBetween('workout_date', [$startOfWeek, $endOfWeek])
            ->get();

        $weeklyTimeMinutes = $weeklyWorkouts->sum('duration_minutes');
        $weeklyDistanceKm = (float) $weeklyWorkouts->sum('distance_km');
        $weeklyCalories = $weeklyWorkouts->sum('calories_burned');
        $weeklyCount = $weeklyWorkouts->count();

        // 1. Personal Records (PR Badges)
        $personalRecords = [
            'maxDuration' => $user->workouts()->max('duration_minutes') ?: 0,
            'maxCalories' => $user->workouts()->max('calories_burned') ?: 0,
            'maxDistance' => (float) ($user->workouts()->whereIn('type', ['Indoor Cycling', 'Treadmill'])->max('distance_km') ?: 0),
            'maxWeight' => (float) ($user->workouts()->where('type', 'Heavyweight Training')->max('weight_kg') ?: 0),
            'maxJumps' => $user->workouts()->where('type', 'Jump Rope')->max('jumps_count') ?: 0,
        ];

        // 2. Active Workout Streak
        $streakDays = $this->calculateStreak($user);

        return view('dashboard', [
            'workouts' => $workouts,
            'weeklyTimeMinutes' => $weeklyTimeMinutes,
            'weeklyDistanceKm' => $weeklyDistanceKm,
            'weeklyCalories' => $weeklyCalories,
            'weeklyCount' => $weeklyCount,
            'selectedWeekOffset' => $selectedWeekOffset,
            'weekOptions' => $weekOptions,
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek,
            'personalRecords' => $personalRecords,
            'streakDays' => $streakDays,
        ]);
    }

    /**
     * Store a newly created workout in storage.
     */
    public function store(StoreWorkoutRequest $request): RedirectResponse
    {
        $data = $this->processWorkoutData($request->validated(), $request->user());
        $request->user()->workouts()->create($data);

        return redirect()->route('dashboard')->with('success', 'Workout logged successfully!');
    }

    /**
     * Show the form for editing the specified workout.
     */
    public function edit(Request $request, Workout $workout): View
    {
        if ($workout->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('workouts.edit', [
            'workout' => $workout,
        ]);
    }

    /**
     * Update the specified workout in storage.
     */
    public function update(UpdateWorkoutRequest $request, Workout $workout): RedirectResponse
    {
        $data = $this->processWorkoutData($request->validated(), $request->user());
        $workout->update($data);

        return redirect()->route('dashboard')->with('success', 'Workout updated successfully!');
    }

    /**
     * Remove the specified workout from storage.
     */
    public function destroy(Request $request, Workout $workout): RedirectResponse
    {
        if ($workout->user_id !== $request->user()->id) {
            abort(403);
        }

        $workout->delete();

        return redirect()->route('dashboard')->with('success', 'Workout deleted successfully!');
    }

    /**
     * Process workout data, auto-calculating speed/distance and predicting calories for cycling & treadmill using MET standards.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function processWorkoutData(array $data, ?User $user = null): array
    {
        $type = $data['type'] ?? 'Other';

        if (! in_array($type, ['Indoor Cycling', 'Treadmill'])) {
            $data['distance_km'] = null;
            $data['speed_kmh'] = null;
        }

        if ($type !== 'Heavyweight Training') {
            $data['weight_kg'] = null;
            $data['sets'] = null;
            $data['reps'] = null;
        }

        if ($type !== 'Jump Rope') {
            $data['jumps_count'] = null;
        }

        if (isset($data['calories_burned']) && $data['calories_burned'] !== '') {
            $data['calories_burned'] = (int) $data['calories_burned'];
        } else {
            $data['calories_burned'] = null;
        }

        return $data;
    }

    /**
     * Update user's weekly fitness goals.
     */
    public function updateGoals(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weekly_minutes_goal' => ['required', 'integer', 'min:10', 'max:10000'],
            'weekly_calories_goal' => ['required', 'integer', 'min:100', 'max:100000'],
            'weekly_workouts_goal' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $request->user()->update($validated);

        return redirect()->route('dashboard')->with('success', 'Weekly fitness goals updated successfully!');
    }

    /**
     * Update user's physical health biometrics (height, weight, age, gender, activity level).
     */
    public function updateHealthProfile(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'height_cm' => ['nullable', 'numeric', 'min:50', 'max:300'],
            'weight_kg' => ['nullable', 'numeric', 'min:1', 'max:500'],
            'age' => ['nullable', 'integer', 'min:1', 'max:120'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other,Prefer not to say'],
            'activity_level' => ['nullable', 'string', 'in:sedentary,lightly_active,moderately_active,very_active,extra_active'],
        ]);

        $user = $request->user();
        $user->update($validated);

        if (isset($validated['weight_kg']) && $validated['weight_kg']) {
            $user->weightLogs()->create([
                'weight_kg' => $validated['weight_kg'],
                'logged_date' => now()->setTimezone('Asia/Dhaka')->format('Y-m-d'),
                'notes' => 'Updated via Health Profile',
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Health & Biometric profile updated successfully!');
    }

    /**
     * Calculate consecutive workout active days streak.
     */
    private function calculateStreak(User $user): int
    {
        $dates = $user->workouts()
            ->pluck('workout_date')
            ->map(fn ($d) => Carbon::parse($d)->format('Y-m-d'))
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        if (empty($dates)) {
            return 0;
        }

        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        $latestDate = $dates[0];
        if ($latestDate !== $today && $latestDate !== $yesterday) {
            return 0;
        }

        $streak = 1;
        $currentDate = Carbon::parse($latestDate)->startOfDay();

        for ($i = 1; $i < count($dates); $i++) {
            $prevDate = Carbon::parse($dates[$i])->startOfDay();
            $diff = (int) $currentDate->diffInDays($prevDate);

            if ($diff === 1) {
                $streak++;
                $currentDate = $prevDate;
            } else {
                break;
            }
        }

        return $streak;
    }
}
