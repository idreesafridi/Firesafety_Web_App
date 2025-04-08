<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashMemo extends Model
{
    protected $fillable = [
        'id',
        'reference_no',
        'customer_po_no',
        'delievery_challan_no',
        'discount_fixed',
        'transportaion',
        'dated',
        'branch_id',
        'customer_order_no', 
        'customer_order_date', 
        'created_date',
        'quote_id',
        'descriptions', 
        'qty', 
        'unit_price', 
        'total_amounts', 
        'customer_id',
        'user_id',
        'productCapacity',
        'sequence',
        'discount_percent',
        'discount_fixed',
        'transportaion',
        'dated',
    ];
    protected $primaryKey = 'id'; // Specify the non-incrementable column as primary key
    public $incrementing = false; // Turn off auto-increment

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}