<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FoodPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Food $food): Response
    {
        return $user->id === $food->restaurant->user_id
            ? Response::allow()
            : Response::deny('Not authorized to manage this food item.');
    }

    public function create(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    public function update(User $user, Food $food): bool
    {
        return $user->id === $food->restaurant->user_id;
    }
}