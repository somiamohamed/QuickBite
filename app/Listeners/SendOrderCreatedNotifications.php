<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Notifications\OrderCreatedNotification;

class SendOrderCreatedNotifications
{
    public function handle(OrderCreated $event)
    {
        $event->order->user->notify(
            new OrderCreatedNotification($event->order)
        );

        $event->order->restaurant->owner->notify(
            new OrderCreatedNotification($event->order)
        );
    }
}