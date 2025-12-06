<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantRedemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'variant_id',
        'order_id',
        'amount_used',
        'notes',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
