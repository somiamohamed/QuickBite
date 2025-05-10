<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RestaurantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        \App\Models\Restaurant::create([
            'name' => 'SushiBai',
            'description' => 'Asian food restaurant',
            'logo' => 'restaurants/chef.png',
            'delivery_time' => '30-45 minutes',
        ]);
    }
}
