<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class FinancialAccount extends Model
{
    protected $fillable = ['name', 'type', 'balance', 'account_number', 'description'];
    protected $casts = ['balance' => 'decimal:2'];

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function updateBalance()
    {
        $income = $this->transactions()->where('type', 'income')->sum('amount');
        $expense = $this->transactions()->where('type', 'expense')->sum('amount');

        $this->update(['balance' => $income - $expense]);
    }
}
