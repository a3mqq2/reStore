<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'cashback_id',
        'notes',
        'status',
    ];

    const STATUS_PENDING = 'قيد التنفيذ';
    const STATUS_COMPLETED = 'منفذ';

    /**
     * Get the user associated with the redemption.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the cashback product associated with the redemption.
     */
    public function cashback()
    {
        return $this->belongsTo(Cashback::class);
    }
}