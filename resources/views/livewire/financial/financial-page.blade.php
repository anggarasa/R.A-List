<div class="space-y-10">
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
                    <flux:heading size="xl" class="text-blue-600 dark:text-blue-400">
                        Rp {{ number_format($totalBalance, 0, ',', '.') }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">Current Balance</flux:text>
        </div>

        <!-- Income Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <flux:icon.arrow-up variant="solid" class="text-green-600" />
                </div>
                <div>
                    <flux:heading>Income</flux:heading>
                    <flux:heading size="xl" class="text-green-600 dark:text-green-400">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">
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
                    <flux:heading size="xl" class="text-red-600 dark:text-red-400">
                        Rp {{ number_format($totalExpense, 0, ',', '.') }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">
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
                    <flux:heading size="xl" class="text-purple-600 dark:text-purple-400">
                        Rp {{ number_format($netBalance, 0, ',', '.') }}
                    </flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">This month's remainder</flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 auto-row-min gap-2">
        <!-- Income vs Expenses Chart -->
        <div class="lg:col-span-2 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Income VS Expenses</flux:heading>
            <div class="relative h-64 sm:h-72 lg:h-96">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Expense Categories Chart -->
        <div class="lg:col-start-3 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Expense Categories</flux:heading>
            <div class="relative h-64 sm:h-72 lg:h-96">
                <canvas id="categoryChart"></canvas>
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

    @script
    <script>
        // Income vs Expenses Chart
        const ctx1 = document.getElementById("incomeExpenseChart").getContext("2d");
        new Chart(ctx1, {
            type: "bar",
            data: {
                labels: @json($monthlyData['labels']),
                datasets: [
                    {
                        label: "Income",
                        data: @json($monthlyData['income']),
                        backgroundColor: "rgba(34, 197, 94, 0.5)",
                        borderColor: "rgb(34, 197, 94)",
                        borderWidth: 1,
                    },
                    {
                        label: "Expenses",
                        data: @json($monthlyData['expenses']),
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
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return "Rp " + (value / 1000000).toFixed(1) + "M";
                            },
                        },
                    },
                },
                plugins: {
                    legend: {
                        position: "top",
                    },
                },
            },
        });

        // Category Chart
        const ctx2 = document.getElementById("categoryChart").getContext("2d");
        new Chart(ctx2, {
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
                                return (
                                    context.label +
                                    ": Rp " +
                                    (context.parsed / 1000000).toFixed(1) +
                                    "M"
                                );
                            },
                        },
                    },
                },
            },
        });
    </script>
    @endscript
</div>