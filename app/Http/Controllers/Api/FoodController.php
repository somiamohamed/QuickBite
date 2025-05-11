<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Services\FoodService;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    protected $foodService;

    public function __construct(FoodService $foodService)
    {
        $this->foodService = $foodService;
    }

    public function index(Request $request, $restaurantId)
    {
        $foods = $this->foodService->getFoodsForRestaurant((int)$restaurantId, $request);
        return FoodResource::collection($foods);
    }

    public function show($restaurantId, $foodId)
    {
        $food = $this->foodService->findFoodById((int)$restaurantId, (int)$foodId);
        return new FoodResource($food);
    }

    public function store(Request $request, $restaurantId)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'restaurant_id' => 'required|exists:restaurants,id', 
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('foods', 'public'); 
        }

        $food = $this->foodService->createFood((int)$restaurantId, $validatedData);

        return new FoodResource($food);
    }
}