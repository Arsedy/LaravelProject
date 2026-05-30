<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'title' => ucwords($this->faker->unique()->words(3, true)),
            'keywords' => implode(', ', $this->faker->words(3)),
            'description' => $this->faker->sentence(),
            'detail' => $this->faker->paragraphs(3, true),
            'image' => null,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(10, 100),
            'min_stock' => $this->faker->numberBetween(2, 10),
            'discount' => $this->faker->randomFloat(2, 0, 50),
            'status' => $this->faker->boolean(90),
        ];
    }
}
