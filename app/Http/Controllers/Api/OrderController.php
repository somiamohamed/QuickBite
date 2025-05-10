<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Events\OrderCreated;
use App\Services\PaymentService;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Notifications\OrderCreatedNotification;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected OrderService $orderService
    ) {}

    public function store(CreateOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());

        $order->user->notify(new OrderCreatedNotification($order));
        $order->restaurant->owner->notify(new OrderCreatedNotification($order));
        event(new OrderCreated($order));

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return new OrderResource($order);
    }

    use App\Notifications\OrderStatusUpdatedNotification;

    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        
        $order->user->notify(
            new OrderStatusUpdatedNotification($order, $request->status)
        );
        
        return new OrderResource($order);
    }

    public function payOrder(Request $request, Order $order, PaymentService $paymentService)
    {
        $payment = $paymentService->processPayment(
            $order,
            auth()->user(),
            $request->only(['method', 'card_token'])
        );

        return new PaymentResource($payment);
    }
}