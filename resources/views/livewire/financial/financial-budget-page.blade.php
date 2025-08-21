<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
        <div class="space-y-2">
            <flux:heading size="2xl" class="font-bold">{{ __('Manage Financial Budget') }}</flux:heading>
            <flux:text class="text-gray-600 dark:text-gray-400">
                Monitor and manage your expenses for the period {{ $currentPeriod }}
            </flux:text>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Filter Controls --}}
            <div class="flex gap-2">
                <flux:select wire:model.live="filterMonth" class="min-w-32">
                    <flux:select.option value="1">January</flux:select.option>
                    <flux:select.option value="2">February</flux:select.option>
                    <flux:select.option value="3">March</flux:select.option>
                    <flux:select.option value="4">April</flux:select.option>
                    <flux:select.option value="5">May</flux:select.option>
                    <flux:select.option value="6">June</flux:select.option>
                    <flux:select.option value="7">July</flux:select.option>
                    <flux:select.option value="8">August</flux:select.option>
                    <flux:select.option value="9">September</flux:select.option>
                    <flux:select.option value="10">October</flux:select.option>
                    <flux:select.option value="11">November</flux:select.option>
                    <flux:select.option value="12">December</flux:select.option>
                </flux:select>

                <flux:select wire:model.live="filterYear" class="min-w-24">
                    @foreach($years as $yr)
                    <flux:select.option value="{{ $yr }}">{{ $yr }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>

            <flux:modal.trigger name="budget-modal">
                <flux:button wire:click="openCreateModal" icon="plus" variant="primary" size="sm">
                    {{ __('Create New Budget') }}
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <flux:heading size="lg" class="mb-6">Budget Summary {{ $currentPeriod }}</flux:heading>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Total Budget --}}
            <div
                class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <flux:text size="sm" class="text-blue-700 dark:text-blue-300 font-medium">Total Budget
                        </flux:text>
                        <flux:heading size="xl" class="text-blue-900 dark:text-blue-100">
                            {{ format_rupiah($summary['total_budget']) }}
                        </flux:heading>
                        <flux:text size="xs" class="text-blue-600 dark:text-blue-400">
                            {{ $summary['budget_count'] }} kategori
                        </flux:text>
                    </div>
                    <flux:icon.calculator variant="solid" class="size-10 text-blue-600" />
                </div>
            </div>

            {{-- Total Used --}}
            <div
                class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl p-4 border border-orange-200 dark:border-orange-800">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <flux:text size="sm" class="text-orange-700 dark:text-orange-300 font-medium">Already Used
                        </flux:text>
                        <flux:heading size="xl" class="text-orange-900 dark:text-orange-100">
                            {{ format_rupiah($summary['total_used']) }}
                        </flux:heading>
                        <flux:text size="xs" class="text-orange-600 dark:text-orange-400">
                            {{ number_format($summary['overall_percentage'], 1) }}% of total
                        </flux:text>
                    </div>
                    <flux:icon.chart-pie variant="solid" class="size-10 text-orange-600" />
                </div>
            </div>

            {{-- Total Remaining --}}
            <div
                class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <flux:text size="sm" class="text-green-700 dark:text-green-300 font-medium">Remaining Budget
                        </flux:text>
                        <flux:heading size="xl" class="text-green-900 dark:text-green-100">
                            {{ format_rupiah($summary['total_remaining']) }}
                        </flux:heading>
                        <flux:text size="xs" class="text-green-600 dark:text-green-400">
                            {{ number_format(100 - $summary['overall_percentage'], 1) }}% remaining
                        </flux:text>
                    </div>
                    <flux:icon.banknotes variant="solid" class="size-10 text-green-600" />
                </div>
            </div>

            {{-- Alert Status --}}
            <div
                class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <flux:text size="sm" class="text-red-700 dark:text-red-300 font-medium">Alert Status
                        </flux:text>
                        <flux:heading size="xl" class="text-red-900 dark:text-red-100">
                            {{ $summary['over_budget_count'] + $summary['critical_count'] }}
                        </flux:heading>
                        <flux:text size="xs" class="text-red-600 dark:text-red-400">
                            over/critical category
                        </flux:text>
                    </div>
                    <flux:icon.exclamation-triangle variant="solid" class="size-10 text-red-600" />
                </div>
            </div>
        </div>
    </div>

    {{-- Budget List --}}
    <div class="bg-white rounded-xl shadow-sm p-6 dark:bg-zinc-900 dark:text-zinc-100">
        <div class="flex items-center justify-between mb-6">
            <flux:heading size="lg">Budget per Category</flux:heading>
            @if($budgets->count() > 0)
            <flux:text size="sm" class="text-gray-500">
                {{ $budgets->count() }} active budget category
            </flux:text>
            @endif
        </div>

        <div class="space-y-4">
            @forelse ($budgets as $budget)
            <div class="border rounded-lg p-5 transition-all duration-200 hover:shadow-md 
                {{ $budget->status === 'over_budget' ? 'bg-red-50 border-red-200 dark:bg-red-900/10 dark:border-red-800' : 
                   ($budget->status === 'critical' ? 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/10 dark:border-yellow-800' : 
                   'border-gray-200 dark:border-zinc-700 hover:border-gray-300 dark:hover:border-zinc-600') }}">

                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <flux:heading size="lg" class="font-semibold">{{ $budget->category->name }}</flux:heading>
                            @if($budget->status === 'over_budget')
                            <flux:badge color="red" size="sm">Over Budget</flux:badge>
                            @elseif($budget->status === 'critical')
                            <flux:badge color="yellow" size="sm">Critical</flux:badge>
                            @elseif($budget->status === 'warning')
                            <flux:badge color="orange" size="sm">Warning</flux:badge>
                            @else
                            <flux:badge color="green" size="sm">Safe</flux:badge>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                            <div>
                                <flux:text class="text-gray-500 dark:text-gray-400">Already Used</flux:text>
                                <flux:text class="font-medium text-orange-600">{{ format_rupiah($budget->used_amount) }}
                                </flux:text>
                            </div>
                            <div>
                                <flux:text class="text-gray-500 dark:text-gray-400">Total Budget</flux:text>
                                <flux:text class="font-medium">{{ format_rupiah($budget->budget_amount) }}</flux:text>
                            </div>
                            <div>
                                <flux:text class="text-gray-500 dark:text-gray-400">Remaining Budget</flux:text>
                                <flux:text
                                    class="font-medium {{ $budget->remaining_amount < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ format_rupiah($budget->remaining_amount) }}
                                </flux:text>
                            </div>
                        </div>
                    </div>

                    <div class="text-right ml-4">
                        <flux:heading size="lg"
                            class="{{ $budget->percentage_used >= 100 ? 'text-red-600' : 'text-gray-900 dark:text-gray-100' }}">
                            {{ number_format(min($budget->percentage_used, 999), 1) }}%
                        </flux:heading>
                        <flux:text size="sm" class="text-gray-500">Used</flux:text>
                    </div>
                </div>

                {{-- Enhanced Progress Bar --}}
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <flux:text size="sm" class="text-gray-600 dark:text-gray-400">Progress</flux:text>
                        <flux:text size="sm" class="font-medium">
                            {{ format_rupiah($budget->used_amount) }} / {{ format_rupiah($budget->budget_amount) }}
                        </flux:text>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="bg-{{ $budget->progress_color }} h-3 rounded-full transition-all duration-300 flex items-center justify-end pr-1"
                            style="width: {{ min($budget->percentage_used, 100) }}%">
                            @if($budget->percentage_used > 10)
                            <div class="w-1 h-1 bg-white rounded-full opacity-75"></div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-2">
                    <flux:button wire:click="openEditModal({{ $budget->id }})" icon="pencil-square" variant="ghost"
                        size="sm" class="text-blue-600 hover:text-blue-700 hover:bg-blue-50">
                        Edit
                    </flux:button>

                    <flux:button wire:click="confirmDeleteBudget({{ $budget->id }})" icon="trash" variant="ghost"
                        size="sm" class="text-red-600 hover:text-red-700 hover:bg-red-50">
                        Delete
                    </flux:button>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <flux:icon.chart-bar class="size-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
                <flux:heading size="lg" class="text-gray-500 dark:text-gray-400 mb-2">
                    No Budget Yet
                </flux:heading>
                <flux:text class="text-gray-400 dark:text-gray-500 mb-4">
                    Start managing your finances by creating a budget for the {{ $currentPeriod }} period
                </flux:text>
                <flux:button wire:click="openCreateModal" icon="plus" variant="primary" size="sm">
                    Create First Budget
                </flux:button>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Enhanced Modal --}}
    <flux:modal name="budget-modal" variant="flyout" class="w-full max-w-lg">
        <div class="space-y-6 p-6">
            <div class="text-center border-b pb-4">
                <flux:heading size="xl" class="font-bold">
                    {{ $isEditing ? 'Edit Budget' : 'Create New Budget' }}
                </flux:heading>
                <flux:text class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ $isEditing ? 'Update your budget category' : 'Set spending limit for a specific category' }}
                </flux:text>
            </div>

            <form wire:submit="saveBudget" class="space-y-5">
                {{-- Category Selection --}}
                <div>
                    <flux:select wire:model="category_id" label="Select Category" placeholder="Select category...">
                        @foreach ($categories as $category)
                        <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>

                {{-- Period Selection --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <flux:select wire:model="month" label="Select Month" placeholder="Select month...">
                            <flux:select.option value="1">January</flux:select.option>
                            <flux:select.option value="2">February</flux:select.option>
                            <flux:select.option value="3">March</flux:select.option>
                            <flux:select.option value="4">April</flux:select.option>
                            <flux:select.option value="5">May</flux:select.option>
                            <flux:select.option value="6">June</flux:select.option>
                            <flux:select.option value="7">July</flux:select.option>
                            <flux:select.option value="8">August</flux:select.option>
                            <flux:select.option value="9">September</flux:select.option>
                            <flux:select.option value="10">October</flux:select.option>
                            <flux:select.option value="11">November</flux:select.option>
                            <flux:select.option value="12">December</flux:select.option>
                        </flux:select>
                    </div>

                    <div>
                        <flux:select wire:model="year" label="Select Year" placeholder="Select year...">
                            @foreach($years as $yr)
                            <flux:select.option value="{{ $yr }}">{{ $yr }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>

                {{-- Budget Amount --}}
                <div>
                    <livewire:widget.currency-input label="Budget Amount" name="budget"
                        placeholder="Enter budget amount" size="sm" />
                    @error('budget_amount')
                    <flux:heading class="text-red-400 flex space-x-1 mt-1">
                        <flux:icon.exclamation-triangle variant="micro" /> {{ $message }}
                    </flux:heading>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 pt-4 border-t">
                    <flux:button wire:click="closeModal" variant="ghost" class="flex-1">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" class="flex-1">
                        {{ $isEditing ? 'Update Budget' : 'Save Budget' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>