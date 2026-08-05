<?php

use App\Models\User;
use App\Models\Workout;

test('unauthenticated users are redirected from analytics page', function () {
    $this->get('/analytics')->assertRedirect('/login');
});

test('authenticated user can view analytics page with summary metrics', function () {
    $user = User::factory()->create();

    Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Indoor Cycling',
        'duration_minutes' => 60,
        'distance_km' => 25.0,
        'calories_burned' => 500,
        'workout_date' => now(),
    ]);

    Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Heavyweight Training',
        'duration_minutes' => 45,
        'distance_km' => null,
        'weight_kg' => 100.0,
        'sets' => 4,
        'reps' => 10,
        'calories_burned' => 300,
        'workout_date' => now(),
    ]);

    $response = $this->actingAs($user)->get('/analytics');

    $response->assertStatus(200);
    $response->assertSee('Fitness Analytics');
    $response->assertSee('800');
    $response->assertSee('25.00');
    $response->assertSee('4,000');
});

test('user can select different day ranges on analytics page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/analytics?days=30');

    $response->assertStatus(200);
    $response->assertSee('Last 30 Days (1 Month)');
});
