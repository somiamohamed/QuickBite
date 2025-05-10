<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order,public string $newStatus) 
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => '# your order status has been updated' . $this->order->id . ' إلى: ' . $this->translateStatus(),
            'url' => '/orders/' . $this->order->id
        ];
    }

    protected function translateStatus(): string
    {
        return match($this->newStatus) {
            'preparing' => 'being prepared',
            'on_the_way' => 'on the way',
            'delivered' => 'delivered',
            default => $this->newStatus
        };
    }
}