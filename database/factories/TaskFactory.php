<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->sentence(12),
            'status' => fake()->randomElement(['pending', 'completed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }
}