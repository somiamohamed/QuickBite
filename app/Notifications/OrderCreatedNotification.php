<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('# new order' . $this->order->id)
            ->line('a new order has recieved' . $this->order->restaurant->name)
            ->action('show order', url('/orders/' . $this->order->id))
            ->line('total order ' . $this->order->total_price . ' $');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => '# new order' . $this->order->id,
            'url' => '/orders/' . $this->order->id
        ];
    }
}