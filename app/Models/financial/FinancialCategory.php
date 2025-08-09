<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
{
    protected $fillable = ['name', 'type'];

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function budgets()
    {
        return $this->hasMany(FinancialBudget::class);
    }

    public function getMonthlySpentAttribute()
    {
        return $this->transactions()
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->where('type', 'expense')
            ->sum('amount');
    }
}
