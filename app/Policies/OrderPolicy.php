<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    use HandlesAuthorization;
    public function view(User $user, Order $order): Response
    {
        return $user->id === $order->user_id || $user->role === 'delivery'
            ? Response::allow()
            : Response::deny('Not authorized to view this order.');
    }

    public function updateStatus(User $user, Order $order): Response
    {
        return ($user->role === 'delivery' || $user->id === $order->user_id)
            ? Response::allow()
            : Response::deny('Not authorized to update the status of this order.');
    }
}