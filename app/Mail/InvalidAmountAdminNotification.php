<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvalidAmountAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $invalidValue;

    /**
     * إنشاء مثيل جديد من الرسالة.
     *
     * @param Customer $customer
     * @param float $invalidValue
     * @return void
     */
    public function __construct(Customer $customer, $invalidValue)
    {
        $this->customer = $customer;
        $this->invalidValue = $invalidValue;
    }

    /**
     * بناء الرسالة.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('تنبيه: قيمة تحويل غير صحيحة من زبون')
                    ->view('emails.invalid_amount_admin')
                    ->with([
                        'customerName' => $this->customer->name,
                        'customerPhone' => $this->customer->phone_number,
                        'customerEmail' => $this->customer->email,
                        'invalidValue' => $this->invalidValue,
                    ]);
    }
}
