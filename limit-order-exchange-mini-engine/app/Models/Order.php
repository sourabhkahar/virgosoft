<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $fillable = [
        'symbol',
        'side',
        'price',
        'amount',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
