<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qoute extends Model
{
    protected $fillable = [
        'id','supplier_id', 'customer_id', 'user_id', 'dated','branch_id', 'termsConditions',
        'other_products_name',
        'other_products_qty',
        'other_products_price',
        'other_products_unit',
        'other_products_size',
        'other_products_image',
        'other_products_heading',
        'attachment',
        'subject',
        'GST',
        'transportaion',
        'productCapacity',
        'discount_percent',
        'discount_fixed',
        'tax_rate',
        'WHT',
        'wh_tax',
        'gst_text',
        'gst_fixed'
    ]; 
    
    public function products()
    {
        return $this->hasMany(QouteProducts::class, 'quote_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
} 