<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItemRequirement extends Model
{
    use HasFactory;

    protected $fillable = ['cart_item_id', 'requirement_id', 'value'];

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class);
    }


    public function requirement()
    {
        return $this->belongsTo(Requirement::class,'requirement_id');
    }
}
