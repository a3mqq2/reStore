<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'secret_number', 'serial_number', 'status', 'customer_id', 'used_at','amount'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
