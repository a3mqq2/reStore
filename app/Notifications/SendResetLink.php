<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Password;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SendResetLink extends Notification
{
    use Queueable;

    protected $email;
    protected $customer;

    public function __construct($email, $customer)
    {
        $this->email = $email;
        $this->customer = $customer;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('website.reset-link', [
            'token' => $this->customer->remember_token,
            'email' => $this->customer->email
        ], false));

        \Log::info('Building password reset email', [
            'customer_id' => $this->customer->id,
            'email' => $this->customer->email,
            'reset_url' => $resetUrl
        ]);

        return (new MailMessage)
                    ->subject('إعادة تعيين كلمة المرور - ReStore')
                    ->greeting('مرحبا ' . $this->customer->name)
                    ->line('لقد تلقينا طلبًا لإعادة تعيين كلمة المرور الخاصة بحسابك.')
                    ->action('إعادة تعيين كلمة المرور', $resetUrl)
                    ->line('هذا الرابط صالح لمرة واحدة فقط.')
                    ->line('إذا لم تطلب إعادة تعيين كلمة المرور، فلا داعي لاتخاذ أي إجراء.');
    }
}
