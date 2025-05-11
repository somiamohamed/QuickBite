<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Services\RestaurantService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class RestaurantController extends Controller
{
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function index(Request $request)
    {
        $restaurants = $this->restaurantService->getAllRestaurants($request);
        return RestaurantResource::collection($restaurants);
    }

    public function show($restaurantId)
    {
        $restaurant = $this->restaurantService->findRestaurantById($restaurantId);
        return new RestaurantResource($restaurant);
    }
    
    public function search(Request $request)
    {
        $restaurants = $this->restaurantService->searchRestaurants($request);
        return RestaurantResource::collection($restaurants);
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $this->authorize("update", $restaurant);
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'id' => 'required|integer', 
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'delivery_time' => 'required|integer']);
        $updatedRestaurant = $this->restaurantService->updateRestaurant($restaurant, $validatedData);
        return new RestaurantResource($updatedRestaurant);
    }
}