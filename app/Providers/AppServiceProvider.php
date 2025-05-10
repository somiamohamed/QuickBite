<?php

namespace App\Providers;

use App\Models\Food;
use App\Models\Order;
use App\Models\Restaurant;
use App\Observers\FoodObserver;
use App\Observers\OrderObserver;
use App\Observers\RestaurantObserver;
use Illuminate\Support\ServiceProvider;
use App\Events\OrderCreated;
use App\Events\OrderStatusUpdated;
use App\Listeners\SendOrderCreatedNotifications;
use App\Listeners\UpdateInventory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderCreated::class => [
            SendOrderCreatedNotifications::class,
        ],
        OrderStatusUpdated::class => [
            UpdateInventory::class,
            \App\Listeners\NotifyDeliveryDriver::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Order::observe(OrderObserver::class);
        Restaurant::observe(RestaurantObserver::class);
        Food::observe(FoodObserver::class);
    }
}