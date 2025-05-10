<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\Food;

class UpdateInventory
{
    public function handle(OrderStatusUpdated $event)
    {
        if ($event->newStatus === 'completed') {
            foreach ($event->order->foods as $food) {
                Food::where('id', $food->id)->decrement(
                    'quantity',
                    $food->pivot->quantity
                );
            }
        }
    }
}