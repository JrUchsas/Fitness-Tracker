<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WeightLogController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [WorkoutController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::post('/workouts', [WorkoutController::class, 'store'])->name('workouts.store');
    Route::get('/workouts/{workout}/edit', [WorkoutController::class, 'edit'])->name('workouts.edit');
    Route::put('/workouts/{workout}', [WorkoutController::class, 'update'])->name('workouts.update');
    Route::delete('/workouts/{workout}', [WorkoutController::class, 'destroy'])->name('workouts.destroy');
    Route::post('/goals', [WorkoutController::class, 'updateGoals'])->name('goals.update');
    Route::post('/weight-logs', [WeightLogController::class, 'store'])->name('weight-logs.store');
    Route::delete('/weight-logs/{weightLog}', [WeightLogController::class, 'destroy'])->name('weight-logs.destroy');

    Route::get('/export-db', function () {
        $dbPath = file_exists('/var/data/database.sqlite')
            ? '/var/data/database.sqlite'
            : database_path('database.sqlite');

        if (! file_exists($dbPath)) {
            abort(404, 'Database file not found.');
        }

        return response()->download($dbPath, 'database.sqlite');
    })->name('export-db');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
