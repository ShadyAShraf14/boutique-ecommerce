<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CustomerOrderPaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        // DB حالياً – ممكن نزود mail بعدين
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type'        => 'customer_order_paid',
            'order_id'    => $this->order->id,
            'order_total' => $this->order->total,
            'status'      => $this->order->status,
            'paid_at'     => now()->toDateTimeString(),
        ];
    }
}
