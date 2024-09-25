<?php

namespace Peyas\PreOrderForm\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserPreOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $preOrder;

    public function __construct($preOrder)
    {
        $this->preOrder = $preOrder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Pre-Order Confirmation')
            ->greeting('Hello ' . $this->preOrder->name . ',')
            ->line('Thank you for your pre-order!')
            ->line('We have received your pre-order for Product ID: ' . $this->preOrder->product_id)
            ->line('We will notify you once the order is processed.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
