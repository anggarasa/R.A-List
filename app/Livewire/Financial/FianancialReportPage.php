<?php

namespace App\Livewire\Financial;

use Livewire\Component;
use App\Models\financial\FinancialTransaction;
use App\Models\financial\FinancialAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

use function PHPSTORM_META\type;

class FianancialReportPage extends Component
{
    public $selectedPeriod = 'this_month';
    public $customStartDate;
    public $customEndDate;
    public $chartPeriod = '6months';
    
    // Cache for preventing unnecessary recalculations
    private $cachedSummary = null;
    private $cachedTransactions = null;
    private $cachedCategoryBreakdown = null;
    private $cachedChartData = null;
    private $lastCacheKey = null;
    
    public function mount()
    {
        $this->customStartDate = now()->startOfMonth()->format('Y-m-d');
        $this->customEndDate = now()->endOfMonth()->format('Y-m-d');
    }

    /**
     * Generate cache key for current state
     */
    private function getCacheKey(): string
    {
        return md5(json_encode([
            'period' => $this->selectedPeriod,
            'start' => $this->customStartDate,
            'end' => $this->customEndDate,
            'chart_period' => $this->chartPeriod
        ]));
    }

    /**
     * Clear cache when parameters change
     */
    private function clearCache()
    {
        $this->cachedSummary = null;
        $this->cachedTransactions = null;
        $this->cachedCategoryBreakdown = null;
        $this->cachedChartData = null;
        $this->lastCacheKey = null;
    }

    /**
     * Check if cache needs to be invalidated
     */
    private function shouldClearCache(): bool
    {
        $currentKey = $this->getCacheKey();
        if ($this->lastCacheKey !== $currentKey) {
            $this->lastCacheKey = $currentKey;
            return true;
        }
        return false;
    }

    public function updatedSelectedPeriod()
    {
        $this->clearCache();
        $this->dispatchChartUpdate();
    }

    public function updatedChartPeriod()
    {
        $this->clearCache();
        $this->dispatchChartUpdate();
    }

    public function updatedCustomStartDate()
    {
        if ($this->selectedPeriod === 'custom') {
            $this->clearCache();
            $this->dispatchChartUpdate();
        }
    }

    public function updatedCustomEndDate()
    {
        if ($this->selectedPeriod === 'custom') {
            $this->clearCache();
            $this->dispatchChartUpdate();
        }
    }

    /**
     * Dispatch chart update event with proper data
     */
    private function dispatchChartUpdate()
    {
        try {
            $chartData = $this->getChartData();
            $categoryBreakdown = $this->getCategoryBreakdown();
            
            // Log untuk debugging
            Log::info('Dispatching chart update', [
                'chart_data_count' => count($chartData),
                'category_count' => $categoryBreakdown->count()
            ]);

            $this->dispatch('updateChart', [
                'chartData' => $chartData,
                'categoryBreakdown' => $categoryBreakdown->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Error dispatching chart update: ' . $e->getMessage());
            
            // Dispatch empty data to prevent JS errors
            $this->dispatch('updateChart', [
                'chartData' => [],
                'categoryBreakdown' => []
            ]);
        }
    }

    /**
     * Get date range based on selected period
     */
    private function getDateRange(): array
    {
        try {
            switch ($this->selectedPeriod) {
                case 'this_month':
                    return [
                        now()->startOfMonth(),
                        now()->endOfMonth()
                    ];
                case 'last_month':
                    return [
                        now()->subMonth()->startOfMonth(),
                        now()->subMonth()->endOfMonth()
                    ];
                case 'this_year':
                    return [
                        now()->startOfYear(),
                        now()->endOfYear()
                    ];
                case 'custom':
                    $start = $this->customStartDate ? Carbon::parse($this->customStartDate) : now()->startOfMonth();
                    $end = $this->customEndDate ? Carbon::parse($this->customEndDate) : now()->endOfMonth();
                    
                    // Ensure start date is before end date
                    if ($start->gt($end)) {
                        $temp = $start;
                        $start = $end;
                        $end = $temp;
                    }
                    
                    return [$start, $end];
                default:
                    return [
                        now()->startOfMonth(),
                        now()->endOfMonth()
                    ];
            }
        } catch (\Exception $e) {
            Log::error('Error getting date range: ' . $e->getMessage());
            return [
                now()->startOfMonth(),
                now()->endOfMonth()
            ];
        }
    }

    /**
     * Get previous period date range for comparison
     */
    private function getPreviousDateRange(): array
    {
        try {
            [$start, $end] = $this->getDateRange();
            $diff = $start->diffInDays($end);
            
            return [
                $start->copy()->subDays($diff + 1),
                $start->copy()->subDay()
            ];
        } catch (\Exception $e) {
            Log::error('Error getting previous date range: ' . $e->getMessage());
            return [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ];
        }
    }

    /**
     * Get summary data with caching
     */
    public function getSummaryData(): array
    {
        if ($this->shouldClearCache()) {
            $this->clearCache();
        }

        if ($this->cachedSummary !== null) {
            return $this->cachedSummary;
        }

        [$startDate, $endDate] = $this->getDateRange();
        [$prevStartDate, $prevEndDate] = $this->getPreviousDateRange();

        try {
            // Current period data with null coalescing
            $currentIncome = (float) FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $currentExpenses = (float) FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            // Previous period data for comparison
            $previousIncome = (float) FinancialTransaction::where('type', 'income')
                ->whereBetween('transaction_date', [$prevStartDate, $prevEndDate])
                ->sum('amount');

            $previousExpenses = (float) FinancialTransaction::where('type', 'expense')
                ->whereBetween('transaction_date', [$prevStartDate, $prevEndDate])
                ->sum('amount');

            // Calculate percentage changes safely
            $incomeChange = $previousIncome > 0 
                ? (($currentIncome - $previousIncome) / $previousIncome) * 100 
                : ($currentIncome > 0 ? 100 : 0);

            $expenseChange = $previousExpenses > 0 
                ? (($currentExpenses - $previousExpenses) / $previousExpenses) * 100 
                : ($currentExpenses > 0 ? 100 : 0);

            $totalBalance = (float) FinancialAccount::sum('balance');
            
            $savingsRate = $currentIncome > 0 
                ? (($currentIncome - $currentExpenses) / $currentIncome) * 100 
                : 0;

            $this->cachedSummary = [
                'total_balance' => $totalBalance,
                'total_income' => $currentIncome,
                'total_expenses' => $currentExpenses,
                'income_change' => round($incomeChange, 1),
                'expense_change' => round($expenseChange, 1),
                'savings_rate' => round($savingsRate, 1),
                'net_savings' => $currentIncome - $currentExpenses
            ];

        } catch (\Exception $e) {
            Log::error('Error calculating summary data: ' . $e->getMessage());
            
            $this->cachedSummary = [
                'total_balance' => 0,
                'total_income' => 0,
                'total_expenses' => 0,
                'income_change' => 0,
                'expense_change' => 0,
                'savings_rate' => 0,
                'net_savings' => 0
            ];
        }

        return $this->cachedSummary;
    }

    /**
     * Get chart data with caching
     */
    public function getChartData(): array
    {
        if ($this->shouldClearCache()) {
            $this->clearCache();
        }

        if ($this->cachedChartData !== null) {
            return $this->cachedChartData;
        }

        try {
            $months = $this->chartPeriod === '12months' ? 12 : 6;
            $data = [];
            
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();

                // Use subqueries for better performance
                $income = (float) FinancialTransaction::where('type', 'income')
                    ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                    ->sum('amount');

                $expenses = (float) FinancialTransaction::where('type', 'expense')
                    ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
                    ->sum('amount');

                $data[] = [
                    'month' => $date->format('M Y'),
                    'income' => $income,
                    'expenses' => $expenses
                ];
            }

            $this->cachedChartData = $data;

        } catch (\Exception $e) {
            Log::error('Error getting chart data: ' . $e->getMessage());
            $this->cachedChartData = [];
        }

        return $this->cachedChartData;
    }

    /**
     * Get recent transactions with caching
     */
    public function getRecentTransactions(): Collection
    {
        if ($this->shouldClearCache()) {
            $this->clearCache();
        }

        if ($this->cachedTransactions !== null) {
            return $this->cachedTransactions;
        }

        try {
            [$startDate, $endDate] = $this->getDateRange();
            
            $this->cachedTransactions = FinancialTransaction::with(['category', 'account'])
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

        } catch (\Exception $e) {
            Log::error('Error getting recent transactions: ' . $e->getMessage());
            $this->cachedTransactions = collect([]);
        }

        return $this->cachedTransactions;
    }

    /**
     * Get category breakdown with caching
     */
    public function getCategoryBreakdown(): Collection
    {
        if ($this->shouldClearCache()) {
            $this->clearCache();
        }

        if ($this->cachedCategoryBreakdown !== null) {
            return $this->cachedCategoryBreakdown;
        }

        try {
            [$startDate, $endDate] = $this->getDateRange();

            $breakdown = FinancialTransaction::select('financial_category_id')
                ->selectRaw('SUM(amount) as total_amount')
                ->with(['category' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('type', 'expense')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->groupBy('financial_category_id')
                ->having('total_amount', '>', 0)
                ->orderBy('total_amount', 'desc')
                ->get();

            // Transform data to ensure consistent structure
            $this->cachedCategoryBreakdown = $breakdown->map(function ($item) {
                return [
                    'category' => [
                        'name' => $item->category->name ?? 'Uncategorized'
                    ],
                    'total_amount' => (float) $item->total_amount
                ];
            });

        } catch (\Exception $e) {
            Log::error('Error getting category breakdown: ' . $e->getMessage());
            $this->cachedCategoryBreakdown = collect([]);
        }

        return $this->cachedCategoryBreakdown;
    }

    /**
     * Export report functionality
     */
    public function exportReport()
    {
        try {
            // Get current data
            // $summary = $this->getSummaryData();
            // $transactions = $this->getRecentTransactions();
            // $categoryBreakdown = $this->getCategoryBreakdown();
            
            // For now, just show success message
            // You can implement actual export logic here (CSV, PDF, etc.)
            
            $this->dispatch('notification', type: 'info', message: 'Export report not implemented yet.');

            // Log::info('Financial report exported', [
            //     'period' => $this->selectedPeriod,
            //     'transactions_count' => $transactions->count(),
            //     'categories_count' => $categoryBreakdown->count()
            // ]);
            
        } catch (\Exception $e) {
            Log::error('Export report error: ' . $e->getMessage());
            
            $this->dispatch('notification', type: 'error', message: 'Failed to export report. Please try again.');
        }
    }

    /**
     * Main render method
     */
    public function render()
    {
        try {
            $summary = $this->getSummaryData();
            $transactions = $this->getRecentTransactions();
            $categoryBreakdown = $this->getCategoryBreakdown();
            $chartData = $this->getChartData();

            // Log current state for debugging
            Log::debug('Financial report rendered', [
                'period' => $this->selectedPeriod,
                'chart_period' => $this->chartPeriod,
                'summary_income' => $summary['total_income'],
                'chart_data_points' => count($chartData),
                'category_count' => $categoryBreakdown->count()
            ]);

            return view('livewire.financial.fianancial-report-page', compact(
                'summary',
                'transactions',
                'categoryBreakdown',
                'chartData'
            ));

        } catch (\Exception $e) {
            Log::error('Error rendering financial report: ' . $e->getMessage());
            
            // Return with empty/default data to prevent page crash
            return view('livewire.financial.fianancial-report-page', [
                'summary' => [
                    'total_balance' => 0,
                    'total_income' => 0,
                    'total_expenses' => 0,
                    'income_change' => 0,
                    'expense_change' => 0,
                    'savings_rate' => 0,
                    'net_savings' => 0
                ],
                'transactions' => collect([]),
                'categoryBreakdown' => collect([]),
                'chartData' => []
            ]);
        }
    }
}