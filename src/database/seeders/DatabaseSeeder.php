<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            RolePermissionSeeder::class,
        ]);

        Contest::create([
            'title' => 'Test Contest',
            'description' => 'This is a test contest',
            'start_time' => now(),
            'end_time' => now()->addDays(1),
        ]);
    }
}
