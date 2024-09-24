<?php

namespace Peyas\PreOrderForm\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminPreOrderNotification extends Notification
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Pre-Order Received')
            ->greeting('Hello Admin,')
            ->line('A new pre-order has been placed.')
            ->line('Customer Name: ' . $this->preOrder->name)
            ->line('Product ID: ' . $this->preOrder->product_id)
            ->line('Thank you for using our application!');
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
