<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function requirements() {
        return $this->hasMany(Requirement::class);
    }

    public function variants() {
        return $this->hasMany(Variant::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class)->where('start_date', '<=', now())->where('end_date', '>=', now());
    }

    public function cashbacks()
    {
        return $this->hasMany(Cashback::class);
    }

    public function subProducts()
    {
        return $this->hasMany(Product::class, 'product_id');
    }

    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
