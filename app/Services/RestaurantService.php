<?php

namespace App\Services;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

class RestaurantService
{
    public function createRestaurant(array $data, $logoFile)
    {
        $logoPath = $logoFile->store('restaurants/logos', 'public');
        
        return Restaurant::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'logo' => $logoPath,
            'user_id' => auth()->id()
        ]);
    }

    public function updateRestaurant(Restaurant $restaurant, array $data)
    {
        if (isset($data['logo'])) {
            Storage::delete($restaurant->logo);
            $data['logo'] = $data['logo']->store('restaurants/logos', 'public');
        }

        $restaurant->update($data);
        return $restaurant;
    }
}