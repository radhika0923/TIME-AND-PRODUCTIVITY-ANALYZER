<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class TimeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Create time logs for each user's tasks
            $userTasks = $user->tasks()->get();

            if ($userTasks->isNotEmpty()) {
                // Create 3-5 time logs per task
                foreach ($userTasks as $task) {
                    TimeLog::factory()
                        ->count(rand(3, 5))
                        ->for($user)
                        ->for($task)
                        ->create();
                }
            }

            // Create some time logs without associated tasks (general focus time)
            TimeLog::factory()
                ->count(rand(2, 4))
                ->for($user)
                ->withoutTask()
                ->create();
        }
    }
}
