<?php

namespace App\Models;

use GuzzleHttp\Handler\Proxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'quantity',
        'price',
        'cost',
        'product_id',
        'variant_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function requirements()
    {
        return $this->hasMany(OrderRequirement::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function variantObj() {
        return $this->belongsTo(Variant::class, 'variant_id');
    }
}
