<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    protected $fillable = [
        'month',
        'year',
        'total_income',
        'total_expense',
        'belance',
    ];
}
