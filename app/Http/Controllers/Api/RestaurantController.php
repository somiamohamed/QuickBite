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
    use AuthorizesRequests;
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function index(Request $request)
    {
        $query = Restaurant::query();

        if ($request->has('cuisine')) {
            $query->where('cuisine', 'like', '%' . $request->input('cuisine') . '%');
        }

        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->input('min_rating'));
        }

        if ($request->has('sort_by')) {
            $sortDir = $request->input('sort_dir', 'asc'); // Default to ascending
            $query->orderBy($request->input('sort_by'), $sortDir);
        }

        $perPage = $request->input('per_page', 15); // Default to 15 items per page
        $restaurants = $query->paginate($perPage);

        if ($request->has('is_featured') && $request->boolean('is_featured')) {
            $query->where('is_featured', true);
        }
        if ($request->has('is_popular') && $request->boolean('is_popular')) {
            $query->where('is_popular', true);
        }

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

    public function featured(Request $request)
    {
        $limit = $request->input('limit', 5);
        $featuredRestaurants = Restaurant::where('is_featured', true)
            ->where('status', 'active') // Ensure active restaurants
            ->take($limit)
            ->get();
        return RestaurantResource::collection($featuredRestaurants);
    }

}