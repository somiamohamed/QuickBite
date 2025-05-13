<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RestaurantPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Restaurant $restaurant): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'restaurant_owner';
    }

    public function update(User $user, Restaurant $restaurant): Response
    {
        return $user->id === $restaurant->user_id
            ? Response::allow()
            : Response::deny('Not authorized to update this restaurant.');
    }

    public function delete(User $user, Restaurant $restaurant): bool
    {
        return $user->id === $restaurant->user_id;
    }

    public function authorize($user, $ability)
    {
        return $user->hasPermissionTo($ability);
    }
}
