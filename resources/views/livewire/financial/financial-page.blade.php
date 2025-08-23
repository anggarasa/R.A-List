<div class="space-y-10" wire:ignore.self>
    <flux:heading size="xl">My Monthly Financial Report</flux:heading>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 auto-rows-min gap-2">
        <!-- Total Saldo Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <flux:icon.wallet variant="solid" class="text-blue-600" />
                </div>
                <div>
                    <flux:heading>Total Saldo</flux:heading>
                    <flux:heading size="lg" class="text-blue-600 dark:text-blue-400">
                        {{ format_rupiah($totalBalance) }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-3">Current Balance</flux:text>
        </div>

        <!-- Income Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <flux:icon.arrow-up variant="solid" class="text-green-600" />
                </div>
                <div>
                    <flux:heading>Income</flux:heading>
                    <flux:heading size="lg" class="text-green-600 dark:text-green-400">
                        {{ format_rupiah($totalIncome) }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-3">
                @if($incomeChange >= 0)
                +{{ $incomeChange }}% from last month
                @else
                {{ $incomeChange }}% from last month
                @endif
            </flux:text>
        </div>

        <!-- Expenditure Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <flux:icon.arrow-down variant="solid" class="text-red-600" />
                </div>
                <div>
                    <flux:heading>Expenditure</flux:heading>
                    <flux:heading size="lg" class="text-red-600 dark:text-red-400">
                        {{ format_rupiah($totalExpense) }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-3">
                @if($expenseChange <= 0) {{ abs($expenseChange) }}% reduction from last month @else +{{ $expenseChange
                    }}% from last month @endif </flux:text>
        </div>

        <!-- Net Balance Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <flux:icon.currency-dollar variant="solid" class="text-purple-600" />
                </div>
                <div>
                    <flux:heading>Net Balance</flux:heading>
                    <flux:heading size="lg" class="text-purple-600 dark:text-purple-400">
                        {{ format_rupiah($netBalance) }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-3">This month's remainder</flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 auto-row-min gap-2">
        <!-- Income vs Expenses Chart -->
        <div class="lg:col-span-2 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Income VS Expenses</flux:heading>
            <div class="relative h-64 sm:h-72 lg:h-96" wire:ignore>
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Expense Categories Chart -->
        <div class="lg:col-start-3 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Expense Categories</flux:heading>
            <div class="relative h-64 sm:h-72 lg:h-96" wire:ignore>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Monthly Budget -->
        <div class="lg:col-start-3 lg:row-start-2 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Monthly Budget</flux:heading>
            <div class="space-y-4">
                @foreach ($monthlyBudget as $budget)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <flux:text>{{ $budget->category->name }}</flux:text>
                        <flux:text>Rp {{ format_nominal($budget->monthly_budget) }} / Rp {{
                            format_nominal($budget->monthly_budget) }}</flux:text>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-{{ $budget->progress < 50 ? 'blue-500' : ($budget->progress >= 90 ? 'red-500' : 'orange-500') }} h-2 rounded-full"
                            style="width: {{ min($budget->progress, 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Latest Transactions -->
        <div
            class="lg:col-span-2 lg:row-span-2 lg:col-start-1 lg:row-start-2 shadow-lg rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <!-- Header -->
            <div
                class="p-6 border-b border-zinc-200 dark:border-zinc-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <flux:heading>Latest Transactions</flux:heading>
                <flux:link href="{{ route('financial.transaction') }}">View All</flux:link>
            </div>
            <!-- List -->
            <div class="p-6 space-y-4">
                @foreach($latestTransactions as $transaction)
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                    <div class="flex items-center">
                        <div
                            class="p-2 bg-{{ $transaction['type'] === 'income' ? 'green' : 'red' }}-100 dark:bg-{{ $transaction['type'] === 'income' ? 'green' : 'red' }}-900/20 rounded-lg mr-3">
                            @if ($transaction['type'] === 'income')
                            <flux:icon.arrow-up variant="solid" class="text-green-600" />
                            @else
                            <flux:icon.arrow-down variant="solid" class="text-red-600" />
                            @endif
                        </div>
                        <div>
                            <flux:heading>{{ $transaction['description'] }}</flux:heading>
                            <flux:text>{{ $transaction['date'] }} • {{ ucfirst($transaction['type']) }}</flux:text>
                        </div>
                    </div>
                    <flux:heading size="lg"
                        class="text-{{ $transaction['type'] === 'income' ? 'green' : 'red' }}-600 dark:text-{{ $transaction['type'] === 'income' ? 'green' : 'red' }}-400 mt-2 sm:mt-0">
                        {{ $transaction['type'] === 'income' ? '+' : '-' }}Rp {{ number_format($transaction['amount'],
                        0, ',', '.') }}
                    </flux:heading>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        // Listen for Livewire navigation events
        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                initializeCharts();
            }, 100);
        });

        // Initialize charts function
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

            // Check if canvas elements exist before creating charts
            const incomeExpenseCanvas = document.getElementById("incomeExpenseChart");
            const categoryCanvas = document.getElementById("categoryChart");

            // Helper format Rupiah
            function formatRupiah(value) {
                return new Intl.NumberFormat("id-ID", {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0
                }).format(value);
            }

            if (incomeExpenseCanvas) {
                const ctx1 = incomeExpenseCanvas.getContext("2d");

                // Ambil data income & expense dari backend
                const incomeData = @json($monthlyData['income']);
                const expenseData = @json($monthlyData['expenses']);
                const allValues = [...incomeData, ...expenseData];

                // Hitung min & max data
                const minValue = Math.min(...allValues);
                const maxValue = Math.max(...allValues);

                // Kasih padding 500k atas & bawah
                let paddedMin = minValue - 500000;
                let paddedMax = maxValue + 500000;

                // Biar ga minus
                if (paddedMin < 0) paddedMin = 0;

                // Dibulatkan ke juta terdekat
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
                                backgroundColor: "rgba(34, 197, 94, 0.5)",
                                borderColor: "rgb(34, 197, 94)",
                                borderWidth: 1,
                            },
                            {
                                label: "Expenses",
                                data: expenseData,
                                backgroundColor: "rgba(239, 68, 68, 0.5)",
                                borderColor: "rgb(239, 68, 68)",
                                borderWidth: 1,
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
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
                            },
                        },
                        plugins: {
                            legend: {
                                position: "top",
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.dataset.label + ": " + formatRupiah(context.parsed.y);
                                    }
                                }
                            }
                        },
                    },
                });
            }

            if (categoryCanvas) {
                const ctx2 = categoryCanvas.getContext("2d");
                window.categoryChart = new Chart(ctx2, {
                    type: "doughnut",
                    data: {
                        labels: @json($categoryData['labels']),
                        datasets: [
                            {
                                data: @json($categoryData['data']),
                                backgroundColor: @json($categoryData['colors']),
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: "bottom",
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.label + ": " + formatRupiah(context.parsed);
                                    },
                                },
                            },
                        },
                    },
                });
            }
        }
    </script>
</div>