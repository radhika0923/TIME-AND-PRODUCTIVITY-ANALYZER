<?php

namespace Tests\Feature;

use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeLogManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_time_log(): void
    {
        $user = User::factory()->create();
        $log = TimeLog::factory()->for($user)->withoutTask()->create(['duration' => 120]);

        $this->actingAs($user)
            ->patch(route('time-logs.update', $log), [
                'duration' => 300,
                'task_id' => null,
            ])
            ->assertRedirect();

        $this->assertSame(300, $log->fresh()->duration);
    }

    public function test_user_can_delete_own_time_log(): void
    {
        $user = User::factory()->create();
        $log = TimeLog::factory()->for($user)->withoutTask()->create(['duration' => 120]);

        $this->actingAs($user)
            ->delete(route('time-logs.destroy', $log))
            ->assertRedirect();

        $this->assertDatabaseMissing('time_logs', ['id' => $log->id]);
    }

    public function test_export_returns_csv(): void
    {
        $user = User::factory()->create();
        TimeLog::factory()->for($user)->withoutTask()->create(['duration' => 600]);

        $this->actingAs($user)
            ->get(route('time.export'))
            ->assertOk()
            ->assertHeader('content-disposition');
    }
}
