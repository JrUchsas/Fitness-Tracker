<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('weekly_minutes_goal')->default(150);
            $table->unsignedInteger('weekly_calories_goal')->default(2000);
            $table->unsignedInteger('weekly_workouts_goal')->default(4);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['weekly_minutes_goal', 'weekly_calories_goal', 'weekly_workouts_goal']);
        });
    }
};
