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
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
//use Illuminate\Foundation\Support\Providers\EventServiceProvider as EventServiceProvider;

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

    public function boot(): void
    {
        RateLimiter::for("api", function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware("api")
                ->prefix("api")
                ->group(base_path("routes/api.php"));
        });
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