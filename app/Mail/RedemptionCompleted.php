<?php

namespace App\Mail;

use App\Models\Redemption;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RedemptionCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $redemption;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Redemption $redemption)
    {
        $this->redemption = $redemption;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('تم تنفيذ الاسترداد الخاص بك')
            ->view('emails.redemption_completed')
            ->with([
                'customerName' => $this->redemption->customer->name,
                'productName' => $this->redemption->cashback->product_name,
                'amount' => $this->redemption->cashback->amount,
                'date' => $this->redemption->created_at->format('Y-m-d'),
            ]);
    }
}
