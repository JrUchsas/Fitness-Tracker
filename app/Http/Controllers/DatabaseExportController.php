<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DatabaseExportController extends Controller
{
    /**
     * Export the database as a portable SQLite database file (.sqlite).
     */
    public function export(Request $request): BinaryFileResponse
    {
        $connection = config('database.default');

        // If already using SQLite file storage, serve the file directly
        if ($connection === 'sqlite') {
            $dbPath = file_exists('/var/data/database.sqlite')
                ? '/var/data/database.sqlite'
                : database_path('database.sqlite');

            if (! file_exists($dbPath)) {
                abort(404, 'Database file not found.');
            }

            return response()->download($dbPath, 'database.sqlite');
        }

        // If using PostgreSQL / MySQL in production, generate a portable SQLite file
        $tempSqlitePath = tempnam(sys_get_temp_dir(), 'fitness_export_').'.sqlite';

        $sqlitePdo = new PDO('sqlite:'.$tempSqlitePath);
        $sqlitePdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sqlitePdo->exec('PRAGMA foreign_keys = OFF;');

        // 1. Create Users Table
        $sqlitePdo->exec('CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR NOT NULL,
            email VARCHAR NOT NULL UNIQUE,
            email_verified_at DATETIME,
            password VARCHAR NOT NULL,
            gender VARCHAR,
            age INTEGER,
            height_cm FLOAT,
            weight_kg NUMERIC(8,2),
            activity_level VARCHAR,
            weekly_minutes_goal INTEGER DEFAULT 150,
            weekly_calories_goal INTEGER DEFAULT 2000,
            weekly_workouts_goal INTEGER DEFAULT 4,
            remember_token VARCHAR,
            created_at DATETIME,
            updated_at DATETIME
        );');

        // 2. Create Workouts Table
        $sqlitePdo->exec('CREATE TABLE IF NOT EXISTS workouts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            type VARCHAR NOT NULL,
            duration_minutes INTEGER NOT NULL,
            distance_km NUMERIC(8,2),
            speed_kmh NUMERIC(8,2),
            weight_kg NUMERIC(8,2),
            sets INTEGER,
            reps INTEGER,
            jumps_count INTEGER,
            calories_burned INTEGER,
            workout_date DATETIME NOT NULL,
            notes TEXT,
            created_at DATETIME,
            updated_at DATETIME
        );');

        // 3. Create Weight Logs Table
        $sqlitePdo->exec('CREATE TABLE IF NOT EXISTS weight_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            weight_kg NUMERIC(8,2) NOT NULL,
            logged_date DATE NOT NULL,
            notes VARCHAR,
            created_at DATETIME,
            updated_at DATETIME
        );');

        // Copy Users data
        $users = DB::table('users')->get();
        $userStmt = $sqlitePdo->prepare('INSERT INTO users (id, name, email, email_verified_at, password, gender, age, height_cm, weight_kg, activity_level, weekly_minutes_goal, weekly_calories_goal, weekly_workouts_goal, remember_token, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($users as $u) {
            $userStmt->execute([
                $u->id,
                $u->name,
                $u->email,
                $u->email_verified_at ?? null,
                $u->password,
                $u->gender ?? null,
                $u->age ?? null,
                $u->height_cm ?? null,
                $u->weight_kg ?? null,
                $u->activity_level ?? null,
                $u->weekly_minutes_goal ?? 150,
                $u->weekly_calories_goal ?? 2000,
                $u->weekly_workouts_goal ?? 4,
                $u->remember_token ?? null,
                $u->created_at ?? now(),
                $u->updated_at ?? now(),
            ]);
        }

        // Copy Workouts data
        $workouts = DB::table('workouts')->get();
        $workoutStmt = $sqlitePdo->prepare('INSERT INTO workouts (id, user_id, type, duration_minutes, distance_km, speed_kmh, weight_kg, sets, reps, jumps_count, calories_burned, workout_date, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        foreach ($workouts as $w) {
            $workoutStmt->execute([
                $w->id,
                $w->user_id,
                $w->type,
                $w->duration_minutes,
                $w->distance_km ?? null,
                $w->speed_kmh ?? null,
                $w->weight_kg ?? null,
                $w->sets ?? null,
                $w->reps ?? null,
                $w->jumps_count ?? null,
                $w->calories_burned ?? null,
                $w->workout_date,
                $w->notes ?? null,
                $w->created_at ?? now(),
                $w->updated_at ?? now(),
            ]);
        }

        // Copy Weight Logs data
        $weightLogs = DB::table('weight_logs')->get();
        $logStmt = $sqlitePdo->prepare('INSERT INTO weight_logs (id, user_id, weight_kg, logged_date, notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)');
        foreach ($weightLogs as $wl) {
            $logStmt->execute([
                $wl->id,
                $wl->user_id,
                $wl->weight_kg,
                $wl->logged_date,
                $wl->notes ?? null,
                $wl->created_at ?? now(),
                $wl->updated_at ?? now(),
            ]);
        }

        return response()->download($tempSqlitePath, 'database.sqlite')->deleteFileAfterSend(true);
    }
}
