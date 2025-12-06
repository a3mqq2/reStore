<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvalidAmountNotification extends Mailable
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
        return $this->subject('تنبيه: قيمة التحويل غير صحيحة')
                    ->view('emails.invalid_amount')
                    ->with([
                        'customerName' => $this->customer->name,
                        'invalidValue' => $this->invalidValue,
                    ]);
    }
}
