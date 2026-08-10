<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $workout = $this->route('workout');

        return $workout && $workout->user_id === $this->user()->id;
    }

    /**
     * Prepare inputs for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('workout_date') && $this->workout_date) {
            try {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}\s+\d{2}:\d{2}/', $this->workout_date)) {
                    $formatted = Carbon::createFromFormat('d/m/Y H:i', $this->workout_date)->format('Y-m-d H:i:s');
                } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}/', $this->workout_date)) {
                    $formatted = Carbon::createFromFormat('d/m/Y', $this->workout_date)->startOfDay()->format('Y-m-d H:i:s');
                } else {
                    $formatted = Carbon::parse($this->workout_date)->format('Y-m-d H:i:s');
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
