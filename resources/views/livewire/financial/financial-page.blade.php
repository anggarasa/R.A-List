<div class="min-h-screen bg-gray-50 dark:bg-zinc-900" wire:ignore.self>
    <!-- Header Section -->
    <div class="bg-white dark:bg-zinc-800 border-b border-gray-200 dark:border-zinc-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="xl" class="text-gray-900 dark:text-white font-bold">
                        Financial Dashboard
                    </flux:heading>
                    <flux:text class="text-gray-600 dark:text-gray-300 mt-2">
                        Monitor your financial health and track your progress
                    </flux:text>
                </div>
                <div class="flex items-center space-x-3">
                    <flux:button icon="arrow-path" variant="outline" size="sm" wire:click="refreshData">
                        Refresh
                    </flux:button>
                    <flux:button icon="plus" variant="primary" size="sm" wire:navigate
                        href="{{ route('financial.transaction') }}">
                        Add Transaction
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Balance Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <flux:icon.wallet class="text-blue-600 dark:text-blue-400 w-6 h-6" />
                    </div>
                    <div class="text-right">
                        <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mb-1">Total Balance</flux:text>
                        <flux:heading size="lg" class="text-gray-900 dark:text-white font-bold">
                            {{ format_rupiah($totalBalance) }}
                        </flux:heading>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <flux:text size="xs" class="text-gray-500 dark:text-gray-400">
                        @if($totalBalance > 0)
                        Across all accounts
                        @else
                        <span class="text-amber-600 dark:text-amber-400">No balance recorded</span>
                        @endif
                    </flux:text>
                    @if($totalBalance > 0)
                    <div class="flex items-center text-green-600 dark:text-green-400">
                        <flux:icon.arrow-up class="w-4 h-4 mr-1" />
                        <flux:text size="xs">Active</flux:text>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Income Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <flux:icon.arrow-up class="text-green-600 dark:text-green-400 w-6 h-6" />
                    </div>
                    <div class="text-right">
                        <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mb-1">This Month's Income
                        </flux:text>
                        <flux:heading size="lg" class="text-gray-900 dark:text-white font-bold">
                            {{ format_rupiah($totalIncome) }}
                        </flux:heading>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    @if($totalIncome > 0)
                    <flux:text size="xs" class="text-gray-500 dark:text-gray-400">
                        @if($incomeChange >= 0)
                        <span class="text-green-600 dark:text-green-400">+{{ $incomeChange }}% from last month</span>
                        @else
                        <span class="text-red-600 dark:text-red-400">{{ $incomeChange }}% from last month</span>
                        @endif
                    </flux:text>
                    @else
                    <flux:text size="xs" class="text-gray-400 dark:text-gray-500">
                        No income recorded
                    </flux:text>
                    @endif
                    @if($incomeChange > 0)
                    <div class="flex items-center text-green-600 dark:text-green-400">
                        <flux:icon.arrow-trending-up class="w-4 h-4 mr-1" />
                        <flux:text size="xs">Growing</flux:text>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Expenses Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <flux:icon.arrow-down class="text-red-600 dark:text-red-400 w-6 h-6" />
                    </div>
                    <div class="text-right">
                        <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mb-1">This Month's Expenses
                        </flux:text>
                        <flux:heading size="lg" class="text-gray-900 dark:text-white font-bold">
                            {{ format_rupiah($totalExpense) }}
                        </flux:heading>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    @if($totalExpense > 0)
                    <flux:text size="xs" class="text-gray-500 dark:text-gray-400">
                        @if($expenseChange <= 0) <span class="text-green-600 dark:text-green-400">{{ abs($expenseChange)
                            }}% reduction</span>
                            @else
                            <span class="text-red-600 dark:text-red-400">+{{ $expenseChange }}% increase</span>
                            @endif
                    </flux:text>
                    @else
                    <flux:text size="xs" class="text-gray-400 dark:text-gray-500">
                        No expenses recorded
                    </flux:text>
                    @endif
                    @if($expenseChange < 0) <div class="flex items-center text-green-600 dark:text-green-400">
                        <flux:icon.arrow-trending-down class="w-4 h-4 mr-1" />
                        <flux:text size="xs">Saving</flux:text>
                </div>
                @endif
            </div>
        </div>

        <!-- Net Balance Card -->
        <div
            class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 {{ $netBalance >= 0 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20' }} rounded-lg">
                    <flux:icon.currency-dollar
                        class="{{ $netBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} w-6 h-6" />
                </div>
                <div class="text-right">
                    <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mb-1">Net Balance</flux:text>
                    <flux:heading size="lg"
                        class="font-bold {{ $netBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ format_rupiah($netBalance) }}
                    </flux:heading>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <flux:text size="xs" class="text-gray-500 dark:text-gray-400">
                    @if($netBalance > 0)
                    <span class="text-green-600 dark:text-green-400">Surplus this month</span>
                    @elseif($netBalance < 0) <span class="text-red-600 dark:text-red-400">Deficit this month</span>
                        @else
                        Break even this month
                        @endif
                </flux:text>
                @if($netBalance > 0)
                <div class="flex items-center text-green-600 dark:text-green-400">
                    <flux:icon.check-circle class="w-4 h-4 mr-1" />
                    <flux:text size="xs">Positive</flux:text>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Income vs Expenses Chart -->
        <div
            class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <flux:heading size="lg" class="text-gray-900 dark:text-white font-semibold">
                        Income vs Expenses Trend
                    </flux:heading>
                    <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mt-1">
                        Monthly comparison over the last 8 months
                    </flux:text>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <flux:text size="xs" class="text-gray-600 dark:text-gray-300">Income</flux:text>
                    </div>
                    <div class="flex items-center space-x-1">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <flux:text size="xs" class="text-gray-600 dark:text-gray-300">Expenses</flux:text>
                    </div>
                </div>
            </div>

            @if(array_sum($monthlyData['income']) > 0 || array_sum($monthlyData['expenses']) > 0)
            <div class="relative h-80" wire:ignore>
                <canvas id="incomeExpenseChart"></canvas>
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-80 text-gray-400 dark:text-gray-500">
                <div class="p-4 bg-gray-100 dark:bg-zinc-700 rounded-full mb-4">
                    <flux:icon.chart-bar class="w-12 h-12 text-gray-400 dark:text-gray-500" />
                </div>
                <flux:heading size="lg" class="text-gray-600 dark:text-gray-400 mb-2">
                    No Transaction Data
                </flux:heading>
                <flux:text class="text-center text-gray-500 dark:text-gray-400 mb-4">
                    Start tracking your income and expenses to see trends here
                </flux:text>
                <flux:button variant="outline" size="sm" wire:navigate href="{{ route('financial.transaction') }}">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Add First Transaction
                </flux:button>
            </div>
            @endif
        </div>

        <!-- Expense Categories Chart -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="mb-6">
                <flux:heading size="lg" class="text-gray-900 dark:text-white font-semibold mb-2">
                    Expense Categories
                </flux:heading>
                <flux:text size="sm" class="text-gray-500 dark:text-gray-400">
                    This month's spending breakdown
                </flux:text>
            </div>

            @if(count($categoryData['labels']) > 0)
            <div class="relative h-64 mb-4" wire:ignore>
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="space-y-2">
                @foreach($categoryData['labels'] as $index => $label)
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 rounded-full"
                            style="background-color: {{ $categoryData['colors'][$index] }}"></div>
                        <flux:text size="sm" class="text-gray-600 dark:text-gray-300">{{ $label }}</flux:text>
                    </div>
                    <flux:text size="sm" class="font-medium text-gray-900 dark:text-white">
                        {{ format_rupiah($categoryData['data'][$index]) }}
                    </flux:text>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-64 text-gray-400 dark:text-gray-500">
                <div class="p-3 bg-gray-100 dark:bg-zinc-700 rounded-full mb-3">
                    <flux:icon.chart-pie class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                </div>
                <flux:heading size="sm" class="text-gray-600 dark:text-gray-400 mb-2">
                    No Category Data
                </flux:heading>
                <flux:text size="sm" class="text-center text-gray-500 dark:text-gray-400">
                    Add expense transactions to see category breakdown
                </flux:text>
            </div>
            @endif
        </div>
    </div>

    <!-- Budget & Goals Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Monthly Budget -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <flux:heading size="lg" class="text-gray-900 dark:text-white font-semibold">
                        Monthly Budget
                    </flux:heading>
                    <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mt-1">
                        {{ now()->format('F Y') }} spending limits
                    </flux:text>
                </div>
                <flux:button variant="outline" size="sm" wire:navigate href="{{ route('financial.budget') }}">
                    <flux:icon.cog-6-tooth class="w-4 h-4 mr-2" />
                    Manage
                </flux:button>
            </div>

            @if($monthlyBudget && $monthlyBudget->count() > 0)
            <div class="space-y-4">
                @foreach ($monthlyBudget as $budget)
                <div class="p-4 bg-gray-50 dark:bg-zinc-700/50 rounded-lg border border-gray-200 dark:border-zinc-600">
                    <div class="flex justify-between items-center mb-3">
                        <flux:heading size="sm" class="text-gray-900 dark:text-white font-medium">
                            {{ $budget->category->name }}
                        </flux:heading>
                        <div class="flex items-center space-x-2">
                            <flux:text size="xs" class="font-medium @if($budget->progress < 70) text-green-600 dark:text-green-400
                                                   @elseif($budget->progress < 90) text-yellow-600 dark:text-yellow-400
                                                   @else text-red-600 dark:text-red-400 @endif">
                                {{ number_format($budget->progress, 1) }}%
                            </flux:text>
                            @if($budget->progress >= 100)
                            <flux:icon.exclamation-triangle class="w-4 h-4 text-red-500" />
                            @endif
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-zinc-600 rounded-full h-2 mb-3">
                        <div class="h-2 rounded-full transition-all duration-300
                                        @if($budget->progress < 70) bg-green-500
                                        @elseif($budget->progress < 90) bg-yellow-500
                                        @else bg-red-500 @endif" style="width: {{ min($budget->progress, 100) }}%">
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300">
                        <span>Used: {{ format_rupiah($budget->budget) }}</span>
                        <span>Budget: {{ format_rupiah($budget->monthly_budget) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-8 text-gray-400 dark:text-gray-500">
                <div class="p-3 bg-gray-100 dark:bg-zinc-700 rounded-full mb-3">
                    <flux:icon.calculator class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                </div>
                <flux:heading size="sm" class="text-gray-600 dark:text-gray-400 mb-2">
                    No Budget Set
                </flux:heading>
                <flux:text size="sm" class="text-center text-gray-500 dark:text-gray-400 mb-4">
                    Set monthly budgets to track your spending
                </flux:text>
                <flux:button variant="outline" size="sm" wire:navigate href="{{ route('financial.budget') }}">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Set Budget
                </flux:button>
            </div>
            @endif
        </div>

        <!-- Financial Goals -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <flux:heading size="lg" class="text-gray-900 dark:text-white font-semibold">
                        Financial Goals
                    </flux:heading>
                    <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mt-1">
                        {{ $financialGoals ? $financialGoals->count() : 0 }} active goals
                    </flux:text>
                </div>
                <flux:button variant="outline" size="sm" wire:navigate href="{{ route('financial.goal') }}">
                    <flux:icon.flag class="w-4 h-4 mr-2" />
                    Manage
                </flux:button>
            </div>

            @if($financialGoals && $financialGoals->count() > 0)
            <div class="space-y-4">
                @foreach($financialGoals as $goal)
                <div class="p-4 rounded-lg border @if($goal->progress_percentage >= 100) 
                                bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 
                                @elseif($goal->progress_percentage >= 75) 
                                bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 
                                @else 
                                bg-gray-50 dark:bg-zinc-700/50 border-gray-200 dark:border-zinc-600 
                                @endif">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <flux:heading size="sm" class="text-gray-900 dark:text-white font-medium">
                                {{ $goal->name }}
                            </flux:heading>
                            @if($goal->description)
                            <flux:text size="xs" class="text-gray-600 dark:text-gray-300 mt-1">
                                {{ $goal->description }}
                            </flux:text>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <flux:text size="xs" class="font-medium @if($goal->progress_percentage >= 100) 
                                            text-green-600 dark:text-green-400 
                                            @elseif($goal->progress_percentage >= 75) 
                                            text-blue-600 dark:text-blue-400 
                                            @else 
                                            text-gray-600 dark:text-gray-300 
                                            @endif">
                                {{ number_format($goal->progress_percentage, 1) }}%
                            </flux:text>
                            @if($goal->progress_percentage >= 100)
                            <flux:icon.check-circle class="w-4 h-4 text-green-500" />
                            @endif
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-zinc-600 rounded-full h-2 mb-3">
                        <div class="h-2 rounded-full transition-all duration-300 
                                        @if($goal->progress_percentage >= 100) bg-green-500 
                                        @elseif($goal->progress_percentage >= 75) bg-blue-500 
                                        @else bg-gray-400 @endif"
                            style="width: {{ min($goal->progress_percentage, 100) }}%">
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-300 mb-2">
                        <span>{{ format_rupiah($goal->current_amount) }}</span>
                        <span>{{ format_rupiah($goal->target_amount) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400">
                        <span>Target: {{ $goal->target_date->format('M Y') }}</span>
                        <span>{{ $goal->days_left_human }}</span>
                    </div>
                    @if($goal->progress_percentage >= 100)
                    <div
                        class="mt-3 p-2 bg-green-100 dark:bg-green-900/30 rounded text-xs text-green-700 dark:text-green-300 text-center">
                        🎉 Goal achieved!
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-8 text-gray-400 dark:text-gray-500">
                <div class="p-3 bg-gray-100 dark:bg-zinc-700 rounded-full mb-3">
                    <flux:icon.flag class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                </div>
                <flux:heading size="sm" class="text-gray-600 dark:text-gray-400 mb-2">
                    No Goals Set
                </flux:heading>
                <flux:text size="sm" class="text-center text-gray-500 dark:text-gray-400 mb-4">
                    Set financial goals to track your progress
                </flux:text>
                <flux:button variant="outline" size="sm" wire:navigate href="{{ route('financial.goal') }}">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Set Goal
                </flux:button>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <flux:heading size="lg" class="text-gray-900 dark:text-white font-semibold">
                        Recent Transactions
                    </flux:heading>
                    <flux:text size="sm" class="text-gray-500 dark:text-gray-400 mt-1">
                        Your latest financial activities
                    </flux:text>
                </div>
                <flux:button variant="outline" size="sm" wire:navigate href="{{ route('financial.transaction') }}">
                    <flux:icon.eye class="w-4 h-4 mr-2" />
                    View All
                </flux:button>
            </div>
        </div>

        <div class="p-6">
            @forelse($latestTransactions as $transaction)
            <div
                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-zinc-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700 transition-colors mb-3 last:mb-0">
                <div class="flex items-center space-x-4 flex-1 min-w-0">
                    <div class="p-3 rounded-full shrink-0
                                @if($transaction['type'] === 'income') bg-green-100 dark:bg-green-900/30
                                @else bg-red-100 dark:bg-red-900/30 @endif">
                        @if($transaction['type'] === 'income')
                        <flux:icon.arrow-up class="text-green-600 dark:text-green-400 w-5 h-5" />
                        @else
                        <flux:icon.arrow-down class="text-red-600 dark:text-red-400 w-5 h-5" />
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <flux:heading size="sm" class="text-gray-900 dark:text-white font-medium truncate">
                            {{ $transaction['description'] }}
                        </flux:heading>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span>{{ $transaction['date'] }}</span>
                            <span>•</span>
                            <span class="truncate">{{ $transaction['category'] }}</span>
                            <span>•</span>
                            <span class="capitalize font-medium
                                        @if($transaction['type'] === 'income') text-green-600 dark:text-green-400
                                        @else text-red-600 dark:text-red-400 @endif">
                                {{ $transaction['type'] }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <flux:heading size="sm"
                        class="font-bold
                                {{ $transaction['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $transaction['type'] === 'income' ? '+' : '-' }}{{ format_rupiah($transaction['amount']) }}
                    </flux:heading>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500">
                <div class="p-4 bg-gray-100 dark:bg-zinc-700 rounded-full mb-4">
                    <flux:icon.document-text class="w-12 h-12 text-gray-400 dark:text-gray-500" />
                </div>
                <flux:heading size="lg" class="text-gray-600 dark:text-gray-400 mb-2">
                    No Transactions Yet
                </flux:heading>
                <flux:text class="text-center text-gray-500 dark:text-gray-400 mb-4">
                    Start by adding your first income or expense transaction
                </flux:text>
                <flux:button variant="primary" size="sm" wire:navigate href="{{ route('financial.transaction') }}">
                    <flux:icon.plus class="w-4 h-4 mr-2" />
                    Add Transaction
                </flux:button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
        <div class="mb-6">
            <flux:heading size="lg" class="text-gray-900 dark:text-white font-semibold mb-2">
                Quick Actions
            </flux:heading>
            <flux:text size="sm" class="text-gray-500 dark:text-gray-400">
                Common financial management tasks
            </flux:text>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <flux:button variant="primary" size="sm" class="flex items-center justify-center space-x-2 h-12"
                wire:navigate href="{{ route('financial.transaction') }}">
                <flux:icon.plus class="w-5 h-5" />
                <span>Add Transaction</span>
            </flux:button>
            <flux:button variant="outline" size="sm" class="flex items-center justify-center space-x-2 h-12"
                wire:navigate href="{{ route('financial.budget') }}">
                <flux:icon.calculator class="w-5 h-5" />
                <span>Set Budget</span>
            </flux:button>
            <flux:button variant="outline" size="sm" class="flex items-center justify-center space-x-2 h-12"
                wire:navigate href="{{ route('financial.goal') }}">
                <flux:icon.flag class="w-5 h-5" />
                <span>Set Goals</span>
            </flux:button>
            <flux:button variant="outline" size="sm" class="flex items-center justify-center space-x-2 h-12"
                wire:navigate href="#">
                <flux:icon.document-text class="w-5 h-5" />
                <span>View Reports</span>
            </flux:button>
        </div>
    </div>
</div>
</div>

<!-- Charts Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                initializeCharts();
            }, 100);
        });

        function initializeCharts() {
            // Destroy existing charts if they exist
            if (window.incomeExpenseChart && typeof window.incomeExpenseChart.destroy === 'function') {
                window.incomeExpenseChart.destroy();
                window.incomeExpenseChart = null;
            }
            if (window.categoryChart && typeof window.categoryChart.destroy === 'function') {
                window.categoryChart.destroy();
                window.categoryChart = null;
            }

            const incomeExpenseCanvas = document.getElementById("incomeExpenseChart");
            const categoryCanvas = document.getElementById("categoryChart");

            function formatRupiah(value) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0
                }).format(value);
            }

            // Check if dark mode is enabled
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#e5e7eb' : '#374151';
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            // Income vs Expense Chart
            if (incomeExpenseCanvas) {
                const ctx1 = incomeExpenseCanvas.getContext("2d");
                const incomeData = @json($monthlyData['income']);
                const expenseData = @json($monthlyData['expenses']);
                
                // Check if we have any data
                if (incomeData.some(val => val > 0) || expenseData.some(val => val > 0)) {
                    const allValues = [...incomeData, ...expenseData];
                    const minValue = Math.min(...allValues);
                    const maxValue = Math.max(...allValues);

                    let paddedMin = minValue - 500000;
                    let paddedMax = maxValue + 500000;

                    if (paddedMin < 0) paddedMin = 0;

                    const suggestedMin = Math.floor(paddedMin / 1000000) * 1000000;
                    const suggestedMax = Math.ceil(paddedMax / 1000000) * 1000000;

                    window.incomeExpenseChart = new Chart(ctx1, {
                        type: "bar",
                        data: {
                            labels: @json($monthlyData['labels']),
                            datasets: [
                                {
                                    label: "Income",
                                    data: incomeData,
                                    backgroundColor: "rgba(34, 197, 94, 0.6)",
                                    borderColor: "rgb(34, 197, 94)",
                                    borderWidth: 2,
                                    borderRadius: 8,
                                },
                                {
                                    label: "Expenses",
                                    data: expenseData,
                                    backgroundColor: "rgba(239, 68, 68, 0.6)",
                                    borderColor: "rgb(239, 68, 68)",
                                    borderWidth: 2,
                                    borderRadius: 8,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false, // Hide legend since we have custom indicators
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            return context.dataset.label + ": " + formatRupiah(context.parsed.y);
                                        }
                                    },
                                    backgroundColor: isDarkMode ? 'rgba(0, 0, 0, 0.9)' : 'rgba(255, 255, 255, 0.95)',
                                    titleColor: isDarkMode ? '#e5e7eb' : '#374151',
                                    bodyColor: isDarkMode ? '#e5e7eb' : '#374151',
                                    borderColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                                    borderWidth: 1,
                                    cornerRadius: 8,
                                    displayColors: false,
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    suggestedMin: suggestedMin,
                                    suggestedMax: suggestedMax,
                                    ticks: {
                                        callback: function (value) {
                                            return formatRupiah(value);
                                        },
                                        color: textColor,
                                        font: {
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        color: gridColor,
                                        drawBorder: false,
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: textColor,
                                        font: {
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        display: false,
                                    }
                                }
                            },
                        },
                    });
                }
            }

            // Category Chart
            if (categoryCanvas && @json($categoryData['labels']).length > 0) {
                const ctx2 = categoryCanvas.getContext("2d");
                window.categoryChart = new Chart(ctx2, {
                    type: "doughnut",
                    data: {
                        labels: @json($categoryData['labels']),
                        datasets: [
                            {
                                data: @json($categoryData['data']),
                                backgroundColor: @json($categoryData['colors']),
                                borderWidth: 0,
                                cutout: '60%',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false, // Hide legend since we have custom indicators
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ": " + formatRupiah(context.parsed) + ` (${percentage}%)`;
                                    },
                                },
                                backgroundColor: isDarkMode ? 'rgba(0, 0, 0, 0.9)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDarkMode ? '#e5e7eb' : '#374151',
                                bodyColor: isDarkMode ? '#e5e7eb' : '#374151',
                                borderColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                displayColors: false,
                            },
                        },
                    },
                });
            }
        }
</script>
</div>