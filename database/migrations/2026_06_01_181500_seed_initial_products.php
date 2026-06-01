<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Seed initial products automatically (skip in testing to prevent conflicts with factories)
        if (app()->environment() !== 'testing') {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\ProductSeeder',
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
