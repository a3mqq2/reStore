<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'name', 'smileone_id', 'smileone_points', 'moogold_id', 'moogold_usd',
        'redemption_value', 'is_redeemable', 'redemption_cost'
    ];

    protected $appends = ['calculated_price', 'calculated_cost'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function prices() {
        return $this->hasMany(VariantPrice::class)->orderBy('price', 'asc');
    }

    public function variantRedemptions() {
        return $this->hasMany(VariantRedemption::class);
    }

    /**
     * Calculate SmileOne price: smileone_points * smileone_point_usd * dollar_sell_rate
     */
    public function getCalculatedPriceAttribute()
    {
        if (!$this->smileone_points || $this->smileone_points <= 0) {
            return null;
        }

        $content = Content::first();
        if (!$content || !$content->smileone_point_usd || !$content->dollar_sell_rate) {
            return null;
        }

        return round($this->smileone_points * $content->smileone_point_usd * $content->dollar_sell_rate, 2);
    }

    /**
     * Calculate SmileOne cost: smileone_points * smileone_point_usd * dollar_buy_rate
     */
    public function getCalculatedCostAttribute()
    {
        if (!$this->smileone_points || $this->smileone_points <= 0) {
            return null;
        }

        $content = Content::first();
        if (!$content || !$content->smileone_point_usd || !$content->dollar_buy_rate) {
            return null;
        }

        return round($this->smileone_points * $content->smileone_point_usd * $content->dollar_buy_rate, 2);
    }
}
