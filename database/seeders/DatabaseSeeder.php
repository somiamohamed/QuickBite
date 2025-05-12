<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\Food;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

//        Admin::factory()->create([
//            'name' => 'Admin',
//            'email' => 'admin@example.com',
//        ]);

        // Create categories
        $categories = [
            ['name' => 'Italian', 'slug' => 'italian'],
            ['name' => 'Fast Food', 'slug' => 'fast-food'],
            ['name' => 'Desserts', 'slug' => 'desserts'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create restaurants and attach categories
        $restaurant = Restaurant::create([
            'name' => 'Pizza Palace',
        ]);
        $restaurant->categories()->attach([1, 2]); // Attach Italian and Fast Food

        // Create foods and link to category
        $food = Food::create([
            'name' => 'Margherita Pizza',
            'price' => 12.99,
            'restaurant_id' => $restaurant->id,
        ]);

        Food::create([
            'name' => 'Cheeseburger',
            'price' => 8.99,
            'restaurant_id' => $restaurant->id,
        ]);
    }
}
