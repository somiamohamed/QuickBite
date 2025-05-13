<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Food;
use App\Models\User;
use App\Events\OrderStatusUpdated;
use App\Models\FoodOption;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($data['items'] as $item) {
                $food = Food::findOrFail($item['food_id']);
                $itemPrice = $food->price;
                $selectedOptionIds = $item['selected_options'] ?? [];
                $optionsPrice = 0;

                if (!empty($selectedOptionIds)) {
                    $validOptions = FoodOption::whereIn('id', $selectedOptionIds)->get();
                    $optionsPrice = $validOptions->sum('price_adjustment');
                }

                $itemTotal = ($itemPrice + $optionsPrice) * $item['quantity'];
                $totalAmount += $itemTotal;

                $orderItemsData[] = [
                    'food_id' => $food->id,
                    'quantity' => $item['quantity'],
                    'price_at_order' => $itemPrice + $optionsPrice, // Price per unit at time of order
                    'selected_options_payload' => json_encode($validOptions->toArray()) // Store selected options details
                ];
            }

            $order = $user->orders()->create([
                'restaurant_id' => $data['restaurant_id'],
                'total_amount' => $totalAmount,
                'status' => 'pending', // Default status
                'delivery_address' => $data['delivery_address'] ?? null,
                'delivery_time' => $data['delivery_time'] ?? null,
            ]);

            // This needs a pivot table `order_food` with columns: order_id, food_id, quantity, price_at_order, selected_options_payload (TEXT/JSON)
            foreach ($orderItemsData as $orderItem) {
                $order->foods()->attach($orderItem['food_id'], [
                    'quantity' => $orderItem['quantity'],
                    'price_at_order' => $orderItem['price_at_order'],
                    'selected_options_payload' => $orderItem['selected_options_payload']
                ]);
            }
            
            $order->load('foods', 'restaurant', 'user');
            return $order;
        });
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