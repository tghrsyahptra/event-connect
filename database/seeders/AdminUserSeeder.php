<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'full_name' => 'System Administrator',
            'email' => 'admin@eventconnect.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
            'is_organizer' => true,
        ]);

        // Create sample participant user
        User::create([
            'name' => 'John Doe',
            'full_name' => 'John Doe',
            'email' => 'participant@eventconnect.com',
            'role' => 'participant',
            'password' => Hash::make('participant123'),
            'is_organizer' => false,
        ]);
    }
}