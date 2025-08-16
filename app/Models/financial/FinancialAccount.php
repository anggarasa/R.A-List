<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    protected $fillable = ['name', 'type', 'balance', 'account_number', 'description'];
    protected $casts = ['balance' => 'decimal:2'];

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'financial_account_id');
    }

    /**
     * Recalculate balance berdasarkan semua transaksi
     * Method ini digunakan untuk memastikan akurasi balance
     */
    public function recalculateBalance()
    {
        $totalIncome = $this->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = $this->transactions()->where('type', 'expense')->sum('amount');
        
        // Asumsi balance awal adalah 0, atau Anda bisa menambah field initial_balance
        $newBalance = $totalIncome - $totalExpense;
        
        $this->update(['balance' => $newBalance]);
        
        return $newBalance;
    }

    /**
     * Method untuk mendapatkan balance saat ini tanpa mengubah database
     */
    public function getCurrentBalance()
    {
        return $this->balance;
    }
}