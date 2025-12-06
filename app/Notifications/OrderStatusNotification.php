<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $messageText;

    public function __construct(Order $order, $messageText)
    {
        $this->order = $order;
        $this->messageText = $messageText;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('شكراََ لشرائك من خدماتنا')
            ->view('emails.order_notification', [
                'order' => $this->order,
                'messageText' => $this->messageText,
                'getArabicStatus' => function($status) {
                    return $this->getArabicStatus($status);
                }
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function getArabicStatus($status)
    {
        switch ($status) {
            case 'new':
                return 'جديد';
            case 'approved':
                return 'مكتمل';
            case 'canceled':
                return 'ملغي';
            case 'under_payment':
                return 'قيد الشراء';
            default:
                return $status;
        }
    }
}
