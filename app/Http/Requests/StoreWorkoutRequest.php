<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare inputs for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('workout_date') && $this->workout_date) {
            try {
                $raw = trim((string) $this->workout_date);
                if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})\s+(\d{1,2}):(\d{2})(?::(\d{2}))?/', $raw, $m)) {
                    $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($m[2], 2, '0', STR_PAD_LEFT);
                    $year = $m[3];
                    $hour = str_pad($m[4], 2, '0', STR_PAD_LEFT);
                    $minute = $m[5];
                    $second = $m[6] ?? '00';
                    $formatted = "{$year}-{$month}-{$day} {$hour}:{$minute}:{$second}";
                } elseif (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})/', $raw, $m)) {
                    $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                    $month = str_pad($m[2], 2, '0', STR_PAD_LEFT);
                    $year = $m[3];
                    $formatted = "{$year}-{$month}-{$day} 00:00:00";
                } else {
                    $formatted = Carbon::parse($raw)->format('Y-m-d H:i:s');
                }
                $this->merge(['workout_date' => $formatted]);
            } catch (\Throwable $e) {
                // Allow validation rule to handle invalid formats
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(['Indoor Cycling', 'Treadmill', 'Heavyweight Training', 'Jump Rope', 'Yoga', 'Other'])],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:1440'],
            'distance_km' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'speed_kmh' => ['nullable', 'numeric', 'min:0', 'max:200'],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'sets' => ['nullable', 'integer', 'min:0', 'max:500'],
            'reps' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'jumps_count' => ['nullable', 'integer', 'min:0', 'max:50000'],
            'calories_burned' => ['nullable', 'integer', 'min:0', 'max:20000'],
            'workout_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
