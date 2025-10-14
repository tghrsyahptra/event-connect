<?php

namespace Database\Seeders;

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
        $this->call([
            CategorySeeder::class,
        ]);

        // Create test users
        User::factory()->create([
            'name' => 'Test User',
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'is_organizer' => false,
        ]);

        User::factory()->create([
            'name' => 'Event Organizer',
            'full_name' => 'Event Organizer',
            'email' => 'organizer@example.com',
            'is_organizer' => true,
        ]);
    }
}
