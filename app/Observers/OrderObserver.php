<?php

namespace App\Observers;

use App\Models\Order;
use App\Events\OrderStatusUpdated;
use App\Notifications\OrderStatusNotification;

class OrderObserver
{
    public function created(Order $order)
    {
        // تشغيل حدث إنشاء طلب جديد
        event(new \App\Events\OrderCreated($order));
    }

    public function updated(Order $order)
    {
        // إذا تغيرت حالة الطلب
        if ($order->isDirty('status')) {
            event(new OrderStatusUpdated(
                $order,
                $order->getOriginal('status'),
                $order->status
            ));
        }
    }

    public function deleting(Order $order)
    {
        // حذف العناصر المرتبطة قبل حذف الطلب
        $order->foods()->detach();
    }
}