<div class="space-y-10">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <flux:heading size="xl">My Monthly Financial Report</flux:heading>
        <flux:button icon="plus" variant="primary">Add Transaction</flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 md:grid-rows-5 gap-4">
        <!-- Income Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-green-100 dark:bg-green-900/20 items-center rounded-lg">
                    <flux:icon.arrow-up variant="solid" class="text-green-600"/>
                </div>
                <div class="space-y-2">
                    <flux:heading>Income</flux:heading>
                    <flux:heading size="xl" class="text-green-600 dark:text-green-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">+12% from last month</flux:text>
        </div>

        <!-- Expenditure Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-red-100 dark:bg-red-900/20 items-center rounded-lg">
                    <flux:icon.arrow-down variant="solid" class="text-red-600"/>
                </div>
                <div class="space-y-2">
                    <flux:heading>Expenditure</flux:heading>
                    <flux:heading size="xl" class="text-red-600 dark:text-red-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">-6% from last month</flux:text>
        </div>

        <!-- Net Balance Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-purple-100 dark:bg-purple-900/20 items-center rounded-lg">
                    <flux:icon.currency-dollar variant="solid" class="text-purple-600"/>
                </div>
                <div class="space-y-2">
                    <flux:heading>Net Balance</flux:heading>
                    <flux:heading size="xl" class="text-purple-600 dark:text-purple-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">Remainder of income</flux:text>
        </div>

        <!-- Income vs Expenses Chart -->
        <div class="md:col-span-2 md:row-span-2 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Income VS Expenses</flux:heading>
            <div class="relative h-64 md:h-72">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Expense Categories Chart -->
        <div class="md:row-span-2 md:col-start-3 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <flux:heading size="lg" class="mb-3">Expense Categories</flux:heading>
            <div class="relative h-64 md:h-72">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Latest Transactions -->
        <div class="md:col-span-3 md:row-start-4 shadow-lg rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <flux:heading>Latest Transactions</flux:heading>
                    <flux:link href="#">View All</flux:link>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg mr-3">💼</div>
                            <div>
                                <flux:heading>Gaji Bulan Agustus</flux:heading>
                                <flux:text>8 Agu 2025 • Income</flux:text>
                            </div>
                        </div>
                        <div class="text-right mt-2 sm:mt-0">
                            <flux:heading size="lg" class="text-green-600 dark:text-green-400">+Rp 15,000,000</flux:heading>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        const ctx1 = document
            .getElementById("incomeExpenseChart")
            .getContext("2d");
        new Chart(ctx1, {
            type: "bar",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu"],
                datasets: [
                    {
                        label: "Income",
                        data: [
                            12000000, 13500000, 14200000, 13800000, 15100000, 14600000,
                            15200000, 15750000,
                        ],
                        backgroundColor: "rgba(34, 197, 94, 0.5)",
                        borderColor: "rgb(34, 197, 94)",
                        borderWidth: 1,
                    },
                    {
                        label: "Expenses",
                        data: [
                            10500000, 11200000, 12800000, 11900000, 13200000, 12400000,
                            12800000, 12300000,
                        ],
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
                                return "Rp " + value / 1000000 + "M";
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
                labels: [
                    "Rumah & Sewa",
                    "Makanan",
                    "Transportasi",
                    "Entertainment",
                    "Utilitas",
                    "Lainnya",
                ],
                datasets: [
                    {
                        data: [5500000, 2300000, 1200000, 800000, 800000, 1700000],
                        backgroundColor: [
                            "rgb(59, 130, 246)",
                            "rgb(34, 197, 94)",
                            "rgb(245, 158, 11)",
                            "rgb(168, 85, 247)",
                            "rgb(239, 68, 68)",
                            "rgb(107, 114, 128)",
                        ],
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
