<?php

namespace App\Services;

use App\Models\Food;
use Illuminate\Support\Facades\Storage;

class FoodService
{
    public function createFood(array $data, $imageFile)
    {
        $imagePath = $imageFile->store('foods/images', 'public');

        return Food::create([
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'image' => $imagePath,
            'restaurant_id' => $data['restaurant_id']
        ]);
    }
}