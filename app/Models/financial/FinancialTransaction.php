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
                    // Tambah balance untuk income
                    $account->increment('balance', $transaction->amount);
                } elseif ($transaction->type === 'expense') {
                    // Kurangi balance untuk expense
                    $account->decrement('balance', $transaction->amount);
                }
                // Untuk transfer, bisa ditambahkan logika sesuai kebutuhan
            }
        });

        static::updated(function ($transaction) {
            if ($transaction->account) {
                // Recalculate balance untuk memastikan akurasi
                $transaction->account->recalculateBalance();
            }
        });

        static::deleted(function ($transaction) {
            if ($transaction->account) {
                $account = $transaction->account;
                
                if ($transaction->type === 'income') {
                    // Kurangi balance karena income dihapus
                    $account->decrement('balance', $transaction->amount);
                } elseif ($transaction->type === 'expense') {
                    // Tambah balance karena expense dihapus
                    $account->increment('balance', $transaction->amount);
                }
            }
        });
    }
}