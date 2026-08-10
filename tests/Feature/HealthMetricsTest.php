<?php

use App\Models\User;

test('calculates correct bmi and bmi category for user', function () {
    $user = User::factory()->create([
        'height_cm' => 175,
        'weight_kg' => 70.0,
    ]);

    expect($user->bmi)->toBe(22.9);
    expect($user->bmi_category['label'])->toBe('Normal Weight');

    $overweightUser = User::factory()->create([
        'height_cm' => 175,
        'weight_kg' => 85.0,
    ]);

    expect($overweightUser->bmi)->toBe(27.8);
    expect($overweightUser->bmi_category['label'])->toBe('Overweight');
});

test('calculates correct bmr and tdee based on gender and activity level', function () {
    $maleUser = User::factory()->create([
        'gender' => 'Male',
        'age' => 25,
        'height_cm' => 180,
        'weight_kg' => 75.0,
        'activity_level' => 'moderately_active',
    ]);

    // BMR male: (10 * 75) + (6.25 * 180) - (5 * 25) + 5 = 750 + 1125 - 125 + 5 = 1755
    expect($maleUser->bmr)->toBe(1755);
    // TDEE: 1755 * 1.55 = 2720
    expect($maleUser->tdee)->toBe(2720);

    $femaleUser = User::factory()->create([
        'gender' => 'Female',
        'age' => 30,
        'height_cm' => 165,
        'weight_kg' => 60.0,
        'activity_level' => 'sedentary',
    ]);

    // BMR female: (10 * 60) + (6.25 * 165) - (5 * 30) - 161 = 600 + 1031.25 - 150 - 161 = 1320
    expect($femaleUser->bmr)->toBe(1320);
    // TDEE: 1320 * 1.2 = 1584
    expect($femaleUser->tdee)->toBe(1584);
});

test('calculates correct ideal weight range', function () {
    $user = User::factory()->create([
        'height_cm' => 180,
    ]);

    $ideal = $user->ideal_weight_range;
    expect($ideal['min'])->toBe(59.9);
    expect($ideal['max'])->toBe(80.7);
    expect($ideal['ideal'])->toBe(70.5);
});

test('user can update health profile biometrics with decimal height via route', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/health-profile', [
        'height_cm' => 177.8,
        'weight_kg' => 74.5,
        'age' => 29,
        'gender' => 'Male',
        'activity_level' => 'very_active',
    ]);

    $response->assertRedirect('/dashboard');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'height_cm' => 177.8,
        'weight_kg' => 74.5,
        'age' => 29,
        'gender' => 'Male',
        'activity_level' => 'very_active',
    ]);

    $user->refresh();
    expect($user->bmi)->toBe(23.6);
});
