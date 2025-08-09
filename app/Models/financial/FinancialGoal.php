<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    protected $fillable = [
        'name', 'target_amount', 'current_amount',
        'target_date', 'status', 'description'
    ];

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
        return now()->diffInDays($this->target_date, false);
    }
}
