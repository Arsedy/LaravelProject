<?php

namespace Database\Seeders;

use App\Models\Order;
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
                'product_id' => $products->random()->id,
            ]);
        }

        // Seed some guest orders
        Order::factory()->count(5)->create([
            'user_id' => null,
            'product_id' => $products->random()->id,
        ]);
    }
}
