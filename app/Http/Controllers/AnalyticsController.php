<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display analytics and graphical charts for workout statistics.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Available day range options
        $dayOptions = [
            7 => 'Last 7 Days',
            14 => 'Last 14 Days',
            30 => 'Last 30 Days (1 Month)',
            60 => 'Last 60 Days (2 Months)',
            90 => 'Last 90 Days (3 Months)',
        ];

        $selectedDays = (int) $request->query('days', 14);
        if (! array_key_exists($selectedDays, $dayOptions)) {
            $selectedDays = 14;
        }

        // 1. Daily Trends for Selected Days Range
        $startDate = now()->subDays($selectedDays - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $recentWorkouts = $user->workouts()
            ->whereBetween('workout_date', [$startDate, $endDate])
            ->get();

        $dailyLabels = [];
        $dailyCalories = [];
        $dailyDistance = [];
        $dailyDuration = [];

        for ($i = $selectedDays - 1; $i >= 0; $i--) {
            $date = now()->setTimezone('Asia/Dhaka')->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $dailyLabels[] = $date->format('d/m/Y');

            $dayWorkouts = $recentWorkouts->filter(function (Workout $w) use ($dateStr) {
                return $w->workout_date->setTimezone('Asia/Dhaka')->format('Y-m-d') === $dateStr;
            });

            $dailyCalories[] = (int) $dayWorkouts->sum('calories_burned');
            $dailyDistance[] = (float) round($dayWorkouts->sum('distance_km'), 2);
            $dailyDuration[] = (int) $dayWorkouts->sum('duration_minutes');
        }

        // 2. Activity Type Breakdown
        $allWorkouts = $user->workouts()->get();

        $typeBreakdown = $allWorkouts->groupBy('type')->map(function ($items, $type) {
            return [
                'type' => $type,
                'count' => $items->count(),
                'total_minutes' => $items->sum('duration_minutes'),
                'total_calories' => $items->sum('calories_burned'),
            ];
        })->values();

        // 3. Summary Performance Metrics
        $totalWorkouts = $allWorkouts->count();
        $totalCalories = $allWorkouts->sum('calories_burned');
        $totalDistance = (float) $allWorkouts->sum('distance_km');
        $totalMinutes = $allWorkouts->sum('duration_minutes');

        $totalHeavyweightVolume = $allWorkouts->where('type', 'Heavyweight Training')->sum(function (Workout $w) {
            return ($w->weight_kg ?? 0) * ($w->sets ?? 1) * ($w->reps ?? 1);
        });

        $totalJumps = $allWorkouts->where('type', 'Jump Rope')->sum('jumps_count');

        // 4. Monthly vs Previous Month Growth Calculations
        $currentMonthStart = now()->startOfMonth();
        $currentMonthEnd = now()->endOfMonth();

        $prevMonthStart = now()->subMonth()->startOfMonth();
        $prevMonthEnd = now()->subMonth()->endOfMonth();

        $currentMonthWorkouts = $allWorkouts->filter(function (Workout $w) use ($currentMonthStart, $currentMonthEnd) {
            return $w->workout_date >= $currentMonthStart && $w->workout_date <= $currentMonthEnd;
        });

        $prevMonthWorkouts = $allWorkouts->filter(function (Workout $w) use ($prevMonthStart, $prevMonthEnd) {
            return $w->workout_date >= $prevMonthStart && $w->workout_date <= $prevMonthEnd;
        });

        $calcGrowth = function ($curr, $prev) {
            if ($prev == 0) {
                return $curr > 0 ? 100 : 0;
            }

            return (int) round((($curr - $prev) / $prev) * 100);
        };

        $monthlyGrowth = [
            'minutesCurrent' => $currentMonthWorkouts->sum('duration_minutes'),
            'minutesGrowth' => $calcGrowth($currentMonthWorkouts->sum('duration_minutes'), $prevMonthWorkouts->sum('duration_minutes')),

            'caloriesCurrent' => $currentMonthWorkouts->sum('calories_burned'),
            'caloriesGrowth' => $calcGrowth($currentMonthWorkouts->sum('calories_burned'), $prevMonthWorkouts->sum('calories_burned')),

            'distanceCurrent' => (float) $currentMonthWorkouts->sum('distance_km'),
            'distanceGrowth' => $calcGrowth($currentMonthWorkouts->sum('distance_km'), $prevMonthWorkouts->sum('distance_km')),

            'sessionsCurrent' => $currentMonthWorkouts->count(),
            'sessionsGrowth' => $calcGrowth($currentMonthWorkouts->count(), $prevMonthWorkouts->count()),
        ];

        // 5. Body Weight Progression Logs
        $weightLogs = $user->weightLogs()->where('logged_date', '>=', $startDate->format('Y-m-d'))->get();
        $weightLabels = [];
        $weightData = [];

        foreach ($weightLogs as $log) {
            $weightLabels[] = $log->logged_date->format('d/m/Y');
            $weightData[] = (float) $log->weight_kg;
        }

        $allWeightLogs = $user->weightLogs()->orderBy('logged_date', 'asc')->orderBy('id', 'asc')->get();
        $latestWeight = $allWeightLogs->last()?->weight_kg ?? $user->weight_kg;
        $initialWeight = $allWeightLogs->first()?->weight_kg;
        $weightChange = ($initialWeight && $latestWeight) ? round($latestWeight - $initialWeight, 1) : 0;

        return view('analytics', [
            'dailyLabels' => $dailyLabels,
            'dailyCalories' => $dailyCalories,
            'dailyDistance' => $dailyDistance,
            'dailyDuration' => $dailyDuration,
            'typeBreakdown' => $typeBreakdown,
            'totalWorkouts' => $totalWorkouts,
            'totalCalories' => $totalCalories,
            'totalDistance' => $totalDistance,
            'totalMinutes' => $totalMinutes,
            'totalHeavyweightVolume' => $totalHeavyweightVolume,
            'totalJumps' => $totalJumps,
            'selectedDays' => $selectedDays,
            'dayOptions' => $dayOptions,
            'monthlyGrowth' => $monthlyGrowth,
            'weightLabels' => $weightLabels,
            'weightData' => $weightData,
            'latestWeight' => $latestWeight,
            'weightChange' => $weightChange,
        ]);
    }
}
