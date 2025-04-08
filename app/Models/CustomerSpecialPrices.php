<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSpecialPrices extends Model
{
    protected $fillable = [
        'product_id', 'productCapacity', 'discount_price', 'customer_id'
    ];
}
