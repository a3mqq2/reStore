<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be bulk assigned via methods like `create` or `update`.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'discount_type',
        'discount_percentage',
        'discount_amount',
        'description',
        'start_date',
        'end_date',
        'active',
        'product_id',
        'variant_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This ensures that these attributes are automatically converted
     * to the specified type when accessed or set.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
    ];

    /**
     * Get the product associated with the coupon.
     *
     * Defines an inverse one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variant associated with the coupon.
     *
     * Defines an inverse one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Get the orders that have used this coupon.
     *
     * Defines a one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope a query to only include active coupons.
     *
     * Example usage: Coupon::active()->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return void
     */
    public function scopeActive($query)
    {
        $query->where('active', true);
    }

    /**
     * Scope a query to filter coupons by discount type.
     *
     * Example usage: Coupon::discountType('percentage')->get();
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return void
     */
    public function scopeDiscountType($query, $type)
    {
        $query->where('discount_type', $type);
    }

    /**
     * Determine if the coupon is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return now()->gt($this->end_date);
    }

    /**
     * Generate a unique coupon code.
     *
     * This method can be used to auto-generate a coupon code if one isn't provided.
     *
     * @return string
     */
    public static function generateUniqueCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
