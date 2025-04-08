<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QouteProducts extends Model
{
    protected $fillable = [
        'quote_id', 'product_id', 'qty', 'unit_price', 'productCapacity', 'sequence', 'heading', 'description'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
