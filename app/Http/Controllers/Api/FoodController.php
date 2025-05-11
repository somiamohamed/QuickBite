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
        $food = Food::where("restaurant_id", $restaurantId)
                    ->with(["optionGroups.foodOptions"])
                    ->findOrFail($foodId);
                    
        return new FoodResource($food);
    }

    public function store(Request $request, $restaurantId)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "price" => "required|numeric|min:0",
            "description" => "nullable|string",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "restaurant_id" => "required|exists:restaurants,id",
        ]);

        $imagePath = null;
        if ($request->hasFile("image") && $request->file("image")->isValid()) {
            $imagePath = $request->file("image")->store("foods", "public"); 
        }

        $food = Food::create([
            "restaurant_id" => $validatedData["restaurant_id"],
            "name" => $validatedData["name"],
            "price" => $validatedData["price"],
            "description" => $validatedData["description"],
            "image" => $imagePath,
        ]);

        return new FoodResource($food);
    }
}