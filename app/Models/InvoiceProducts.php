<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceProducts extends Model
{
    protected $fillable = [
        'invoice_id', 'product_id', 'qty', 'gst_product','unit_price', 'productCapacity', 'sequence', 'heading','description'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
