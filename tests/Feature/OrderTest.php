<?php

namespace Tests\Feature;

use App\Models\Food;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $restaurant = Restaurant::factory()->create();
        $food = Food::factory()->create(['restaurant_id' => $restaurant->id]);

        $response = $this->actingAs($user)
            ->postJson('/api/orders', [
                'restaurant_id' => $restaurant->id,
                'items' => [
                    ['food_id' => $food->id, 'quantity' => 2]
                ]
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.total', $food->price * 2);
    }
}