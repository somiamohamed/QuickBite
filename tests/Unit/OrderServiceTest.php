<?php

namespace Tests\Unit;

use App\Models\Food;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderService();
    }

    public function test_create_order_calculates_total_correctly()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $food = Food::factory()->create([
            'restaurant_id' => $restaurant->id,
            'price' => 50
        ]);

        $order = $this->service->createOrder([
            'restaurant_id' => $restaurant->id,
            'items' => [['food_id' => $food->id, 'quantity' => 3]]
            ], $user);

        $this->assertEquals(150, $order->total);
    }
}