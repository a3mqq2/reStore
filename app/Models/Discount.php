<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $appends = ['is_active'];
    protected $fillable = [
        'product_id',
        'discount_percentage',
        'start_date',
        'end_date',
    ];

    /**
     * Get the product associated with the discount.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Check if the discount is active.
     *
     * @return bool
     */
    public function getIsActiveAttribute()
    {
        $currentDate = now();
        return $this->start_date <= $currentDate && $this->end_date >= $currentDate;
    }

    public function isActive() 
    {
        $currentDate = now();
        return $this->start_date <= $currentDate && $this->end_date >= $currentDate;
    }

    /**
     * Scope a query to only include active discounts.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        $currentDate = now();
        return $query->where('start_date', '<=', $currentDate)
                     ->where('end_date', '>=', $currentDate);
    }
}
