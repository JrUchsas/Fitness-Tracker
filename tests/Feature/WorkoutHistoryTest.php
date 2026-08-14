<?php

use App\Models\User;
use App\Models\Workout;

test('unauthenticated users are redirected from workouts history route', function () {
    $response = $this->get('/workouts');

    $response->assertRedirect('/login');
});

test('authenticated user can view workouts history with all details and notes', function () {
    $user = User::factory()->create();

    $workout1 = Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Indoor Cycling',
        'duration_minutes' => 50,
        'distance_km' => 22.5,
        'calories_burned' => 450,
        'workout_date' => now()->subDay(),
        'notes' => 'Intense sprint intervals',
    ]);

    $workout2 = Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Heavyweight Training',
        'duration_minutes' => 60,
        'weight_kg' => 90.0,
        'sets' => 5,
        'reps' => 8,
        'calories_burned' => 380,
        'workout_date' => now(),
        'notes' => 'New bench press record',
    ]);

    $response = $this->actingAs($user)->get('/workouts');

    $response->assertStatus(200);
    $response->assertSee('Workout History');
    $response->assertSee('Indoor Cycling');
    $response->assertSee('Heavyweight Training');
    $response->assertSee('Intense sprint intervals');
    $response->assertSee('New bench press record');
    $response->assertSee('22.50');
    $response->assertSee('90.0 kg');
    $response->assertSee('5 sets × 8 reps');
});

test('user can filter workout history by exercise type', function () {
    $user = User::factory()->create();

    Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Treadmill',
        'notes' => 'Morning 5k Treadmill run',
    ]);

    Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Yoga',
        'notes' => 'Evening relaxation flow',
    ]);

    $response = $this->actingAs($user)->get('/workouts?type=Treadmill');

    $response->assertStatus(200);
    $response->assertSee('Morning 5k Treadmill run');
    $response->assertDontSee('Evening relaxation flow');
});

test('user can search workout history by note keyword', function () {
    $user = User::factory()->create();

    Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Indoor Cycling',
        'notes' => 'High cadence sprint session',
    ]);

    Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Indoor Cycling',
        'notes' => 'Easy recovery spin',
    ]);

    $response = $this->actingAs($user)->get('/workouts?search=cadence');

    $response->assertStatus(200);
    $response->assertSee('High cadence sprint session');
    $response->assertDontSee('Easy recovery spin');
});

test('workout history only shows current users logged workouts', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Workout::factory()->create([
        'user_id' => $user1->id,
        'notes' => 'User One Secret Workout',
    ]);

    Workout::factory()->create([
        'user_id' => $user2->id,
        'notes' => 'User Two Secret Workout',
    ]);

    $response = $this->actingAs($user1)->get('/workouts');

    $response->assertStatus(200);
    $response->assertSee('User One Secret Workout');
    $response->assertDontSee('User Two Secret Workout');
});
