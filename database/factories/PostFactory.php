<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(),
            'description' => fake()->sentence(),
            'content'     => fake()->paragraphs(3, true),
            'published'   => 1,
            'user_id'     => User::factory(),
            'category_id' => Category::factory(),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['published' => 0]);
    }
}
