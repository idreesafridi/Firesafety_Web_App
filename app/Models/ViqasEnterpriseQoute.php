<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViqasEnterpriseQoute extends Model
{
    protected $fillable = [
        'supplier_id', 'customer_id', 'user_id', 'dated', 'termsConditions',
        'other_products_name',
        'other_products_qty',
        'other_products_price',
        'other_products_unit',
        'other_products_image',
        'attachment',
        'subject',
        'GST',
        'transportaion',
        'productCapacity',
        'increasePercent',
    ]; 
}
  