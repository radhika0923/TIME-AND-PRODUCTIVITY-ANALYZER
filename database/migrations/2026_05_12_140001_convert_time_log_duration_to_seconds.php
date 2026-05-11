<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Historical values were whole minutes; store as seconds from here on.
        DB::table('time_logs')->update([
            'duration' => DB::raw('duration * 60'),
        ]);
    }

    public function down(): void
    {
        DB::table('time_logs')->update([
            'duration' => DB::raw('CAST(duration / 60 AS INTEGER)'),
        ]);
    }
};
