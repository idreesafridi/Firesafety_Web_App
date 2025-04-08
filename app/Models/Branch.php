<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model 
{
    protected $fillable = [
        'branch_name', 'branch_address', 'manager_name', 'phone_number'
    ];

    public function expensesoneweek()
    {
    	return $this->hasMany(Expense::class, 'branch_id');
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function qoutes()
    {
        return $this->hasMany(Qoute::class);
    }
    public function cashmemos()
    {
        return $this->hasMany(CashMemo::class);
    }
}