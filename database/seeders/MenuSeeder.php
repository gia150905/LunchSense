<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::insert([
            [
                'name' => 'Nasi Ayam', 
                'description' => 'Traditional Malaysian chicken rice with fragrant rice and succulent roasted chicken, served with soy-ginger glaze and spicy chili dip.', 
                'price' => 6.00, 
                'stock' => 18, 
                'status' => 'available', 
                'category' => 'Rice',
                'portions_left' => 18,
                'prep_time' => 5,
                'rating' => 4.80,
                'reviews_count' => 52,
                'calories' => 450,
                'ingredients' => 'Fragrant Rice, Roasted Chicken, Soy-Ginger Glaze, Chili Dip, Cucumber, Garlic',
                'image' => null, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Nasi Goreng Kampung', 
                'description' => 'Authentic spicy fried rice with anchovies, water spinach, and scrambled eggs, topped with a fried egg.', 
                'price' => 5.50, 
                'stock' => 12,  
                'status' => 'available', 
                'category' => 'Rice',
                'portions_left' => 12,
                'prep_time' => 8,
                'rating' => 4.60,
                'reviews_count' => 35,
                'calories' => 580,
                'ingredients' => 'White Rice, Anchovies, Bird\'s Eye Chili, Egg, Garlic, Shrimp Paste, Soy Sauce',
                'image' => null, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Mee Goreng Mamak', 
                'description' => 'Spicy wok-fried yellow noodles with tofu, potatoes, bean sprouts, and sweet soy sauce.', 
                'price' => 5.00, 
                'stock' => 8,  
                'status' => 'almost_sold_out', 
                'category' => 'Noodles',
                'portions_left' => 8,
                'prep_time' => 6,
                'rating' => 4.50,
                'reviews_count' => 28,
                'calories' => 510,
                'ingredients' => 'Yellow Noodles, Chili Paste, Tofu, Potato, Soy Sauce, Egg, Tomato Sauce',
                'image' => null, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
            [
                'name' => 'Teh Ais', 
                'description' => 'Chilled classic pulled black tea mixed with sweetened condensed milk and served over ice.', 
                'price' => 2.50, 
                'stock' => 50, 
                'status' => 'available', 
                'category' => 'Beverages',
                'portions_left' => 50,
                'prep_time' => 2,
                'rating' => 4.90,
                'reviews_count' => 120,
                'calories' => 180,
                'ingredients' => 'Black Tea Leaves, Sweetened Condensed Milk, Evaporated Milk, Ice Cubes',
                'image' => null, 
                'created_at' => now(), 
                'updated_at' => now()
            ],
        ]);
    }
}
