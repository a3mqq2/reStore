<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 
        'payment_method_id', 
        'order_date', 
        'order_notes', 
        'coupon_id', 
        'total_amount', 
        'discounted_total',
        'payment_notes',
        'payment_code',
        'from_cashback',
    ];

    public function products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class);
    }
}
