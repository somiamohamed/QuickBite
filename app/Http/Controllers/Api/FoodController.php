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
            // 'restaurant_id' is implicit from the route parameter $restaurantId
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'option_groups' => 'nullable|array',
            'option_groups.*.name' => 'required_with:option_groups|string',
            'option_groups.*.type' => 'required_with:option_groups|in:radio,checkbox',
            'option_groups.*.is_required' => 'sometimes|boolean',
            'option_groups.*.food_options' => 'required_with:option_groups|array',
            'option_groups.*.food_options.*.name' => 'required_with:option_groups.*.food_options|string',
            'option_groups.*.food_options.*.price_adjustment' => 'sometimes|numeric',
        ]);

        $imagePath = null;
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('foods', 'public'); 
        }

        $food = $this->foodService->createFood((int)$restaurantId, $validatedData);
        $foodData = collect($validatedData)->except(['option_groups', 'image'])->toArray();
        $foodData['restaurant_id'] = $restaurant->id;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $foodData['image_url'] = $request->file('image')->store('foods', 'public');
        }

        $food = Food::create($foodData);

        if ($request->has('option_groups')) {
            foreach ($request->input('option_groups') as $groupData) {
                $optionGroup = $food->optionGroups()->create([
                    'name' => $groupData['name'],
                    'type' => $groupData['type'],
                    'is_required' => $groupData['is_required'] ?? false,
                ]);
                foreach ($groupData['food_options'] as $optionData) {
                    $optionGroup->foodOptions()->create([
                        'name' => $optionData['name'],
                        'price_adjustment' => $optionData['price_adjustment'] ?? 0,
                    ]);
                }
            }
        }
        return new FoodResource($food->load('optionGroups.foodOptions'));
    }

    public function featured(Request $request)
    {
        $limit = $request->input('limit', 5);
        $featuredFood = Restaurant::where('is_featured', true)
            ->where('status', 'active')
            ->take($limit)
            ->get();
        return FoodResource::collection($featuredFood);
    }

    public function searchGlobal(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:255',
        ]);

        $queryTerm = $request->input('query');
        $perPage = $request->input('per_page', 15);

        $foods = Food::where('name', 'LIKE', "%{$queryTerm}%")
            ->orWhere('description', 'LIKE', "%{$queryTerm}%")
            ->with('restaurant') 
            ->paginate($perPage);

        return FoodResource::collection($foods);
    }
}