<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    use HasFactory;

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'product_name',
        'product_image',
        'product_details',
        'amount',
        'active',
    ];

    /**
     * إرجاع مسار الصورة المخزنة.
     */

}
