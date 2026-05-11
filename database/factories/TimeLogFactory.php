<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimeLog>
 */
class TimeLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'task_id' => Task::factory(),
            'duration' => $this->faker->numberBetween(900, 28800), // seconds: 15 min to 8 h
            'created_at' => $this->faker->dateTimeThisMonth(),
        ];
    }

    /**
     * Indicate that the time log should not have an associated task.
     */
    public function withoutTask(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'task_id' => null,
            ];
        });
    }
}
