<?php

namespace App\Http\Controllers;

use App\Models\WeightLog;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WeightLogController extends Controller
{
    /**
     * Store a new body weight log entry.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->has('logged_date') && $request->logged_date) {
            try {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}/', $request->logged_date)) {
                    $formatted = Carbon::createFromFormat('d/m/Y', $request->logged_date)->format('Y-m-d');
                } else {
                    $formatted = Carbon::parse($request->logged_date)->format('Y-m-d');
                }
                $request->merge(['logged_date' => $formatted]);
            } catch (\Throwable $e) {
                // Fallback to standard validation
            }
        }

        $validated = $request->validate([
            'weight_kg' => ['required', 'numeric', 'min:1', 'max:500'],
            'logged_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $user->weightLogs()->create([
            'weight_kg' => $validated['weight_kg'],
            'logged_date' => $validated['logged_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Also update current profile body weight to keep features in sync
        $user->update([
            'weight_kg' => $validated['weight_kg'],
        ]);

        return back()->with('success', 'Body weight logged successfully!');
    }

    /**
     * Remove a body weight log entry.
     */
    public function destroy(Request $request, WeightLog $weightLog): RedirectResponse
    {
        if ($weightLog->user_id !== $request->user()->id) {
            abort(403);
        }

        $weightLog->delete();

        return back()->with('success', 'Body weight entry deleted.');
    }
}
