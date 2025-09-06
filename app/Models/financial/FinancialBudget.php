<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialBudget extends Model
{
    protected $fillable = ['financial_category_id', 'amount', 'month', 'year', 'status'];
    protected $casts = ['amount' => 'decimal:2'];

    protected static function booted()
    {
        static::created(function () {
            \App\Livewire\Financial\FinancialPage::clearDashboardCache();
        });

        static::updated(function () {
            \App\Livewire\Financial\FinancialPage::clearDashboardCache();
        });

        static::deleted(function () {
            \App\Livewire\Financial\FinancialPage::clearDashboardCache();
        });
    }

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }

    public function getUsedAmountAttribute()
    {
        // This accessor should be avoided in favor of pre-calculated values
        // to prevent N+1 queries. Use optimized queries in the controller instead.
        return $this->category->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', $this->month)
            ->whereYear('transaction_date', $this->year)
            ->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->used_amount;
    }

    public function getPercentageUsedAttribute()
    {
        return $this->amount > 0 ? ($this->used_amount / $this->amount) * 100 : 0;
    }
}
