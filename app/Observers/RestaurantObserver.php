<?php

namespace App\Observers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

class RestaurantObserver
{
    public function created(Restaurant $restaurant)
    {
        Storage::makeDirectory("restaurants/{$restaurant->id}");
    }

    public function updated(Restaurant $restaurant)
    {
        if ($restaurant->isDirty('logo')) {
            Storage::delete($restaurant->getOriginal('logo'));
        }
    }

    public function deleted(Restaurant $restaurant)
    {
        Storage::deleteDirectory("restaurants/{$restaurant->id}");
    }
}