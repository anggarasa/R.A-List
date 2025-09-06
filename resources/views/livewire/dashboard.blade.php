<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-zinc-900 dark:to-zinc-800">
    <!-- Header Section -->
    <div class="bg-white dark:bg-zinc-800 shadow-sm border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <flux:heading size="xl">Dashboard</flux:heading>
                    <flux:text>Selamat datang di R.A List Management System</flux:text>
                </div>
                <div class="flex items-center space-x-4 w-full sm:w-auto">
                    <flux:button wire:click="refreshData" icon="arrow-path">Refresh</flux:button>
                    <div class="text-right flex-shrink-0">
                        <flux:text>Hari ini</flux:text>
                        <flux:heading size="lg">{{ now()->format('d M Y') }}</flux:heading>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Total Balance Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 transform">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text>Total Balance</flux:text>
                        <flux:heading size="lg">{{ format_rupiah($totalBalance) }}</flux:heading>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                        <flux:icon.currency-dollar class="size-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                </div>
            </div>

            <!-- Monthly Income Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 transform">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text>This Month's Income</flux:text>
                        <flux:heading size="lg" class="text-emerald-600 dark:text-emerald-400">{{
                            format_rupiah($monthlyIncome) }}</flux:heading>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                        <flux:icon.arrow-trending-up class="size-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                </div>
            </div>

            <!-- Monthly Expense Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 transform">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text>This Month's Expenses</flux:text>
                        <flux:heading size="lg" class="text-red-600 dark:text-red-400">{{
                            format_rupiah($monthlyExpense) }}</flux:heading>
                    </div>
                    <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                        <flux:icon.arrow-trending-down class="size-6 text-red-600 dark:text-red-400" />
                    </div>
                </div>
            </div>

            <!-- Active Projects Card -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6 hover:shadow-lg hover:scale-105 transition-all duration-300 transform">
                <div class="flex items-center justify-between">
                    <div>
                        <flux:text>Active Projects</flux:text>
                        <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">{{ $activeProjects }}
                        </flux:heading>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <flux:icon.rectangle-stack class="size-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Completed Tasks -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Completed task</flux:heading>
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                        <flux:icon.check class="size-5 text-emerald-600 dark:text-emerald-400" />
                    </div>
                </div>
                <flux:heading size="xl" class="text-emerald-600 dark:text-emerald-400">{{ $completedTasks }}
                </flux:heading>
                <flux:text>The task has been completed</flux:text>
            </div>

            <!-- Pending Tasks -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Pending Tasks</flux:heading>
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                        <flux:icon.clock class="size-5 text-yellow-600 dark:text-yellow-400" />
                    </div>
                </div>
                <flux:heading size="xl" class="text-yellow-600 dark:text-yellow-400">{{ $pendingTasks }}
                </flux:heading>
                <flux:text>Task waiting</flux:text>
            </div>

            <!-- Task Completion Rate -->
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <flux:heading size="lg">Completion Level</flux:heading>
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <flux:icon.chart-bar class="size-5 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
                @php
                $totalTasks = $completedTasks + $pendingTasks;
                $completionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                @endphp
                <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">{{ number_format($completionRate, 1)
                    }}%
                </flux:heading>
                <flux:text>Of the total {{ $totalTasks }} tasks</flux:text>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <flux:heading size="lg">Latest Transactions</flux:heading>
                </div>
                <div class="p-6">
                    @if($recentTransactions->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentTransactions as $transaction)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 rounded-lg {{ $transaction->type === 'income' ? 'bg-emerald-100 dark:bg-emerald-900/20' : 'bg-red-100 dark:bg-red-900/20' }}">
                                    @if($transaction->type === 'income')
                                    <flux:icon.arrow-trending-up
                                        class="size-4 text-emerald-600 dark:text-emerald-400" />
                                    @else
                                    <flux:icon.arrow-trending-down class="size-4 text-red-600 dark:text-red-400" />
                                    @endif
                                </div>
                                <div>
                                    <flux:heading>{{ $transaction->description }}</flux:heading>
                                    <flux:text>{{
                                        $transaction->category->name ?? 'N/A' }}</flux:text>
                                </div>
                            </div>
                            <div class="text-right">
                                <flux:heading
                                    class="{{ $transaction->type === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}{{
                                    format_rupiah($transaction->amount) }}
                                </flux:heading>
                                <flux:text>{{
                                    $transaction->transaction_date->format('d M Y') }}</flux:text>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400">Belum ada transaksi</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Financial Goals -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <flux:heading size="lg">Financial Targets</flux:heading>
                </div>
                <div class="p-6">
                    @if($financialGoals->count() > 0)
                    <div class="space-y-4">
                        @foreach($financialGoals as $goal)
                        <div class="p-4 bg-slate-50 dark:bg-zinc-700/50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <flux:heading>{{ $goal->name }}</flux:heading>
                                <flux:text>{{ $goal->days_left_human }}</flux:text>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <flux:text>{{ format_rupiah($goal->current_amount) }} / {{
                                    format_rupiah($goal->target_amount) }}</flux:text>
                                <flux:heading>{{
                                    number_format($goal->progress_percentage, 1) }}%</flux:heading>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-zinc-600 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-emerald-500 h-2 rounded-full transition-all duration-1000 ease-out animate-pulse"
                                    style="width: {{ min($goal->progress_percentage, 100) }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                            </path>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400">Belum ada target keuangan</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mt-6 sm:mt-8">
            <!-- Recent Projects -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <flux:heading size="lg">Latest Projects</flux:heading>
                </div>
                <div class="p-6">
                    @if($recentProjects->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentProjects as $project)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                                    <flux:icon.rectangle-stack class="size-4 text-blue-600 dark:text-blue-400" />
                                </div>
                                <div>
                                    <flux:heading>{{ $project->name }}</flux:heading>
                                    <flux:text>{{ $project->tasks_count }} task</flux:text>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-slate-100 text-slate-800 dark:bg-zinc-700 dark:text-slate-300' }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400">Belum ada proyek</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Tasks -->
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <flux:heading size="lg">Upcoming Assignments</flux:heading>
                </div>
                <div class="p-6">
                    @if($upcomingTasks->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingTasks as $task)
                        <div
                            class="flex items-center justify-between p-4 bg-slate-50 dark:bg-zinc-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 {{ $task->priority === 'High' ? 'bg-red-100 dark:bg-red-900/20' : ($task->priority === 'Medium' ? 'bg-yellow-100 dark:bg-yellow-900/20' : 'bg-green-100 dark:bg-green-900/20') }} rounded-lg">
                                    <flux:icon.clipboard-document-check
                                        class="size-4 {{ $task->priority === 'High' ? 'text-red-600 dark:text-red-400' : ($task->priority === 'Medium' ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}" />
                                </div>
                                <div>
                                    <flux:heading>{{ $task->title }}</flux:heading>
                                    <flux:text>{{ $task->project->name ?? 'N/A' }}</flux:text>
                                </div>
                            </div>
                            <div class="text-right">
                                <flux:heading>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M')
                                    : 'N/A'
                                    }}</flux:heading>

                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $task->priority === 'High' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : ($task->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                        <p class="text-slate-600 dark:text-slate-400">Tidak ada tugas mendatang</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Budget Status Section -->
        @if($budgetStatus->count() > 0)
        <div class="mt-6 sm:mt-8">
            <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <flux:heading size="lg">This Month's Budget Status</flux:heading>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($budgetStatus as $budget)
                        <div class="p-4 bg-slate-50 dark:bg-zinc-700/50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <flux:heading>{{ $budget['category'] }}</flux:heading>
                                <flux:text
                                    class="{{ $budget['percentage'] > 100 ? 'text-red-600 dark:text-red-400' : ($budget['percentage'] > 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                    {{ number_format($budget['percentage'], 1) }}%
                                </flux:text>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-zinc-600 rounded-full h-2 mb-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-1000 ease-out {{ $budget['percentage'] > 100 ? 'bg-red-500' : ($budget['percentage'] > 80 ? 'bg-yellow-500' : 'bg-emerald-500') }}"
                                    style="width: {{ min($budget['percentage'], 100) }}%"></div>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ format_rupiah($budget['used']) }}</flux:text>
                                <flux:text>{{ format_rupiah($budget['budget']) }}</flux:text>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>