<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'image', 'stock', 'status',
        'category', 'portions_left', 'prep_time', 'rating', 'reviews_count', 'calories', 'ingredients'
    ];

    public function updateStatus()
    {
        $this->portions_left = $this->stock;
        if ($this->stock <= 0) {
            $this->status = 'sold_out';
        } elseif ($this->stock <= 10) {
            $this->status = 'almost_sold_out';
        } else {
            $this->status = 'available';
        }
        $this->save();
    }
}
