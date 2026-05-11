<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_time_tracking_page_loads_for_verified_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('time.index'))
            ->assertOk();
    }

    public function test_cannot_start_second_timer(): void
    {
        Carbon::setTestNow('2026-01-01 12:00:00');
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('time.start'), [])->assertOk();

        $this->actingAs($user)
            ->postJson(route('time.start'), [])
            ->assertStatus(400)
            ->assertJsonFragment(['error' => 'Timer already running.']);

        Carbon::setTestNow();
    }

    public function test_stop_under_one_minute_does_not_create_log(): void
    {
        Carbon::setTestNow('2026-01-01 12:00:00');
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('time.start'), [])->assertOk();

        // Same instant: zero full minutes (avoids Carbon diffInMinutes edge cases across versions)
        $this->actingAs($user)
            ->postJson(route('time.stop'))
            ->assertOk()
            ->assertJson(['logged' => false]);

        $this->assertDatabaseCount('time_logs', 0);

        Carbon::setTestNow();
    }

    public function test_stop_after_one_minute_creates_log(): void
    {
        Carbon::setTestNow('2026-01-01 12:00:00');
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('time.start'), [])->assertOk();

        Carbon::setTestNow('2026-01-01 12:02:30');
        $this->actingAs($user)
            ->postJson(route('time.stop'))
            ->assertOk()
            ->assertJson(['logged' => true]);

        $this->assertDatabaseCount('time_logs', 1);

        Carbon::setTestNow();
    }

    public function test_active_timer_persists_on_user_record_across_requests(): void
    {
        Carbon::setTestNow('2026-01-01 14:00:00');
        $user = User::factory()->create();

        $this->actingAs($user)->postJson(route('time.start'), [])->assertOk();

        $user->refresh();
        $this->assertNotNull($user->focus_timer_started_at);
        $this->assertNull($user->focus_timer_task_id);

        $this->actingAs($user)
            ->get(route('time.index'))
            ->assertOk()
            ->assertSee('Tracking Active', false);

        Carbon::setTestNow();
    }
}
