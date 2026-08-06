use App\Models\User;
use App\Models\Workout;

test('unauthenticated users are redirected from dashboard and workout routes', function () {
    $this->get('/dashboard')->assertRedirect('/login');
    $this->post('/workouts', [])->assertRedirect('/login');
});

test('authenticated user can view dashboard with workouts and summary stats', function () {
    $user = User::factory()->create();

    $workout = Workout::factory()->create([
        'user_id' => $user->id,
        'type' => 'Indoor Cycling',
        'duration_minutes' => 45,
        'distance_km' => 15.5,
        'calories_burned' => 350,
        'workout_date' => now(),
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Indoor Cycling');
    $response->assertSee('45');
    $response->assertSee('15.50');
});

test('user can log a new workout session', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/workouts', [
        'type' => 'Treadmill',
        'duration_minutes' => 30,
        'distance_km' => 4.5,
        'calories_burned' => 280,
        'workout_date' => now()->format('Y-m-d H:i:s'),
        'notes' => 'Great morning run',
    ]);

    $response->assertRedirect('/dashboard');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('workouts', [
        'user_id' => $user->id,
        'type' => 'Treadmill',
        'duration_minutes' => 30,
        'distance_km' => 4.5,
        'calories_burned' => 280,
        'notes' => 'Great morning run',
    ]);
});

test('user can edit their own workout session', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create(['user_id' => $user->id, 'type' => 'Yoga']);

    $editResponse = $this->actingAs($user)->get("/workouts/{$workout->id}/edit");
    $editResponse->assertStatus(200);
    $editResponse->assertSee('Yoga');

    $updateResponse = $this->actingAs($user)->put("/workouts/{$workout->id}", [
        'type' => 'Heavyweight Training',
        'duration_minutes' => 45,
        'weight_kg' => 85.0,
        'sets' => 4,
        'reps' => 12,
        'calories_burned' => 320,
        'workout_date' => now()->format('Y-m-d H:i:s'),
        'notes' => 'Heavy squat session',
    ]);

    $updateResponse->assertRedirect('/dashboard');
    $this->assertDatabaseHas('workouts', [
        'id' => $workout->id,
        'type' => 'Heavyweight Training',
        'duration_minutes' => 45,
        'weight_kg' => 85.0,
        'sets' => 4,
        'reps' => 12,
    ]);
});

test('user can delete their own workout session', function () {
    $user = User::factory()->create();
    $workout = Workout::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)->delete("/workouts/{$workout->id}");

    $response->assertRedirect('/dashboard');
    $this->assertDatabaseMissing('workouts', ['id' => $workout->id]);
});

test('user cannot edit or delete another users workout session', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $workout = Workout::factory()->create(['user_id' => $user1->id]);

    $this->actingAs($user2)->get("/workouts/{$workout->id}/edit")->assertStatus(403);

    $this->actingAs($user2)->put("/workouts/{$workout->id}", [
        'type' => 'Yoga',
        'duration_minutes' => 30,
        'workout_date' => now()->format('Y-m-d H:i:s'),
    ])->assertStatus(403);

    $this->actingAs($user2)->delete("/workouts/{$workout->id}")->assertStatus(403);

    $this->assertDatabaseHas('workouts', ['id' => $workout->id]);
});

test('workout store validation rules prevent invalid entries', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/workouts', [
        'type' => 'InvalidType',
        'duration_minutes' => -10,
    ]);

    $response->assertSessionHasErrors(['type', 'duration_minutes', 'workout_date']);
});

test('recent workouts are ordered by workout_date ascending', function () {
    $user = User::factory()->create();

    $workoutLater = Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->addDays(2),
        'notes' => 'Later Workout',
    ]);

    $workoutEarlier = Workout::factory()->create([
        'user_id' => $user->id,
        'workout_date' => now()->subDays(2),
        'notes' => 'Earlier Workout',
    ]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
    $response->assertSeeInOrder(['Earlier Workout', 'Later Workout']);
});

test('user can select an active week offset to view weekly summary stats', function () {
    $user = User::factory()->create();

    Workout::factory()->create([
        'user_id' => $user->id,
        'duration_minutes' => 90,
        'distance_km' => 20.0,
        'calories_burned' => 500,
        'workout_date' => now()->subDays(7),
    ]);

    $response = $this->actingAs($user)->get('/dashboard?week_offset=-1');

    $response->assertStatus(200);
    $response->assertSee('90');
    $response->assertSee('20.00');
test('user can log a workout with manual calories and custom metrics', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/workouts', [
        'type' => 'Indoor Cycling',
        'duration_minutes' => 60,
        'distance_km' => 20.0,
        'calories_burned' => 450,
        'workout_date' => now()->format('Y-m-d H:i:s'),
    ]);

    $response->assertRedirect('/dashboard');

    $this->assertDatabaseHas('workouts', [
        'user_id' => $user->id,
        'type' => 'Indoor Cycling',
        'duration_minutes' => 60,
        'distance_km' => 20.0,
        'calories_burned' => 450,
    ]);
});

test('user can update weekly fitness goals', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/goals', [
        'weekly_minutes_goal' => 300,
        'weekly_calories_goal' => 3500,
        'weekly_workouts_goal' => 5,
    ]);

    $response->assertRedirect('/dashboard');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'weekly_minutes_goal' => 300,
        'weekly_calories_goal' => 3500,
        'weekly_workouts_goal' => 5,
    ]);
});




