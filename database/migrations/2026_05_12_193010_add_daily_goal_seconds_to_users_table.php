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
            $table->integer('daily_goal_seconds')->default(14400); // Default 4 hours
            $table->integer('pomodoro_work')->default(25); // Default 25 mins
            $table->integer('pomodoro_break')->default(5); // Default 5 mins
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['daily_goal_seconds', 'pomodoro_work', 'pomodoro_break']);
        });
    }
};
