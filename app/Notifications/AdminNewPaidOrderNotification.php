<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class AdminNewPaidOrderNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public $order) {}

    public function via($notifiable)
    {
        // مهم جدًا: broadcast + database
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message'  => "New paid order #{$this->order->id}",
            'total'    => (string) ($this->order->total ?? ''),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
