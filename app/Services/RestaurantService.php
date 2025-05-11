<?php
namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class RestaurantService
{
    public function getAllRestaurants(Request $request): LengthAwarePaginator
    {
        $query = Restaurant::query();
        if ($request->has("cuisine")) {
            $query->where("cuisine", "like", "%" . $request->input("cuisine") . "%");
        }
        return $query->paginate(10);
    }

    public function findRestaurantById(int $id): Restaurant
    {
        return Restaurant::with("foods")->findOrFail($id);
    }
    
    public function searchRestaurants(Request $request): LengthAwarePaginator
    {
        $validatedData = $request->validate(["query" => "required|string"]);
        return Restaurant::where("name", "like", "%{$validatedData["query"]}%")->paginate(10);
    }

    public function updateRestaurant(Restaurant $restaurant, array $validatedData): Restaurant
    {
        $restaurant->update($validatedData);
        return $restaurant;
    }
}