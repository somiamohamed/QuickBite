<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Food;
use Illuminate\Auth\Access\Response;

class FoodPolicy
{
    public function manage(User $user, Food $food): Response
    {
        return $user->id === $food->restaurant->user_id
            ? Response::allow()
            : Response::deny('Not authorized to manage this food item.');
    }
}