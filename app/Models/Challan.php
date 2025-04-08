<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    protected $fillable = [
        'reference_no',
        'customer_order_no', 
        'customer_order_date', 
        'created_date',
        'invoice_no', 
        'customer_id', 
        'descriptions', 
        'qty', 
        'unit',
        'remarks', 
        'type',
        'productCapacity',
        'user_id',
        'sequence'
    ];
}
 