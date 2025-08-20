<div class="space-y-10">
    {{-- @if (true)
    <!-- maintanance view page -->
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="flex items-center justify-center">
            <flux:icon.information-circle variant="solid" class="size-16" />
        </div>
        <div class="flex items-center justify-center">
            <flux:heading size="xl">{{ __('Maintenance Mode') }}</flux:heading>
        </div>
    </div>
    @else --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <flux:heading size="xl">{{ __('Manage Financial Budget') }}</flux:heading>

        <flux:modal.trigger name="add-budget">
            <flux:button icon="plus" variant="primary">{{ __('Create New Budget') }}</flux:button>
        </flux:modal.trigger>
    </div>

    {{-- budget summary --}}
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            {{-- Total Budget --}}
            <div class="bg-white rounded-xl shadow-sm p-4 dark:bg-zinc-800 dark:text-zinc-100">
                <div class="flex items-center justify-between">
                    <div class="flex-none space-y-3">
                        <flux:heading>{{ __('Total Budget') }}</flux:heading>
                        <flux:heading size="xl">
                            {{ format_rupiah($totalBudget) }}
                        </flux:heading>
                    </div>
                    <flux:icon.calculator variant="solid" class="size-10 text-blue-600" />
                </div>
            </div>

            {{-- Total Spent --}}
            <div class="bg-white rounded-xl shadow-sm p-4 dark:bg-zinc-800 dark:text-zinc-100">
                <div class="flex items-center justify-between">
                    <div class="flex-none space-y-3">
                        <flux:heading>{{ __('Total Spent') }}</flux:heading>
                        <flux:heading size="xl" class="text-red-600">
                            {{ format_rupiah($totalSpent) }}
                        </flux:heading>
                    </div>
                    <flux:icon.chart-pie variant="solid" class="size-10 text-red-600" />
                </div>
            </div>

            {{-- Total Remaining --}}
            <div class="bg-white rounded-xl shadow-sm p-4 dark:bg-zinc-800 dark:text-zinc-100">
                <div class="flex items-center justify-between">
                    <div class="flex-none space-y-3">
                        <flux:heading>{{ __('Total Remaining') }}</flux:heading>
                        <flux:heading size="xl" class="text-green-600">
                            {{ format_rupiah($totalRemaining) }}
                        </flux:heading>
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
            @forelse ($budgets as $budget)
            <div
                class="border rounded-lg p-4 {{ $budget->progress >= 90 ? 'bg-red-900/20 border-red-500' : 'border-gray-200 dark:border-zinc-700' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <flux:heading>{{ $budget->category->name }}</flux:heading>
                    </div>
                    <div class="text-right">
                        <flux:heading>
                            {{ format_rupiah($budget->budget) }}
                            / {{ format_rupiah($budget->monthly_budget) }}
                        </flux:heading>
                        <flux:text>{{ round(min($budget->progress, 100), 2) }}% terpakai</flux:text>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-{{ $budget->progress < 50 ? 'blue-500' : ($budget->progress >= 90 ? 'red-500' : 'orange-500') }} h-3 rounded-full"
                        style="width: {{ min($budget->progress, 100) }}%"></div>
                </div>
            </div>
            @empty
            <flux:text>Tidak ada budget untuk periode ini.</flux:text>
            @endforelse
        </div>
    </div>


    {{-- modal --}}
    <flux:modal name="add-budget" variant="flyout" @close="clearForm">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Budget</flux:heading>
                <flux:text class="mt-2">Manage your finances by limiting your expenses.</flux:text>
            </div>

            <form wire:submit="saveBudget" class="space-y-6">
                {{-- select category --}}
                <flux:select wire:model="category_id" label="Select Category">
                    <flux:select.option>Choose category...</flux:select.option>
                    @foreach ($categories as $category)
                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- select month --}}
                <flux:select wire:model="month" label="Select Month">
                    <flux:select.option>Choose month...</flux:select.option>
                    <flux:select.option value="01">January</flux:select.option>
                    <flux:select.option value="02">February</flux:select.option>
                    <flux:select.option value="03">March</flux:select.option>
                    <flux:select.option value="04">April</flux:select.option>
                    <flux:select.option value="05">May</flux:select.option>
                    <flux:select.option value="06">June</flux:select.option>
                    <flux:select.option value="07">July</flux:select.option>
                    <flux:select.option value="08">August</flux:select.option>
                    <flux:select.option value="09">September</flux:select.option>
                    <flux:select.option value="10">October</flux:select.option>
                    <flux:select.option value="11">November</flux:select.option>
                    <flux:select.option value="12">December</flux:select.option>
                </flux:select>

                {{-- select year --}}
                <flux:select wire:model="year" label="Select Year">
                    <flux:select.option>Choose year...</flux:select.option>
                    @foreach($years as $yr)
                    <flux:select.option value="{{ $yr }}">{{ $yr }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- input budget amount --}}
                <livewire:widget.currency-input label="Total Budget" name="budget" :error="$errors->first('budget')"
                    size="sm" />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save Budget</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
    {{-- @endif --}}
</div>