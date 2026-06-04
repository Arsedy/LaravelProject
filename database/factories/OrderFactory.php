<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
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
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'zip_code' => $this->faker->postcode(),
            'subtotal' => $this->faker->randomFloat(2, 20, 2000),
            'shipping_price' => $this->faker->randomElement([0, 4]),
            'total' => $this->faker->randomFloat(2, 20, 2000),
            'shipping_method' => $this->faker->randomElement(['Free Shipping', 'Standard Shipping']),
            'payment_method' => $this->faker->randomElement(['Cash on Delivery', 'Direct Bank Transfer', 'Paypal']),
            'status' => $this->faker->randomElement(['New', 'Accepted', 'Cancelled', 'Onshipping', 'Completed']),
        ];
    }
}
