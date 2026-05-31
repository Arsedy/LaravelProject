<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First make sure the roles exist
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator role']
        );

        $userRole = Role::updateOrCreate(
            ['name' => 'user'],
            ['description' => 'Regular user role']
        );

        // Update or create the admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@mysite.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('12345'),
            ]
        );

        // Sync roles without detaching
        $adminUser->roles()->syncWithoutDetaching([
            $adminRole->id,
            $userRole->id,
        ]);
    }
}
