<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed admin user automatically (skip in testing to prevent unique constraint conflicts)
        if (app()->environment() !== 'testing') {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\AdminUserSeeder',
                '--force' => true,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
