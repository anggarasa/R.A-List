<?php

namespace App\Models\financial;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    protected $fillable = [
        'name', 'target_amount', 'current_amount',
        'target_date', 'status', 'description'
    ];

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

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date'
    ];

    public function getProgressPercentageAttribute()
    {
        return $this->target_amount > 0 ?
            ($this->current_amount / $this->target_amount) * 100 : 0;
    }

    public function getRemainingAmountAttribute()
    {
        return $this->target_amount - $this->current_amount;
    }

    public function getDaysLeftAttribute()
    {
        return Carbon::now()->diffInDays($this->target_date, false);
    }

    public function getDaysLeftHumanAttribute()
    {
        return Carbon::parse($this->target_date)->diffForHumans([
            'parts' => 2,
            'short' => false,
            'syntax' => Carbon::DIFF_RELATIVE_TO_NOW,
        ]);
    }
}
