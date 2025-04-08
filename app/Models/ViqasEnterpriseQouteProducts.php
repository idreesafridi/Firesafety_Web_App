<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViqasEnterpriseQouteProducts extends Model
{
    protected $fillable = [
        'quote_id', 'product_id', 'qty', 'unit_price', 'productCapacity'
    ]; 
}
 