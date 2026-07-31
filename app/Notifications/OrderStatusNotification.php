<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order, public string $event = 'created')
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusKey = $this->order->status instanceof \App\Enums\OrderStatus 
            ? $this->order->status->value 
            : (string) $this->order->status;

        $statusLabels = [
            'pending' => 'تم استلام طلبك وهو قيد الانتظار',
            'processing' => 'جاري تجهيز وتغليف طلبك بكل عناية',
            'shipped' => 'تم شحن طلبك وهو في الطريق إليك',
            'delivered' => 'تم توصيل طلبك بنجاح! نتمنى لك تجربة مميزة',
            'cancelled' => 'تم إلغاء طلبك',
            'refunded' => 'تم استرجاع مبلغ طلبك',
        ];

        $statusMsg = $statusLabels[$statusKey] ?? 'تم تحديث حالة طلبك';

        return (new MailMessage)
            ->subject("تحديث طلبك #{$this->order->order_number} - متجر وِصال")
            ->greeting("مرحباً {$notifiable->name}،")
            ->line($statusMsg)
            ->line("رقم الطلب: #{$this->order->order_number}")
            ->line("الإجمالي: {$this->order->total_cents / 100} {$this->order->currency_code}")
            ->action('عرض تفاصيل الطلب', route('checkout.success', $this->order->id))
            ->line('شكراً لتسوقك من متجر وِصال!');
    }

    public function toArray(object $notifiable): array
    {
        $statusLabel = $this->order->status instanceof \App\Enums\OrderStatus 
            ? ($this->order->status->getLabel() ?? $this->order->status->value)
            : (string) $this->order->status;

        $statusVal = $this->order->status instanceof \App\Enums\OrderStatus 
            ? $this->order->status->value 
            : (string) $this->order->status;

        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $statusVal,
            'message' => "تم تحديث حالة طلبك #{$this->order->order_number} إلى: {$statusLabel}",
            'url' => route('checkout.success', $this->order->id)
        ];
    }
}
