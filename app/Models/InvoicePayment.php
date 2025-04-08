<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id',
        'amount_recieved',
        'remaining_amount', 
        'wh_tax',
        'sales_tax',  
        'payment_mode', 
        'dated', 
        'recieved_by'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
    public function recievedby()
    {
        return $this->belongsTo(User::class, 'recieved_by');
    }
}
