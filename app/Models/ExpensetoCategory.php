<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensetoCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'category_id',
        'qty',
        'price',
        // Add other fields as needed
    ];
}
