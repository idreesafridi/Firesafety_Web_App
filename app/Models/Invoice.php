<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model 
{
    protected $fillable = [
        'supplier_id', 'customer_id', 'user_id','branch','dated', 'refill_notification', 'sales_tax_invoice', 'termsConditions',
        'sub_total', 'transportaion', 'total_tax', 'total_amount', 'paid_amount', 'expiry_date', 'quote_id',
        'GST',
        'transportaion',
        'other_products_name',
        'other_products_qty', 
        'other_products_price',
        'other_products_unit',
        'other_products_size',
        'customer_ntn_no',
        'invoice_no',
        'customer_po_no',
        'discount_percent',
        'delievery_challan_no',
        'discount_fixed',
        'tax_rate',
        'branch_id',
        'WHT',
        'wh_tax',
        'gst_text',
        'gst_fixed'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(InvoiceProducts::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}  