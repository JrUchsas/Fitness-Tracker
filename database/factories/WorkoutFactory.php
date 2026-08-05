<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workout>
 */
class WorkoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Indoor Cycling', 'Treadmill', 'Heavyweight Training', 'Jump Rope', 'Yoga', 'Other'];
        $type = fake()->randomElement($types);

        $isCardio = in_array($type, ['Indoor Cycling', 'Treadmill']);
        $isWeights = $type === 'Heavyweight Training';
        $isRope = $type === 'Jump Rope';

        $duration = fake()->numberBetween(15, 90);
        $distance = $isCardio ? fake()->randomFloat(2, 2, 25) : null;
        $speed = $isCardio ? fake()->randomFloat(2, 8, 32) : null;

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'duration_minutes' => $duration,
            'distance_km' => $distance,
            'speed_kmh' => $speed,
            'weight_kg' => $isWeights ? fake()->randomFloat(2, 20, 140) : null,
            'sets' => $isWeights ? fake()->numberBetween(3, 6) : null,
            'reps' => $isWeights ? fake()->numberBetween(8, 15) : null,
            'jumps_count' => $isRope ? fake()->numberBetween(500, 3000) : null,
            'calories_burned' => fake()->numberBetween(100, 800),
            'workout_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
