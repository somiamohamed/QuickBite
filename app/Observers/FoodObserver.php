<?php

namespace App\Observers;

use App\Models\Food;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class FoodObserver
{
    public function saved(Food $food)
    {
        Cache::forget("restaurant_{$food->restaurant_id}_foods");
    }

    public function deleted(Food $food)
    {
        if ($food->image) {
            Storage::delete($food->image);
        }
    }
}