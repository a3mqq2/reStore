<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderVoucherMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public array $item;      // بيانات المنتج من MooGold
    public array $vouchers;  // الأكواد المسترجعة

    /**
     * Create a new message instance.
     *
     * @param  Order $order
     * @param  array $item
     * @param  array $vouchers
     * @return void
     */
    public function __construct(Order $order, array $item, array $vouchers)
    {
        $this->order    = $order;
        $this->item     = $item;
        $this->vouchers = $vouchers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('كود / قسيمة الشراء #' . $this->order->gateway_order_id)
                    ->view('emails.order_voucher')
                    ->with([
                        'customerName'  => $this->order->customer_name,
                        'productName'   => $this->item['product']    ?? '',
                        'quantity'      => $this->item['quantity']   ?? 1,
                        'price'         => $this->item['price']      ?? '',
                        'total'         => $this->order->total_amount,
                        'vouchers'      => $this->vouchers,
                        'orderId'       => $this->order->gateway_order_id,
                        'orderDate'     => $this->order->created_at->format('Y-m-d H:i'),
                    ]);
    }
}
