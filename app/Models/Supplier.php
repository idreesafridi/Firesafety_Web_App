<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name', 'address', 'phone1','phone2', 'mobile1','mobile2', 'user_id', 'dated'
    ];
}
