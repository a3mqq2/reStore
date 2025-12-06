<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_product_id', 
        'name', 
        'value'
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }
}
