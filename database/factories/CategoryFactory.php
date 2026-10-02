<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Category::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->words(2, true);

        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
        ];
    }

    /**
     * Категория с конкретным названием
     */
    public function withTitle(string $title): static
    {
        return $this->state(fn () => [
            'title' => $title,
            'slug' => Str::slug($title),
        ]);
    }
}
