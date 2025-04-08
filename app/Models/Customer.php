<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_name', 'customer_ntn', 'phone_no', 'email', 'address', 'city', 'company_name', 'type', 'user_id', 'dated'
    ];
    public $timestamps = true;
}
