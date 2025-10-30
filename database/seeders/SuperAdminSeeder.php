<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('app.super_admin_email', 'superadmin@example.com');
        $name = 'Super Admin';

        $existing = User::where('email', $email)->first();
        if ($existing) {
            $existing->update([
                'name' => $name,
                'full_name' => $name,
                'role' => 'super_admin',
                'is_organizer' => true,
            ]);
            return;
        }

        User::create([
            'name' => $name,
            'full_name' => $name,
            'email' => $email,
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_organizer' => true,
        ]);
    }
}


