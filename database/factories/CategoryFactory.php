<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'parent_id' => null,
            'title' => ucwords($this->faker->unique()->words(2, true)),
            'keywords' => implode(', ', $this->faker->words(3)),
            'description' => $this->faker->sentence(),
            'image' => null,
            'status' => $this->faker->boolean(80),
        ];
    }
}
