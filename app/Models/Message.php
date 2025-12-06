<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender',
        'message',
        'timestamp',
        'status',
        'value',
        'customer_id',
        'source_number',
        'type', // نوع الرسالة: Libyana أو Almadar
    ];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
