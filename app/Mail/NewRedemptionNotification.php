<?php

namespace App\Mail;

use App\Models\Redemption;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewRedemptionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $redemption;
    public $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Redemption $redemption, $customer)
    {
        $this->redemption = $redemption;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('استرداد جديد من العميل')
            ->view('emails.new_redemption_notification')
            ->with([
                'customerName' => $this->customer->name,
                'cashbackAmount' => $this->redemption->cashback->amount,
                'productName' => $this->redemption->cashback->product_name,
                'notes' => $this->redemption->notes,
            ]);
    }
}
