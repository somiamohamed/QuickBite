<?php

namespace Tests\Feature;

use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestaurantTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_restaurant()
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($user)
            ->postJson('/api/restaurants', [
                'name' => 'new restaurant',
                'description' => 'restaurant description',
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'new restaurant');
    }

    public function test_guests_cannot_create_restaurants()
    {
        $response = $this->postJson('/api/restaurants', [
            'name' => 'not allowed',
        ]);

        $response->assertUnauthorized();
    }
}