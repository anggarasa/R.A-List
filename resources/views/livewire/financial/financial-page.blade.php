<div class="space-y-6" wire:ignore.self>
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
        <!-- Card Template -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <flux:heading size="sm" class="mb-1 truncate">Total Balance</flux:heading>
                    <flux:heading size="lg" class="text-blue-600 dark:text-blue-400 font-bold truncate">
                        {{ format_rupiah($totalBalance) }}
                    </flux:heading>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full shrink-0">
                    <flux:icon.wallet class="text-blue-600 w-6 h-6" />
                </div>
            </div>
            <flux:text size="sm" class="text-gray-500 mt-2 truncate">
                @if($totalBalance > 0)
                Your current total balance
                @else
                <span class="text-amber-600">No balance recorded</span>
                @endif
            </flux:text>
        </div>

        <!-- Income -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <flux:heading size="sm" class="mb-1 truncate">This Month's Income</flux:heading>
                    <flux:heading size="lg" class="text-green-600 dark:text-green-400 font-bold truncate">
                        {{ format_rupiah($totalIncome) }}
                    </flux:heading>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full shrink-0">
                    <flux:icon.arrow-up class="text-green-600 w-6 h-6" />
                </div>
            </div>
            <flux:text size="sm" class="text-gray-500 mt-2 truncate">
                @if($totalIncome > 0)
                @if($incomeChange >= 0)
                <span class="text-green-600">+{{ $incomeChange }}% from last month</span>
                @else
                <span class="text-red-600">{{ $incomeChange }}% from last month</span>
                @endif
                @else
                <span class="text-gray-400">No income recorded this month</span>
                @endif
            </flux:text>
        </div>

        <!-- Expenses -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <flux:heading size="sm" class="mb-1 truncate">This Month's Expenses</flux:heading>
                    <flux:heading size="lg" class="text-red-600 dark:text-red-400 font-bold truncate">
                        {{ format_rupiah($totalExpense) }}
                    </flux:heading>
                </div>
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full shrink-0">
                    <flux:icon.arrow-down class="text-red-600 w-6 h-6" />
                </div>
            </div>
            <flux:text size="sm" class="text-gray-500 mt-2 truncate">
                @if($totalExpense > 0)
                @if($expenseChange <= 0) <span class="text-green-600">{{ abs($expenseChange) }}% reduction from last
                    month</span>
                    @else
                    <span class="text-red-600">+{{ $expenseChange }}% increase from last month</span>
                    @endif
                    @else
                    <span class="text-gray-400">No expenses recorded this month</span>
                    @endif
            </flux:text>
        </div>

        <!-- Net Balance -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div class="truncate">
                    <flux:heading size="sm" class="mb-1 truncate">Net Balance</flux:heading>
                    <flux:heading size="lg"
                        class="font-bold truncate 
                        {{ $netBalance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ format_rupiah($netBalance) }}
                    </flux:heading>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full shrink-0">
                    <flux:icon.currency-dollar class="text-purple-600 w-6 h-6" />
                </div>
            </div>
            <flux:text size="sm" class="text-gray-500 mt-2 truncate">
                @if($netBalance > 0)
                <span class="text-green-600">Surplus this month</span>
                @elseif($netBalance < 0) <span class="text-red-600">Deficit this month</span>
                    @else
                    Break even this month
                    @endif
            </flux:text>
        </div>
    </div>

    <!-- Charts + Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Income vs Expenses -->
        <div class="lg:col-span-2 bg-white dark:bg-zinc-900 rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg" class="truncate">Income vs Expenses Trend</flux:heading>
                <div class="text-sm text-gray-500">Last 8 months</div>
            </div>
            @if(array_sum($monthlyData['income']) > 0 || array_sum($monthlyData['expenses']) > 0)
            <div class="relative h-80" wire:ignore>
                <canvas id="incomeExpenseChart"></canvas>
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-80 text-gray-400">
                <flux:icon.chart-bar class="w-16 h-16 mb-4 text-gray-300 shrink-0" />
                <flux:heading size="lg" class="mb-2">No Transaction Data</flux:heading>
                <flux:text class="text-center">Start tracking your income and expenses to see trends here</flux:text>
            </div>
            @endif
        </div>

        <!-- Expense Categories -->
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6">
            <flux:heading size="lg" class="mb-6 truncate">Expense Categories</flux:heading>
            @if(count($categoryData['labels']) > 0)
            <div class="relative h-64" wire:ignore>
                <canvas id="categoryChart"></canvas>
            </div>
            @else
            <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                <flux:icon.chart-pie class="w-12 h-12 mb-3 text-gray-300 shrink-0" />
                <flux:heading size="sm" class="mb-2">No Category Data</flux:heading>
                <flux:text size="sm" class="text-center">Add expense transactions to see category breakdown</flux:text>
            </div>
            @endif
        </div>

        <!-- Monthly Budget -->
        @if($monthlyBudget && $monthlyBudget->count() > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6 lg:col-start-3">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">Monthly Budget</flux:heading>
                <flux:text size="sm">{{ now()->format('F Y') }}</flux:text>
            </div>
            <div class="space-y-4">
                @foreach ($monthlyBudget as $budget)
                <div class="p-4 bg-gray-50 dark:bg-zinc-950 rounded-lg">
                    <div class="flex justify-between items-center mb-3">
                        <flux:heading size="sm" class="truncate">{{ $budget->category->name }}</flux:heading>
                        <flux:text size="xs" class="@if($budget->progress < 70) text-green-600
                                   @elseif($budget->progress < 90) text-yellow-600
                                   @else text-red-600 @endif">
                            {{ number_format($budget->progress, 1) }}%
                        </flux:text>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-zinc-600 rounded-full h-2 mb-2">
                        <div class="h-2 rounded-full transition-all duration-300
                            @if($budget->progress < 70) bg-green-500
                            @elseif($budget->progress < 90) bg-yellow-500
                            @else bg-red-500 @endif" style="width: {{ min($budget->progress, 100) }}%">
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 truncate">
                        <span>Used: {{ format_rupiah($budget->budget) }}</span>
                        <span>Budget: {{ format_rupiah($budget->monthly_budget) }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-zinc-700">
                <flux:button variant="filled" size="sm" class="w-full" wire:navigate
                    href="{{ route('financial.budget') }}"> Manage Budget </flux:button>
            </div>
        </div>
        @endif

        <!-- Financial Goals -->
        @if($financialGoals && $financialGoals->count() > 0)
        <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6 lg:col-start-3">
            <div class="flex items-center justify-between mb-6">
                <flux:heading size="lg">Financial Goals</flux:heading>
                <flux:text size="sm">{{ $financialGoals->count() }} active</flux:text>
            </div>
            <div class="space-y-4">
                @foreach($financialGoals as $goal)
                <div
                    class="p-4 rounded-lg @if($goal->progress_percentage >= 100) bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 @elseif($goal->progress_percentage >= 75) bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 @else bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 @endif ">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <flux:heading size="sm" class="font-medium">{{ $goal->name }}</flux:heading>
                            @if($goal->description) <flux:text size="xs" class="mt-1">{{ $goal->description }}
                            </flux:text> @endif
                        </div> <span
                            class="text-sm font-medium @if($goal->progress_percentage >= 100) text-green-600 @elseif($goal->progress_percentage >= 75) text-blue-600 @else text-gray-600 @endif ">
                            {{ number_format($goal->progress_percentage, 1) }}% </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-zinc-600 rounded-full h-2 mb-3">
                        <div class="h-2 rounded-full transition-all duration-300 @if($goal->progress_percentage >= 100) bg-green-500 @elseif($goal->progress_percentage >= 75) bg-blue-500 @else bg-gray-400 @endif "
                            style="width: {{ min($goal->progress_percentage, 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-2"> <span>{{
                            format_rupiah($goal->current_amount) }}</span> <span>{{ format_rupiah($goal->target_amount)
                            }}</span> </div>
                    <div class="flex justify-between items-center text-xs"> <span class="text-gray-500">Target: {{
                            $goal->target_date->format('M Y') }}</span> <span class="text-gray-500">{{
                            $goal->days_left_human }}</span> </div> @if($goal->progress_percentage >= 100) <div
                        class="mt-2 p-2 bg-green-100 dark:bg-green-900/30 rounded text-xs text-green-700 dark:text-green-300">
                        🎉 Goal achieved! </div> @endif
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-zinc-700">
                <flux:button variant="filled" size="sm" class="w-full" wire:navigate
                    href="{{ route('financial.goal') }}"> Manage Goals </flux:button>
            </div>
        </div>
        @endif

        <!-- Latest Transactions -->
        <div
            class="lg:col-span-2  lg:row-span-2 lg:col-start-1 lg:row-start-2 bg-white dark:bg-zinc-900 rounded-2xl shadow">
            <div class="p-6 border-b border-zinc-600 flex items-center justify-between">
                <div class="truncate">
                    <flux:heading size="lg">Recent Transactions</flux:heading>
                    <flux:text size="sm">Your latest financial activities</flux:text>
                </div>
                <flux:button variant="filled" size="sm" wire:navigate href="{{ route('financial.transaction') }}">
                    View All
                </flux:button>
            </div>
            <div class="p-6 space-y-4">
                @forelse($latestTransactions as $transaction)
                <div
                    class="flex items-center justify-between p-4 bg-zinc-50 dark:bg-zinc-950 rounded-lg hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">
                    <div class="flex items-center space-x-4 truncate">
                        <div class="p-2 rounded-full shrink-0
                            @if($transaction['type'] === 'income') bg-green-100 dark:bg-green-900/30
                            @else bg-red-100 dark:bg-red-900/30 @endif">
                            @if($transaction['type'] === 'income')
                            <flux:icon.arrow-up class="text-green-600 w-5 h-5" />
                            @else
                            <flux:icon.arrow-down class="text-red-600 w-5 h-5" />
                            @endif
                        </div>
                        <div class="truncate">
                            <flux:heading size="sm" class="truncate">{{ $transaction['description'] }}</flux:heading>
                            <div class="flex items-center space-x-2 text-xs text-gray-500 truncate">
                                <span>{{ $transaction['date'] }}</span>
                                <span>•</span>
                                <span class="truncate">{{ $transaction['category'] }}</span>
                                <span>•</span>
                                <span class="capitalize
                                    @if($transaction['type'] === 'income') text-green-600
                                    @else text-red-600 @endif">
                                    {{ $transaction['type'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <flux:heading size="sm"
                        class="font-bold truncate
                        {{ $transaction['type'] === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $transaction['type'] === 'income' ? '+' : '-' }}{{ format_rupiah($transaction['amount']) }}
                    </flux:heading>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <flux:icon.document-text class="w-16 h-16 mb-4 text-gray-300 shrink-0" />
                    <flux:heading size="lg" class="mb-2">No Transactions Yet</flux:heading>
                    <flux:text class="text-center mb-4">Start by adding your first income or expense transaction
                    </flux:text>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow p-6">
        <flux:heading size="lg" class="mb-4">Quick Actions</flux:heading>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <flux:button variant="primary" size="sm" class="flex items-center justify-center space-x-2" wire:navigate
                href="{{ route('financial.transaction') }}">
                <flux:icon.plus class="w-4 h-4" /><span>Add Transaction</span>
            </flux:button>
            <flux:button variant="filled" size="sm" class="flex items-center justify-center space-x-2" wire:navigate
                href="{{ route('financial.budget') }}">
                <flux:icon.calculator class="w-4 h-4" /><span>Set Budget</span>
            </flux:button>
            <flux:button variant="filled" size="sm" class="flex items-center justify-center space-x-2" wire:navigate
                href="{{ route('financial.goal') }}">
                <flux:icon.flag class="w-4 h-4" /><span>Set Goals</span>
            </flux:button>
            <flux:button variant="filled" size="sm" class="flex items-center justify-center space-x-2" wire:navigate
                href="#">
                <flux:icon.document-text class="w-4 h-4" /><span>View Reports</span>
            </flux:button>
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
                                    borderRadius: 6,
                                },
                                {
                                    label: "Expenses",
                                    data: expenseData,
                                    backgroundColor: "rgba(239, 68, 68, 0.6)",
                                    borderColor: "rgb(239, 68, 68)",
                                    borderWidth: 2,
                                    borderRadius: 6,
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: "top",
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            return context.dataset.label + ": " + formatRupiah(context.parsed.y);
                                        }
                                    },
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: 'white',
                                    bodyColor: 'white',
                                    borderColor: 'rgba(255, 255, 255, 0.1)',
                                    borderWidth: 1,
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
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.1)',
                                    }
                                },
                                x: {
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
                                borderWidth: 2,
                                borderColor: '#ffffff',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "bottom",
                                labels: {
                                    padding: 20,
                                    usePointStyle: true,
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                                        return context.label + ": " + formatRupiah(context.parsed) + ` (${percentage}%)`;
                                    },
                                },
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: 'white',
                                bodyColor: 'white',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                            },
                        },
                    },
                });
            }
        }
    </script>
</div>