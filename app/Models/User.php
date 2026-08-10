<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'age',
        'height_cm',
        'weight_kg',
        'activity_level',
        'weekly_minutes_goal',
        'weekly_calories_goal',
        'weekly_workouts_goal',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'age' => 'integer',
            'height_cm' => 'float',
            'weight_kg' => 'decimal:2',
            'weekly_minutes_goal' => 'integer',
            'weekly_calories_goal' => 'integer',
            'weekly_workouts_goal' => 'integer',
        ];
    }

    /**
     * Get the workouts for the user.
     *
     * @return HasMany<Workout, $this>
     */
    public function workouts(): HasMany
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * Get the weight logs for the user.
     *
     * @return HasMany<WeightLog, $this>
     */
    public function weightLogs(): HasMany
    {
        return $this->hasMany(WeightLog::class)->orderBy('logged_date', 'asc');
    }

    /**
     * Calculate Body Mass Index (BMI).
     */
    public function getBmiAttribute(): ?float
    {
        if (! $this->weight_kg || ! $this->height_cm || $this->height_cm <= 0) {
            return null;
        }

        $heightMeters = $this->height_cm / 100;

        return round((float) $this->weight_kg / ($heightMeters * $heightMeters), 1);
    }

    /**
     * Get BMI Category details.
     *
     * @return array{label: string, class: string, color: string}
     */
    public function getBmiCategoryAttribute(): array
    {
        $bmi = $this->bmi;

        if ($bmi === null) {
            return ['label' => 'Not Configured', 'class' => 'bg-slate-800 text-slate-400 border-slate-700', 'color' => 'slate'];
        }

        if ($bmi < 18.5) {
            return ['label' => 'Underweight', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/30', 'color' => 'blue'];
        }

        if ($bmi < 25.0) {
            return ['label' => 'Normal Weight', 'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30', 'color' => 'emerald'];
        }

        if ($bmi < 30.0) {
            return ['label' => 'Overweight', 'class' => 'bg-amber-500/10 text-amber-400 border-amber-500/30', 'color' => 'amber'];
        }

        return ['label' => 'Obese', 'class' => 'bg-rose-500/10 text-rose-400 border-rose-500/30', 'color' => 'rose'];
    }

    /**
     * Calculate Basal Metabolic Rate (BMR) using Mifflin-St Jeor Equation.
     */
    public function getBmrAttribute(): ?int
    {
        if (! $this->weight_kg || ! $this->height_cm || $this->height_cm <= 0) {
            return null;
        }

        $weight = (float) $this->weight_kg;
        $height = (float) $this->height_cm;
        $age = $this->age ?: 25;

        if (strtolower((string) $this->gender) === 'female') {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) - 161;
        } else {
            $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age) + 5;
        }

        return (int) round($bmr);
    }

    /**
     * Calculate Total Daily Energy Expenditure (TDEE).
     */
    public function getTdeeAttribute(): ?int
    {
        $bmr = $this->bmr;
        if ($bmr === null) {
            return null;
        }

        $multipliers = [
            'sedentary' => 1.2,
            'lightly_active' => 1.375,
            'moderately_active' => 1.55,
            'very_active' => 1.725,
            'extra_active' => 1.9,
        ];

        $multiplier = $multipliers[$this->activity_level] ?? 1.55;

        return (int) round($bmr * $multiplier);
    }

    /**
     * Calculate Ideal Target Weight Range for healthy BMI (18.5 - 24.9).
     *
     * @return array{min: float, max: float, ideal: float}|null
     */
    public function getIdealWeightRangeAttribute(): ?array
    {
        if (! $this->height_cm || $this->height_cm <= 0) {
            return null;
        }

        $heightMeters = $this->height_cm / 100;
        $minKg = round(18.5 * ($heightMeters * $heightMeters), 1);
        $maxKg = round(24.9 * ($heightMeters * $heightMeters), 1);
        $idealKg = round(21.75 * ($heightMeters * $heightMeters), 1);

        return [
            'min' => $minKg,
            'max' => $maxKg,
            'ideal' => $idealKg,
        ];
    }
}
