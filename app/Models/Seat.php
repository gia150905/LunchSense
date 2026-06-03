<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = ['table_number', 'capacity', 'available_seats', 'status', 'zone', 'social_mode', 'current_users', 'coordinates'];

    protected function casts(): array
    {
        return [
            'current_users' => 'array',
            'coordinates' => 'array',
            'social_mode' => 'boolean',
        ];
    }
}
