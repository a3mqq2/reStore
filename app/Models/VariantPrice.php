<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantPrice extends Model
{
    use HasFactory;

    protected $fillable = ['variant_id','payment_method_id','price','original_price'];
    
    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class);
    }
}
