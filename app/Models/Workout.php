<?php

namespace App\Models;

use Database\Factories\WorkoutFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Workout extends Model
{
    /** @use HasFactory<WorkoutFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'duration_minutes',
        'distance_km',
        'speed_kmh',
        'weight_kg',
        'sets',
        'reps',
        'jumps_count',
        'calories_burned',
        'workout_date',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'workout_date' => 'datetime',
            'duration_minutes' => 'integer',
            'calories_burned' => 'integer',
            'distance_km' => 'decimal:2',
            'speed_kmh' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'sets' => 'integer',
            'reps' => 'integer',
            'jumps_count' => 'integer',
        ];
    }

    /**
     * Get the user that owns the workout.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
