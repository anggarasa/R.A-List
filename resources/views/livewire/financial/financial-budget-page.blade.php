<div class="space-y-10">
    @if (true)
    {{-- maintanance view page --}}
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="flex items-center justify-center">
            <flux:icon.information-circle variant="solid" class="size-16" />
        </div>
        <div class="flex items-center justify-center">
            <flux:heading size="xl">{{ __('Maintenance Mode') }}</flux:heading>
        </div>
    </div>
    @else
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <flux:heading size="xl">{{ __('Manage Financial Budget') }}</flux:heading>

        <flux:button icon="plus" variant="primary">{{ __('Create New Budget') }}</flux:button>
    </div>

    {{-- budget summary --}}
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-4 dark:bg-zinc-800 dark:text-zinc-100">
                <div class="flex items-center justify-between">
                    <div class="flex-none space-y-3">
                        <flux:heading>{{ __('Total Budget') }}</flux:heading>
                        <flux:heading size="xl">Rp 10.000.000</flux:heading>
                    </div>

                    <flux:icon.calculator variant="solid" class="size-10 text-blue-600" />
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 dark:bg-zinc-800 dark:text-zinc-100">
                <div class="flex items-center justify-between">
                    <div class="flex-none space-y-3">
                        <flux:heading>{{ __('Total Spent') }}</flux:heading>
                        <flux:heading size="xl" class="text-red-600">Rp 8.500.000</flux:heading>
                    </div>

                    <flux:icon.chart-pie variant="solid" class="size-10 text-red-600" />
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 dark:bg-zinc-800 dark:text-zinc-100">
                <div class="flex items-center justify-between">
                    <div class="flex-none space-y-3">
                        <flux:heading>{{ __('Total Remaining') }}</flux:heading>
                        <flux:heading size="xl" class="text-green-600">Rp 1.500.000</flux:heading>
                    </div>

                    <flux:icon.banknotes variant="solid" class="size-10 text-green-600" />
                </div>
            </div>
        </div>
    </div>


    {{-- budget list --}}
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <flux:heading size="lg">{{ __('Budget per Category') }}</flux:heading>
        <div class="space-y-6 mt-6">
            <div class="border border-gray-200 rounded-lg p-4 dark:border-zinc-700">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        {{-- <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-utensils text-orange-600 text-sm"></i>
                        </div> --}}
                        <flux:heading>{{ __('Food & Beverage') }}</flux:heading>
                    </div>
                    <div class="text-right">
                        <flux:heading>Rp 850.000 / Rp 1.500.000</flux:heading>
                        <flux:text>57% terpakai</flux:text>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-orange-500 h-3 rounded-full" style="width: 87%"></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>