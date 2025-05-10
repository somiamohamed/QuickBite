<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Food;
use App\Models\User;
use App\Events\OrderStatusUpdated;

class OrderService
{
    public function createOrder(array $data, User $user)
    {
        $restaurantId = Food::findOrFail($data['foods'][0]['food_id'])->restaurant_id;

        $order = Order::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'total_price' => 0,
            'status' => 'pending',
            'delivery_address' => $data['delivery_address'],
        ]);

        $this->addFoodsToOrder($order, $data['foods']);
        $this->calculateTotalPrice($order);

        return $order;
    }

    protected function addFoodsToOrder(Order $order, array $foods)
    {
        foreach ($foods as $item) {
            $food = Food::findOrFail($item['food_id']);
            $order->foods()->attach($food->id, [
                'quantity' => $item['quantity'],
                'price' => $food->price,
            ]);
        }
    }

    protected function calculateTotalPrice(Order $order)
    {
        $totalPrice = $order->foods->sum(function ($food) {
            return $food->pivot->price * $food->pivot->quantity;
        });

        $order->update(['total_price' => $totalPrice]);
    }

    public function updateStatus(Order $order, string $newStatus)
    {
        $oldStatus = $order->status;
        $order->update(['status' => $newStatus]);
        
        event(new OrderStatusUpdated($order, $oldStatus, $newStatus));
    }
}