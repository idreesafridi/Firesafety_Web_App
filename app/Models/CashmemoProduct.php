<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashmemoProduct extends Model
{
    protected $fillable = [
        'cashmemo_id', 'product_id', 'qty', 'unit_price', 'productCapacity', 'sequence','heading'
    ];
}
