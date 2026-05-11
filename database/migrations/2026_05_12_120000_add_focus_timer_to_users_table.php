<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('focus_timer_started_at')->nullable()->after('remember_token');
            $table->foreignId('focus_timer_task_id')->nullable()->after('focus_timer_started_at')->constrained('tasks')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['focus_timer_task_id']);
            $table->dropColumn(['focus_timer_task_id', 'focus_timer_started_at']);
        });
    }
};
