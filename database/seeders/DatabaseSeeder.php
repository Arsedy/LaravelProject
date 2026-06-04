<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            ProductSeeder::class,
        ]);

        $products = Product::all();

        // Create 5 regular users
        $users = User::factory()->count(5)->create();

        // Sync regular role to these users if role exists
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            foreach ($users as $user) {
                $user->roles()->syncWithoutDetaching([$userRole->id]);
            }
        }

        // Seed some orders for registered users
        foreach ($users as $user) {
            Order::factory()->count(2)->create([
                'user_id' => $user->id,
            ])->each(function ($order) use ($products) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->price;
                if ($product->discount > 0) {
                    $price = $product->price - ($product->price * ($product->discount / 100));
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'price' => $price,
                    'quantity' => $qty,
                    'total' => $price * $qty,
                ]);

                // Update order total and subtotal to match
                $order->update([
                    'subtotal' => $price * $qty,
                    'total' => $price * $qty + $order->shipping_price,
                ]);
            });
        }

        // Seed some guest orders
        Order::factory()->count(5)->create([
            'user_id' => null,
        ])->each(function ($order) use ($products) {
            $product = $products->random();
            $qty = rand(1, 2);
            $price = $product->price;
            if ($product->discount > 0) {
                $price = $product->price - ($product->price * ($product->discount / 100));
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_title' => $product->title,
                'price' => $price,
                'quantity' => $qty,
                'total' => $price * $qty,
            ]);

            $order->update([
                'subtotal' => $price * $qty,
                'total' => $price * $qty + $order->shipping_price,
            ]);
        });
    }
}
