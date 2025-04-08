<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'user_id', 'month', 
        'salary', 'over_time', 'night_half','night_full','dns_allounce','medical_allounce',
        'house_rent', 'convence',
        'advance', 'absent_days','absent_amount', 'half_day', 'ensurance',
        'provident', 'professional', 'tax',
        'gross_earning', 'total_deduction', 'net_salary', 'prepared_by', 'dated'
    ];
}
