<?php

namespace App\Observers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;

class RestaurantObserver
{
    public function created(Restaurant $restaurant)
    {
        // إنشاء مجلد لصور المطعم
        Storage::makeDirectory("restaurants/{$restaurant->id}");
    }

    public function updated(Restaurant $restaurant)
    {
        // إذا تغير شعار المطعم
        if ($restaurant->isDirty('logo')) {
            Storage::delete($restaurant->getOriginal('logo'));
        }
    }

    public function deleted(Restaurant $restaurant)
    {
        // حذف جميع صور المطعم
        Storage::deleteDirectory("restaurants/{$restaurant->id}");
    }
}