<?php

use App\Models\User;
use App\Models\WeightLog;

test('authenticated user can log body weight entry', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/weight-logs', [
        'weight_kg' => 74.5,
        'logged_date' => now()->format('Y-m-d'),
        'notes' => 'Morning weigh-in',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $this->assertDatabaseHas('weight_logs', [
        'user_id' => $user->id,
        'weight_kg' => 74.5,
        'notes' => 'Morning weigh-in',
    ]);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'weight_kg' => 74.5,
    ]);
});

test('user can delete their own weight log entry', function () {
    $user = User::factory()->create();
    $weightLog = WeightLog::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete("/weight-logs/{$weightLog->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('weight_logs', ['id' => $weightLog->id]);
});
