<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'category_id', 'account_id', 'type', 'amount',
        'description', 'transaction_date', 'receipt_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date'
    ];

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class);
    }

    public function account()
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            $transaction->account->updateBalance();
        });

        static::updated(function ($transaction) {
            $transaction->account->updateBalance();
        });

        static::deleted(function ($transaction) {
            $transaction->account->updateBalance();
        });
    }
}
