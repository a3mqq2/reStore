<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'description',
        'performed_by',
        'related_order_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }

    public function getTypeArabicAttribute()
    {
        return match($this->type) {
            'add' => 'إضافة رصيد',
            'subtract' => 'خصم رصيد',
            'transfer_in' => 'تحويل وارد',
            'transfer_out' => 'تحويل صادر',
            'order_payment' => 'دفع طلب',
            'refund' => 'استرداد',
            default => $this->type,
        };
    }
}
