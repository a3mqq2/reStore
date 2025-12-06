<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CartItem extends Model
{
    use HasFactory;

        protected $fillable = ['product_id', 'quantity','customer_id','variant_id','session_id'];



        public function product()
        {
            return $this->belongsTo(Product::class);
        }

        public function variant()
        {
            return $this->belongsTo(Variant::class);
        }

        public function requirements() {
            return $this->hasMany(CartItemRequirement::class,'cart_item_id');
        }

}