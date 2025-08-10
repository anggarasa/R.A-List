<div class="space-y-10">
    <flux:heading size="xl">My Monthly Financial Report</flux:heading>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 auto-rows-min gap-2">
        <!-- Total Saldo Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <flux:icon.arrow-up variant="solid" class="text-green-600" />
                </div>
                <div>
                    <flux:heading>Total Saldo</flux:heading>
                    <flux:heading size="xl" class="text-green-600 dark:text-green-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">+12% from last month</flux:text>
        </div>

        <!-- Income Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <flux:icon.arrow-up variant="solid" class="text-green-600" />
                </div>
                <div>
                    <flux:heading>Income</flux:heading>
                    <flux:heading size="xl" class="text-green-600 dark:text-green-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">+12% from last month</flux:text>
        </div>

        <!-- Expenditure Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-red-100 dark:bg-red-900/20 rounded-lg">
                    <flux:icon.arrow-down variant="solid" class="text-red-600" />
                </div>
                <div>
                    <flux:heading>Expenditure</flux:heading>
                    <flux:heading size="xl" class="text-red-600 dark:text-red-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">-6% from last month</flux:text>
        </div>

        <!-- Net Balance Card -->
        <div class="shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex items-center space-x-5">
                <div class="py-2 px-1 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <flux:icon.currency-dollar variant="solid" class="text-purple-600" />
                </div>
                <div>
                    <flux:heading>Net Balance</flux:heading>
                    <flux:heading size="xl" class="text-purple-600 dark:text-purple-400">Rp 1.000.000</flux:heading>
                </div>
            </div>
            <flux:text class="mt-5">Remainder of income</flux:text>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 auto-row-min gap-2">
        <!-- Income vs Expenses Chart -->
        <div class="lg:col-span-2 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex justify-between items-start">
                <flux:heading size="lg" class="mb-3">Income VS Expenses</flux:heading>
                <flux:text size="sm">
                    <flux:link href="#">See Report</flux:link>
                </flux:text>
            </div>
            <div class="relative h-64 sm:h-72 lg:h-96">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Expense Categories Chart -->
        <div class="lg:col-start-3 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex justify-between items-start">
                <flux:heading size="lg" class="mb-3">Expense Categories</flux:heading>
                <flux:text size="sm">
                    <flux:link href="#">Manage Category</flux:link>
                </flux:text>
            </div>
            <div class="relative h-64 sm:h-72 lg:h-96">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Monthly Budget -->
        <div class="lg:col-start-3 lg:row-start-2 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex justify-between items-start">
                <flux:heading size="lg" class="mb-3">Monthly Budget</flux:heading>
                <flux:text size="sm">
                    <flux:link href="#">Manage Budget</flux:link>
                </flux:text>
            </div>
            <div class="space-y-4">
                <!-- Makanan -->
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <flux:text>Makanan</flux:text>
                        <flux:text>Rp 850K / Rp 1.000K</flux:text>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                </div>
                <!-- Transportasi -->
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <flux:text>Transportasi</flux:text>
                        <flux:text>Rp 320K / Rp 500K</flux:text>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 64%"></div>
                    </div>
                </div>
                <!-- Hiburan -->
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <flux:text>Hiburan</flux:text>
                        <flux:text>Rp 750K / Rp 600K</flux:text>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Targets -->
        <div class="lg:col-start-3 lg:row-start-3 shadow-lg p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <div class="flex justify-between items-start">
                <flux:heading size="lg" class="mb-3">Financial Targets</flux:heading>
                <flux:text size="sm">
                    <flux:link href="#">Manage Target</flux:link>
                </flux:text>
            </div>
            <div class="space-y-4">
                <!-- Emergency Fund -->
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-medium text-gray-800">Emergency Fund</h4>
                        <span class="text-sm text-gray-500">75%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="text-sm text-gray-600">Rp 15M / Rp 20M</p>
                    <p class="text-xs text-gray-500 mt-1">Target: Des 2024</p>
                </div>
                <!-- Liburan Bali -->
                <div class="p-4 bg-green-50 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-medium text-gray-800">Liburan Bali</h4>
                        <span class="text-sm text-gray-500">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                    <p class="text-sm text-gray-600">Rp 4.5M / Rp 10M</p>
                    <p class="text-xs text-gray-500 mt-1">Target: Jun 2024</p>
                </div>
            </div>
        </div>

        <!-- Latest Transactions -->
        <div
            class="lg:col-span-2 lg:row-span-2 lg:col-start-1 lg:row-start-2 shadow-lg rounded-xl bg-zinc-50 dark:bg-zinc-900">
            <!-- Header -->
            <div
                class="p-6 border-b border-zinc-200 dark:border-zinc-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <flux:heading>Latest Transactions</flux:heading>
                <flux:link href="#">View All</flux:link>
            </div>
            <!-- List -->
            <div class="p-6 space-y-4">
                <!-- Item -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between py-3 border-b border-zinc-100 dark:border-zinc-700 last:border-b-0">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-900/20 rounded-lg mr-3">💼</div>
                        <div>
                            <flux:heading>Gaji Bulan Agustus</flux:heading>
                            <flux:text>8 Agu 2025 • Income</flux:text>
                        </div>
                    </div>
                    <flux:heading size="lg" class="text-green-600 dark:text-green-400 mt-2 sm:mt-0">
                        +Rp 15,000,000
                    </flux:heading>
                </div>
                <!-- Ulangi item sesuai kebutuhan -->
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