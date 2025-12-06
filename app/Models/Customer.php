<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'city_id',
        'phone_number',
        'password',
        'email',
        'otp',
        'email_verified_at',
        'balance',
        'code',
        'cashback',
        'redemption_balance',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $customer->code = static::generateUniqueCode();
        });
    }

    public static function generateUniqueCode()
    {
        $code = Str::random(6);
        while (self::where('code', $code)->exists()) {
            $code = Str::random(6);
        }
        return $code;
    }

    // Define relationship with City model
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function balanceTransactions()
    {
        return $this->hasMany(BalanceTransaction::class);
    }

    public function variantRedemptions()
    {
        return $this->hasMany(VariantRedemption::class);
    }
}
