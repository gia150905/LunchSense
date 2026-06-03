<?php

namespace Database\Seeders;

use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Tables in Zone A
        Seat::create([
            'table_number' => '1',
            'capacity' => 4,
            'available_seats' => 1,
            'status' => 'available',
            'zone' => 'Zone A',
            'social_mode' => false,
            'current_users' => json_encode(['Ali', 'John', 'Abu']),
            'coordinates' => json_encode(['x' => 1, 'y' => 1])
        ]);

        Seat::create([
            'table_number' => '2',
            'capacity' => 2,
            'available_seats' => 0,
            'status' => 'full',
            'zone' => 'Zone A',
            'social_mode' => false,
            'current_users' => json_encode(['Lily', 'Zack']),
            'coordinates' => json_encode(['x' => 1, 'y' => 2])
        ]);

        Seat::create([
            'table_number' => '3',
            'capacity' => 4,
            'available_seats' => 4,
            'status' => 'available',
            'zone' => 'Zone A',
            'social_mode' => false,
            'current_users' => json_encode([]),
            'coordinates' => json_encode(['x' => 1, 'y' => 3])
        ]);

        // Seed Tables in Zone B
        Seat::create([
            'table_number' => '4',
            'capacity' => 2,
            'available_seats' => 1,
            'status' => 'available',
            'zone' => 'Zone B',
            'social_mode' => true,
            'current_users' => json_encode(['Ahmad']),
            'coordinates' => json_encode(['x' => 2, 'y' => 1])
        ]);

        Seat::create([
            'table_number' => '5',
            'capacity' => 4,
            'available_seats' => 0,
            'status' => 'cleaning',
            'zone' => 'Zone B',
            'social_mode' => false,
            'current_users' => json_encode([]),
            'coordinates' => json_encode(['x' => 2, 'y' => 2])
        ]);

        // Figma specific Table #12
        Seat::create([
            'table_number' => '12',
            'capacity' => 4,
            'available_seats' => 3,
            'status' => 'available',
            'zone' => 'Zone B',
            'social_mode' => true,
            'current_users' => json_encode(['Sarah', 'David']),
            'coordinates' => json_encode(['x' => 2, 'y' => 3])
        ]);
    }
}
