<?php

namespace App\Services;

use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request; 
use Illuminate\Pagination\LengthAwarePaginator;

class FoodService
{
    public function getFoodsForRestaurant(int $restaurantId, Request $request): LengthAwarePaginator
    {
        return Food::where('restaurant_id', $restaurantId)->paginate($request->input('per_page', 10));
    }

    public function findFoodById(int $restaurantId, int $foodId): Food
    {
        return Food::where('restaurant_id', $restaurantId)
            ->with(['optionGroups.foodOptions']) 
            ->findOrFail($foodId);
    }

    public function createFood(int $restaurantId, array $validatedData): Food
    {
        $imagePath = null;

        if (isset($validatedData['image']) && $validatedData['image']->isValid()) {
            $imagePath = $validatedData['image']->store('foods', 'public');
        }

        return Food::create([
            'restaurant_id' => $restaurantId,
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'] ?? null,
            'image' => $imagePath,
        ]);
    }
}