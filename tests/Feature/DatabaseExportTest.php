<?php

use App\Models\User;
use App\Models\WeightLog;
use App\Models\Workout;

test('unauthenticated users cannot download database backup', function () {
    $this->get('/export-db')->assertRedirect('/login');
});

test('authenticated user can download database backup as sqlite', function () {
    $user = User::factory()->create();
    Workout::factory()->create(['user_id' => $user->id]);
    WeightLog::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->get('/export-db');

    $response->assertOk();
    $response->assertHeader('content-disposition', 'attachment; filename=database.sqlite');
});
