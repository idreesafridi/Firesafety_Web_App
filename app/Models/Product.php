<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 'supplier_id', 'name', 'category_id', 'capacity', 'buying_price_per_unit', 'selling_price_per_unit', 'unit', 'image', 'description', 'dated', 'expiry_date', 'inventory'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}