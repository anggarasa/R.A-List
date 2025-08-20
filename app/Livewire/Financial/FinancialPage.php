<?php

namespace App\Livewire\Financial;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\financial\FinancialBudget;
use App\Models\financial\FinancialAccount;
use App\Models\financial\FinancialCategory;
use App\Models\financial\FinancialTransaction;

class FinancialPage extends Component
{
    public $totalBalance;
    public $totalIncome;
    public $totalExpense;
    public $netBalance;
    public $monthlyData;
    public $categoryData;
    public $latestTransactions;
    public $incomeChange;
    public $expenseChange;
    public $monthlyBudget;

    public function mount()
    {
        $this->loadFinancialData();
        $this->dispatch('financial-page-loaded');
    }

    protected function loadFinancialData()
    {
        // Get current month and previous month
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        // Calculate total balance from all accounts
        $this->totalBalance = FinancialAccount::sum('balance');

        // Calculate current month income and expenses
        $this->totalIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth->month)
            ->whereYear('transaction_date', $currentMonth->year)
            ->sum('amount');

        $this->totalExpense = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth->month)
            ->whereYear('transaction_date', $currentMonth->year)
            ->sum('amount');

        // Calculate previous month for comparison
        $previousIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', $previousMonth->month)
            ->whereYear('transaction_date', $previousMonth->year)
            ->sum('amount');

        $previousExpense = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', $previousMonth->month)
            ->whereYear('transaction_date', $previousMonth->year)
            ->sum('amount');

        // Calculate percentage changes
        $this->incomeChange = $previousIncome > 0 
            ? round((($this->totalIncome - $previousIncome) / $previousIncome) * 100, 1)
            : 0;

        $this->expenseChange = $previousExpense > 0 
            ? round((($this->totalExpense - $previousExpense) / $previousExpense) * 100, 1)
            : 0;

        // Net balance is income minus expenses for current month
        $this->netBalance = $this->totalIncome - $this->totalExpense;

        // Get monthly data for chart (last 8 months)
        $this->monthlyData = $this->getMonthlyData();

        // Get category data for pie chart
        $this->categoryData = $this->getCategoryData();

        // Get latest transactions
        $this->latestTransactions = $this->getLatestTransactions();

        // Get monthly budget
        $this->monthlyBudget = $this->getMonthlyBudget();
    }

    public function getMonthlyBudget()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        return FinancialBudget::with('category')
            ->when($currentMonth, fn($q) => $q->where('month', $currentMonth))
            ->when($currentYear, fn($q) => $q->where('year', $currentYear))
            ->get()
            ->map(function ($budget) {
                return (object) [
                    'id' => $budget->id,
                    'category' => $budget->category,
                    'budget' => $budget->used_amount,          // jumlah terpakai
                    'monthly_budget' => $budget->amount,       // jumlah yg di-set
                    'progress' => $budget->percentage_used,    // persen progress
                ];
            });
    }

    protected function getMonthlyData()
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 7; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            
            $income = FinancialTransaction::where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');
            
            $expense = FinancialTransaction::where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount');

            $months[] = $monthName;
            $incomeData[] = (int) $income;
            $expenseData[] = (int) $expense;
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expenses' => $expenseData
        ];
    }

    protected function getCategoryData()
    {
        $currentMonth = Carbon::now();
        
        $categories = FinancialCategory::withSum(['transactions' => function ($query) use ($currentMonth) {
            $query->where('type', 'expense')
                  ->whereMonth('transaction_date', $currentMonth->month)
                  ->whereYear('transaction_date', $currentMonth->year);
        }], 'amount')
        ->having('transactions_sum_amount', '>', 0)
        ->get();

        $labels = [];
        $data = [];
        $colors = [
            'rgb(59, 130, 246)',   // Blue
            'rgb(34, 197, 94)',    // Green
            'rgb(245, 158, 11)',   // Yellow
            'rgb(168, 85, 247)',   // Purple
            'rgb(239, 68, 68)',    // Red
            'rgb(107, 114, 128)',  // Gray
        ];

        foreach ($categories as $index => $category) {
            $labels[] = $category->name;
            $data[] = (int) ($category->transactions_sum_amount ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    protected function getLatestTransactions()
    {
        return FinancialTransaction::with(['category', 'account'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'description' => $transaction->description,
                    'amount' => $transaction->amount,
                    'type' => $transaction->type,
                    'date' => $transaction->transaction_date->format('d M Y'),
                    'category' => $transaction->category->name ?? 'Uncategorized',
                ];
            });
    }

    public function render()
    {
        return view('livewire.financial.financial-page');
    }
}