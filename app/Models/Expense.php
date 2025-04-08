<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id', 'branch_id', 'amount','cash_received' , 'P-Balance','Current Cash in Hand','Remaining Balance','description', 'dated', 'category_id', 'image'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
    
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
 