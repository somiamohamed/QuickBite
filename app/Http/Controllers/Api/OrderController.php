<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderService;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use AuthorizesRequests;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(CreateOrderRequest $request)
    {
        try {
            $order = $this->orderService->createOrder($request->user(), $validatedData = $request->validate());
            return new OrderResource($order);
        }
        catch (\Exception $e) {
            // Log the exception
            return response()->json(['message' => 'Failed to create order: ' . $e->getMessage()], 500);
        }
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return new OrderResource($order);
    }


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
            $order, auth()->user(), $request->only(['method', 'card_token'])
        );

        return new OrderResource($payment);
    }

    public function indexForUser(Request $request)
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['restaurant', 'foods'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return OrderResource::collection($orders);
    }
}