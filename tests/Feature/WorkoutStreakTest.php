<?php

use App\Models\User;
use App\Models\Workout;

test('streak calculates correctly for consecutive days regardless of workout time', function () {
    $user = User::factory()->create();

    // Log workout yesterday evening
    Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->subDay()->setTime(20, 0, 0),
    ]);

    // Log workout today morning (14 hours difference, consecutive calendar days)
    Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->setTime(8, 0, 0),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('2 Day Streak');
});

test('streak handles multiple workouts on the same day correctly', function () {
    $user = User::factory()->create();

    // Log 2 workouts yesterday
    Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->subDay()->setTime(9, 0, 0),
    ]);
    Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->subDay()->setTime(17, 0, 0),
    ]);

    // Log 1 workout today
    Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->setTime(10, 0, 0),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('2 Day Streak');
});

test('streak returns 0 if last workout was prior to yesterday', function () {
    $user = User::factory()->create();

    // Workout 3 days ago
    Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->subDays(3)->setTime(10, 0, 0),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');
    $response->assertStatus(200);
    $response->assertSee('0 Day Streak');
});
