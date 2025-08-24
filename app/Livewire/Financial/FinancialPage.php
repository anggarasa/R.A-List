<?php

namespace App\Livewire\Financial;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\financial\FinancialGoal;
use App\Models\financial\FinancialBudget;
use App\Models\financial\FinancialAccount;
use App\Models\financial\FinancialCategory;
use App\Models\financial\FinancialTransaction;

class FinancialPage extends Component
{
    public $totalBalance = 0;
    public $totalIncome = 0;
    public $totalExpense = 0;
    public $netBalance = 0;
    public $monthlyData = [];
    public $categoryData = [];
    public $latestTransactions = [];
    public $incomeChange = 0;
    public $expenseChange = 0;
    public $monthlyBudget = [];
    public $financialGoals = [];

    public function mount()
    {
        $this->loadFinancialData();
        $this->dispatch('financial-page-loaded');
        $this->dispatch('initialize-charts', [
            'monthlyData' => $this->monthlyData,
            'categoryData' => $this->categoryData
        ]);
    }

    protected function loadFinancialData()
    {
        // Get current month and previous month
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        // Calculate total balance from all accounts
        $this->totalBalance = FinancialAccount::sum('balance') ?? 0;

        // Calculate current month income and expenses
        $this->totalIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', $currentMonth->month)
            ->whereYear('transaction_date', $currentMonth->year)
            ->sum('amount') ?? 0;

        $this->totalExpense = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', $currentMonth->month)
            ->whereYear('transaction_date', $currentMonth->year)
            ->sum('amount') ?? 0;

        // Calculate previous month for comparison
        $previousIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', $previousMonth->month)
            ->whereYear('transaction_date', $previousMonth->year)
            ->sum('amount') ?? 0;

        $previousExpense = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', $previousMonth->month)
            ->whereYear('transaction_date', $previousMonth->year)
            ->sum('amount') ?? 0;

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

        // Get monthly budget (only if data exists)
        $this->monthlyBudget = $this->getMonthlyBudget();

        // Get financial goals (only if data exists)
        $this->financialGoals = $this->getFinancialGoals();
    }

    public function getMonthlyBudget()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $budgets = FinancialBudget::with('category')
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->limit(2)
            ->get();

        if ($budgets->isEmpty()) {
            return collect([]);
        }

        return $budgets->map(function ($budget) {
            return (object) [
                'id' => $budget->id,
                'category' => $budget->category,
                'budget' => $budget->used_amount,          // jumlah terpakai
                'monthly_budget' => $budget->amount,       // jumlah yang di-set
                'progress' => min($budget->percentage_used, 100),    // persen progress (max 100%)
            ];
        });
    }

    public function getFinancialGoals()
    {
        $goals = FinancialGoal::where('status', 'active')
            ->orderBy('target_date', 'asc')
            ->limit(2)
            ->get();

        if ($goals->isEmpty()) {
            return collect([]);
        }

        return $goals;
    }

    protected function getMonthlyData()
    {
        $months = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 7; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $income = FinancialTransaction::where('type', 'income')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount') ?? 0;
            
            $expense = FinancialTransaction::where('type', 'expense')
                ->whereMonth('transaction_date', $date->month)
                ->whereYear('transaction_date', $date->year)
                ->sum('amount') ?? 0;

            $months[] = $monthName;
            $incomeData[] = (float) $income;
            $expenseData[] = (float) $expense;
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
        ->orderBy('transactions_sum_amount', 'desc')
        ->get();

        if ($categories->isEmpty()) {
            return [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
        }

        $labels = $categories->pluck('name')->toArray();
        $data = $categories->pluck('transactions_sum_amount')->map(fn($v) => (float) $v)->toArray();

        $colors = [
            'rgb(59, 130, 246)',   // Blue
            'rgb(34, 197, 94)',    // Green
            'rgb(245, 158, 11)',   // Amber
            'rgb(168, 85, 247)',   // Purple
            'rgb(239, 68, 68)',    // Red
            'rgb(107, 114, 128)',  // Gray
            'rgb(236, 72, 153)',   // Pink
            'rgb(20, 184, 166)',   // Teal
            'rgb(251, 146, 60)',   // Orange
            'rgb(139, 92, 246)',   // Violet
        ];

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    protected function getLatestTransactions()
    {
        $transactions = FinancialTransaction::with(['category', 'account'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        if ($transactions->isEmpty()) {
            return collect([]);
        }

        return $transactions->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'date' => $transaction->transaction_date->format('d M Y'),
                'category' => $transaction->category->name ?? 'Uncategorized',
                'account' => $transaction->account->name ?? 'Unknown Account',
            ];
        });
    }

    /**
     * Refresh data when needed
     */
    public function refreshData()
    {
        $this->loadFinancialData();
        $this->dispatch('charts-updated', [
            'monthlyData' => $this->monthlyData,
            'categoryData' => $this->categoryData
        ]);
    }

    /**
     * Get summary statistics for display
     */
    public function getSummaryStats()
    {
        return [
            'total_accounts' => FinancialAccount::count(),
            'active_budgets' => FinancialBudget::where('month', Carbon::now()->month)
                                             ->where('year', Carbon::now()->year)
                                             ->count(),
            'active_goals' => FinancialGoal::where('status', 'active')->count(),
            'total_transactions' => FinancialTransaction::whereMonth('transaction_date', Carbon::now()->month)
                                                      ->whereYear('transaction_date', Carbon::now()->year)
                                                      ->count(),
        ];
    }

    /**
     * Check if user has any financial data
     */
    public function hasAnyData()
    {
        return FinancialTransaction::exists() || 
               FinancialAccount::exists() || 
               FinancialBudget::exists() || 
               FinancialGoal::exists();
    }

    /**
     * Get personalized insights
     */
    public function getInsights()
    {
        $insights = [];
        
        // Budget insights
        if (collect($this->monthlyBudget)->isNotEmpty()) {
            $overBudget = collect($this->monthlyBudget)->where('progress', '>', 100);
            if ($overBudget->isNotEmpty()) {
                $insights[] = [
                    'type' => 'warning',
                    'title' => 'Budget Alert',
                    'message' => 'You have ' . $overBudget->count() . ' categories over budget this month.'
                ];
            }
        }

        // Spending insights
        if ($this->expenseChange > 20) {
            $insights[] = [
                'type' => 'warning',
                'title' => 'Spending Increase',
                'message' => "Your expenses increased by {$this->expenseChange}% compared to last month."
            ];
        } elseif ($this->expenseChange < -10) {
            $insights[] = [
                'type' => 'success',
                'title' => 'Great Savings!',
                'message' => "You reduced your expenses by " . abs($this->expenseChange) . "% this month."
            ];
        }

        // Income insights
        if ($this->incomeChange > 10) {
            $insights[] = [
                'type' => 'success',
                'title' => 'Income Growth',
                'message' => "Your income increased by {$this->incomeChange}% this month. Great job!"
            ];
        }

        // Goal insights
        if (collect($this->financialGoals)->isNotEmpty()) {
            $achievedGoals = collect($this->financialGoals)->where('progress_percentage', '>=', 100);
            if ($achievedGoals->isNotEmpty()) {
                $insights[] = [
                    'type' => 'success',
                    'title' => 'Goals Achieved!',
                    'message' => 'Congratulations! You have achieved ' . $achievedGoals->count() . ' financial goals.'
                ];
            }
        }

        return $insights;
    }

    public function render()
    {
        return view('livewire.financial.financial-page', [
            'summaryStats' => $this->getSummaryStats(),
            'insights' => $this->getInsights(),
            'hasData' => $this->hasAnyData(),
        ]);
    }
}