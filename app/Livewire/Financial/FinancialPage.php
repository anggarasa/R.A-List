<?php

namespace App\Livewire\Financial;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        // Use caching for expensive operations
        $cacheKey = 'financial_dashboard_' . Carbon::now()->format('Y-m-d-H');
        
        $data = Cache::remember($cacheKey, 3600, function () {
            return $this->loadFinancialDataFromDatabase();
        });

        // Assign cached data to properties
        $this->totalBalance = $data['totalBalance'];
        $this->totalIncome = $data['totalIncome'];
        $this->totalExpense = $data['totalExpense'];
        $this->netBalance = $data['netBalance'];
        $this->incomeChange = $data['incomeChange'];
        $this->expenseChange = $data['expenseChange'];
        $this->monthlyData = $data['monthlyData'];
        $this->categoryData = $data['categoryData'];
        $this->latestTransactions = $data['latestTransactions'];
        $this->monthlyBudget = $data['monthlyBudget'];
        $this->financialGoals = $data['financialGoals'];
    }

    protected function loadFinancialDataFromDatabase()
    {
        // Get current month and previous month
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        // Optimize: Get all financial data in fewer queries
        $this->totalBalance = FinancialAccount::sum('balance') ?? 0;

        // Optimize: Get current and previous month data in single query
        $monthlyTotals = $this->getMonthlyTotalsOptimized($currentMonth, $previousMonth);
        
        $this->totalIncome = $monthlyTotals['current_income'];
        $this->totalExpense = $monthlyTotals['current_expense'];
        $previousIncome = $monthlyTotals['previous_income'];
        $previousExpense = $monthlyTotals['previous_expense'];

        // Calculate percentage changes
        $incomeChange = $previousIncome > 0 
            ? round((($this->totalIncome - $previousIncome) / $previousIncome) * 100, 1)
            : 0;

        $expenseChange = $previousExpense > 0 
            ? round((($this->totalExpense - $previousExpense) / $previousExpense) * 100, 1)
            : 0;

        // Net balance is income minus expenses for current month
        $netBalance = $this->totalIncome - $this->totalExpense;

        return [
            'totalBalance' => $this->totalBalance,
            'totalIncome' => $this->totalIncome,
            'totalExpense' => $this->totalExpense,
            'netBalance' => $netBalance,
            'incomeChange' => $incomeChange,
            'expenseChange' => $expenseChange,
            'monthlyData' => $this->getMonthlyDataOptimized(),
            'categoryData' => $this->getCategoryDataOptimized(),
            'latestTransactions' => $this->getLatestTransactionsOptimized(),
            'monthlyBudget' => $this->getMonthlyBudgetOptimized(),
            'financialGoals' => $this->getFinancialGoalsOptimized(),
        ];
    }

    /**
     * Optimized method to get monthly totals in single query
     */
    protected function getMonthlyTotalsOptimized($currentMonth, $previousMonth)
    {
        $totals = DB::table('financial_transactions')
            ->select([
                DB::raw('SUM(CASE WHEN type = "income" AND MONTH(transaction_date) = ? AND YEAR(transaction_date) = ? THEN amount ELSE 0 END) as current_income'),
                DB::raw('SUM(CASE WHEN type = "expense" AND MONTH(transaction_date) = ? AND YEAR(transaction_date) = ? THEN amount ELSE 0 END) as current_expense'),
                DB::raw('SUM(CASE WHEN type = "income" AND MONTH(transaction_date) = ? AND YEAR(transaction_date) = ? THEN amount ELSE 0 END) as previous_income'),
                DB::raw('SUM(CASE WHEN type = "expense" AND MONTH(transaction_date) = ? AND YEAR(transaction_date) = ? THEN amount ELSE 0 END) as previous_expense'),
            ])
            ->setBindings([
                $currentMonth->month, $currentMonth->year,
                $currentMonth->month, $currentMonth->year,
                $previousMonth->month, $previousMonth->year,
                $previousMonth->month, $previousMonth->year,
            ])
            ->first();

        return [
            'current_income' => (float) ($totals->current_income ?? 0),
            'current_expense' => (float) ($totals->current_expense ?? 0),
            'previous_income' => (float) ($totals->previous_income ?? 0),
            'previous_expense' => (float) ($totals->previous_expense ?? 0),
        ];
    }

    /**
     * Optimized monthly budget with pre-calculated used amounts
     */
    protected function getMonthlyBudgetOptimized()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Get budgets with pre-calculated used amounts to prevent N+1
        $budgets = DB::table('financial_budgets as fb')
            ->join('financial_categories as fc', 'fb.financial_category_id', '=', 'fc.id')
            ->leftJoin('financial_transactions as ft', function($join) use ($currentMonth, $currentYear) {
                $join->on('fc.id', '=', 'ft.financial_category_id')
                     ->where('ft.type', '=', 'expense')
                     ->whereMonth('ft.transaction_date', $currentMonth)
                     ->whereYear('ft.transaction_date', $currentYear);
            })
            ->select([
                'fb.id',
                'fb.amount as monthly_budget',
                'fc.name as category_name',
                DB::raw('COALESCE(SUM(ft.amount), 0) as used_amount')
            ])
            ->where('fb.month', $currentMonth)
            ->where('fb.year', $currentYear)
            ->groupBy('fb.id', 'fb.amount', 'fc.name')
            ->limit(2)
            ->get();

        if ($budgets->isEmpty()) {
            return collect([]);
        }

        return $budgets->map(function ($budget) {
            $progress = $budget->monthly_budget > 0 
                ? min(($budget->used_amount / $budget->monthly_budget) * 100, 100)
                : 0;

            return (object) [
                'id' => $budget->id,
                'category' => (object) ['name' => $budget->category_name],
                'budget' => (float) $budget->used_amount,
                'monthly_budget' => (float) $budget->monthly_budget,
                'progress' => $progress,
            ];
        });
    }

    public function getMonthlyBudget()
    {
        return $this->getMonthlyBudgetOptimized();
    }

    /**
     * Optimized financial goals method
     */
    protected function getFinancialGoalsOptimized()
    {
        $goals = FinancialGoal::where('status', 'active')
            ->orderBy('target_date', 'asc')
            ->limit(2)
            ->get();

        return $goals->isEmpty() ? collect([]) : $goals;
    }

    public function getFinancialGoals()
    {
        return $this->getFinancialGoalsOptimized();
    }

    /**
     * Optimized monthly data with single query for all months
     */
    protected function getMonthlyDataOptimized()
    {
        // Generate date range for last 8 months
        $dates = [];
        for ($i = 7; $i >= 0; $i--) {
            $dates[] = Carbon::now()->subMonths($i);
        }

        // Build date conditions for better MySQL compatibility
        $dateConditions = [];
        foreach ($dates as $date) {
            $dateConditions[] = [
                'year' => $date->year,
                'month' => $date->month
            ];
        }

        // Get all monthly data in single query with better compatibility
        $monthlyData = DB::table('financial_transactions')
            ->select([
                DB::raw('MONTH(transaction_date) as month'),
                DB::raw('YEAR(transaction_date) as year'),
                'type',
                DB::raw('SUM(amount) as total')
            ])
            ->where(function ($query) use ($dateConditions) {
                foreach ($dateConditions as $condition) {
                    $query->orWhere(function ($q) use ($condition) {
                        $q->whereYear('transaction_date', $condition['year'])
                          ->whereMonth('transaction_date', $condition['month']);
                    });
                }
            })
            ->groupBy('month', 'year', 'type')
            ->get()
            ->groupBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        $months = [];
        $incomeData = [];
        $expenseData = [];

        foreach ($dates as $date) {
            $monthName = $date->format('M Y');
            $key = $date->format('Y-n');
            
            $monthTransactions = $monthlyData->get($key, collect());
            
            $income = $monthTransactions->where('type', 'income')->first()->total ?? 0;
            $expense = $monthTransactions->where('type', 'expense')->first()->total ?? 0;

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

    protected function getMonthlyData()
    {
        return $this->getMonthlyDataOptimized();
    }

    /**
     * Optimized category data with single query
     */
    protected function getCategoryDataOptimized()
    {
        $currentMonth = Carbon::now();

        // Use single query with join instead of withSum to prevent N+1
        $categories = DB::table('financial_categories as fc')
            ->leftJoin('financial_transactions as ft', function($join) use ($currentMonth) {
                $join->on('fc.id', '=', 'ft.financial_category_id')
                     ->where('ft.type', '=', 'expense')
                     ->whereMonth('ft.transaction_date', $currentMonth->month)
                     ->whereYear('ft.transaction_date', $currentMonth->year);
            })
            ->select([
                'fc.name',
                DB::raw('COALESCE(SUM(ft.amount), 0) as total_amount')
            ])
            ->groupBy('fc.id', 'fc.name')
            ->having('total_amount', '>', 0)
            ->orderBy('total_amount', 'desc')
            ->get();

        if ($categories->isEmpty()) {
            return [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
        }

        $labels = $categories->pluck('name')->toArray();
        $data = $categories->pluck('total_amount')->map(fn($v) => (float) $v)->toArray();

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

    protected function getCategoryData()
    {
        return $this->getCategoryDataOptimized();
    }

    /**
     * Optimized latest transactions with proper eager loading
     */
    protected function getLatestTransactionsOptimized()
    {
        $transactions = FinancialTransaction::with(['category:id,name', 'account:id,name'])
            ->select(['id', 'description', 'amount', 'type', 'transaction_date', 'financial_category_id', 'financial_account_id'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
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

    protected function getLatestTransactions()
    {
        return $this->getLatestTransactionsOptimized();
    }

    /**
     * Refresh data when needed - clears cache and reloads
     */
    public function refreshData()
    {
        // Clear cache to force fresh data
        $this->clearCache();
        
        $this->loadFinancialData();
        $this->dispatch('charts-updated', [
            'monthlyData' => $this->monthlyData,
            'categoryData' => $this->categoryData
        ]);
    }

    /**
     * Clear dashboard cache
     */
    protected function clearCache()
    {
        $cacheKey = 'financial_dashboard_' . Carbon::now()->format('Y-m-d-H');
        Cache::forget($cacheKey);
    }

    /**
     * Optimized summary statistics with separate queries for better compatibility
     */
    public function getSummaryStats()
    {
        $currentMonth = Carbon::now();
        
        // Use separate queries for better MySQL compatibility
        $totalAccounts = DB::table('financial_accounts')->count();
        
        $activeBudgets = DB::table('financial_budgets')
            ->where('month', $currentMonth->month)
            ->where('year', $currentMonth->year)
            ->count();
        
        $activeGoals = DB::table('financial_goals')
            ->where('status', 'active')
            ->count();
        
        $totalTransactions = DB::table('financial_transactions')
            ->whereMonth('transaction_date', $currentMonth->month)
            ->whereYear('transaction_date', $currentMonth->year)
            ->count();

        return [
            'total_accounts' => $totalAccounts,
            'active_budgets' => $activeBudgets,
            'active_goals' => $activeGoals,
            'total_transactions' => $totalTransactions,
        ];
    }

    /**
     * Optimized check for any financial data
     */
    public function hasAnyData()
    {
        try {
            // Use EXISTS queries which are more efficient and compatible
            $hasTransactions = DB::table('financial_transactions')->exists();
            $hasAccounts = DB::table('financial_accounts')->exists();
            $hasBudgets = DB::table('financial_budgets')->exists();
            $hasGoals = DB::table('financial_goals')->exists();

            return $hasTransactions || $hasAccounts || $hasBudgets || $hasGoals;
        } catch (\Exception $e) {
            // Fallback to simple count queries if EXISTS fails
            $hasTransactions = DB::table('financial_transactions')->count() > 0;
            $hasAccounts = DB::table('financial_accounts')->count() > 0;
            $hasBudgets = DB::table('financial_budgets')->count() > 0;
            $hasGoals = DB::table('financial_goals')->count() > 0;

            return $hasTransactions || $hasAccounts || $hasBudgets || $hasGoals;
        }
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

    /**
     * Clear cache when financial data is updated
     * This should be called from other components when data changes
     */
    public static function clearDashboardCache()
    {
        $cacheKey = 'financial_dashboard_' . Carbon::now()->format('Y-m-d-H');
        Cache::forget($cacheKey);
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