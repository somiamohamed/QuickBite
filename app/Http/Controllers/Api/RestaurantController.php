<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return RestaurantResource::collection($restaurants);
    }

    public function show($restaurantId)
    {
        $restaurant = Restaurant::with('foods')->findOrFail($restaurantId);
        return new RestaurantResource($restaurant);
    }

    public function search(Request $request)
    {
        $request->validate(['query' => 'required|string']);

        $restaurants = Restaurant::where('name', 'like', "%{$request->query}%")
                                ->get();

        return RestaurantResource::collection($restaurants);
    }

    use AuthorizesRequests;
    public function update(Request $request, Restaurant $restaurant)
    {
        $this->authorize('update', $restaurant);
        $restaurant->update($request->all());
        return new RestaurantResource($restaurant);
    }
}