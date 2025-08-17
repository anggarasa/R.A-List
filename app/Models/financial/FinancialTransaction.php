<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'financial_category_id', 'financial_account_id', 'type', 'amount',
        'description', 'transaction_date', 'receipt_path'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date'
    ];

    // Eager loading untuk relasi yang sering digunakan
    protected $with = ['category', 'account'];

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }

    public function account()
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }

    protected static function booted()
    {
        static::created(function ($transaction) {
            if ($transaction->account) {
                $account = $transaction->account;
                
                if ($transaction->type === 'income') {
                    $account->increment('balance', $transaction->amount);
                } elseif ($transaction->type === 'expense') {
                    $account->decrement('balance', $transaction->amount);
                }
            }
        });

        static::updated(function ($transaction) {
            if ($transaction->account) {
                $transaction->account->recalculateBalance();
            }
        });

        static::deleted(function ($transaction) {
            if ($transaction->account) {
                $account = $transaction->account;
                
                if ($transaction->type === 'income') {
                    $account->decrement('balance', $transaction->amount);
                } elseif ($transaction->type === 'expense') {
                    $account->increment('balance', $transaction->amount);
                }
            }
        });
    }
}