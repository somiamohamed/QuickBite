<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);
        $foods = $restaurant->foods;
        
        return FoodResource::collection($foods);
    }

    public function show($restaurantId, $foodId)
    {
        $food = Food::where('restaurant_id', $restaurantId)
                    ->findOrFail($foodId);
                    
        return new FoodResource($food);
    }

    public function store(Request $request, $restaurantId)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $food = Food::create([
            'restaurant_id' => $restaurantId,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $request->file('image') ? $request->file('image')->store('foods') : null,
        ]);

        return new FoodResource($food);
    }
}