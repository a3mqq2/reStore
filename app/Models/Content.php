<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'facebook',
        'instagram',
        'email',
        'whatsapp',
        'telegram',
        'policy',
        'returns',
        'about',
        'message',
        'libyana',
        'madar',
        'vodafone_cash',
        'asiacell',
        'point_cost',
        'point_cost_libyana',
        'point_cost_almadar',
        'point_cost_red',
        'point_cost_vfcash',
        'maintenance_mode',
        'maintenance_message',
        'dollar_buy_rate',
        'dollar_sell_rate',
        'smileone_point_usd',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'dollar_buy_rate' => 'decimal:2',
        'dollar_sell_rate' => 'decimal:2',
        'smileone_point_usd' => 'decimal:4',
        'point_cost_libyana' => 'decimal:4',
        'point_cost_almadar' => 'decimal:4',
        'point_cost_red' => 'decimal:4',
        'point_cost_vfcash' => 'decimal:4',
    ];
}
