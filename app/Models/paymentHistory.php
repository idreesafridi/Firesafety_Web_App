<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paymentHistory extends Model
{
    protected $fillable = [
        'invoice_id', 'amount_paid', 'dated', 'comments', 'recieved_by', 'verified',
    ];
}
