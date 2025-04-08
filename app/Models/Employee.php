<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'phone', 
        'address', 
        'type', 
        'email', 
        'salary', 
        'bank', 
        'account_no', 
        'branch',
        'bike_maintenance'
        
    ];
}
