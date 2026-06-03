<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Student Aina',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'wallet_balance' => 142.50,
            'active_dietary_tags' => json_encode(['Halal', 'Gluten-Free']),
            'favorite_cafeteria' => 'Cafeteria DKG 6, UUM',
        ]);

        User::create([
            'name' => 'Staff Budi',
            'email' => 'staff@example.com',
            'password' => Hash::make('password'),
            'role' => 'staff'
        ]);

        User::create([
            'name' => 'Admin Cici',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
    }
}
