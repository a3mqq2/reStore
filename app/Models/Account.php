<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_category_id',
        'title',
        'description',
        'username',
        'password',
        'price',
        'status',
        'customer_id',
        'sold_at',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sold_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(AccountCategory::class, 'account_category_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSold($query)
    {
        return $query->where('status', 'sold');
    }

    public function markAsSold($customerId)
    {
        $this->update([
            'status' => 'sold',
            'customer_id' => $customerId,
            'sold_at' => now(),
        ]);
    }
}
