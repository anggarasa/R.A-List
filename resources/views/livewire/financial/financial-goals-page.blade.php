<div class="space-y-8">
    {{-- Header Section with Statistics --}}
    <div class="bg-gradient-to-r bg-zinc-100 dark:bg-zinc-900/20 p-6 rounded-lg">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
            <div>
                <flux:heading size="xl">
                    Manage Your Financial Goals
                </flux:heading>
                <flux:text class="mt-2">
                    Set and track your financial goals
                </flux:text>
            </div>

            {{-- Quick Statistics --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 min-w-0 lg:min-w-96">
                <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg shadow-sm">
                    <flux:text size="sm" class="text-gray-600 dark:text-gray-400">Total Goals</flux:text>
                    <flux:heading size="lg" class="text-blue-600">{{ $this->goalsStatistics['total_goals'] }}
                    </flux:heading>
                </div>
                <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg shadow-sm">
                    <flux:text size="sm" class="text-gray-600 dark:text-gray-400">Active Goals</flux:text>
                    <flux:heading size="lg" class="text-green-600">{{ $this->goalsStatistics['active_goals'] }}
                    </flux:heading>
                </div>
                <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg shadow-sm">
                    <flux:text size="sm" class="text-gray-600 dark:text-gray-400">Completed Goals</flux:text>
                    <flux:heading size="lg" class="text-purple-600">{{ $this->goalsStatistics['completed_goals'] }}
                    </flux:heading>
                </div>
                <div class="bg-white dark:bg-zinc-800 p-3 rounded-lg shadow-sm">
                    <flux:text size="sm" class="text-gray-600 dark:text-gray-400">Average Progress</flux:text>
                    <flux:heading size="lg" class="text-indigo-600">{{
                        number_format($this->goalsStatistics['average_progress'], 1) }}%</flux:heading>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        {{-- Filters and Sorting --}}
        <div class="flex items-center gap-3">
            <flux:text class="font-medium">Filter:</flux:text>
            <div class="flex gap-2">
                <flux:button wire:click="setFilter('all')" size="sm"
                    variant="{{ $filterStatus === 'all' ? 'primary' : 'ghost' }}">
                    All
                </flux:button>
                <flux:button wire:click="setFilter('active')" size="sm"
                    variant="{{ $filterStatus === 'active' ? 'primary' : 'ghost' }}">
                    Active
                </flux:button>
                <flux:button wire:click="setFilter('completed')" size="sm"
                    variant="{{ $filterStatus === 'completed' ? 'primary' : 'ghost' }}">
                    Completed
                </flux:button>
            </div>
        </div>

        {{-- Add New Goal Button --}}
        <flux:modal.trigger name="add-goal">
            <flux:button icon="plus" variant="primary" class="shadow-lg">
                {{ $isEditMode ? 'Edit Goal' : 'Add New Goal' }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    {{-- Goals Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse ($this->filteredGoals as $goal)
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden
                    {{ $goal->status === 'completed' ? 'ring-2 ring-green-200 dark:ring-green-800' : '' }}">

            {{-- Goal Header --}}
            <div class="p-6 pb-4">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <flux:heading size="lg" class="mb-1">
                            {{ $goal->name }}
                        </flux:heading>
                        @if($goal->description)
                        <flux:text size="sm" class="line-clamp-2">
                            {{ $goal->description }}
                        </flux:text>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 ml-4">
                        @php
                        $badge = match (true) {
                        $goal->status === 'completed' => ['color' => 'green', 'icon' => 'check-circle', 'text' =>
                        'Completed'],
                        $goal->days_left < 0=> ['color' => 'red', 'icon' => 'exclamation-triangle','text' => 'Late'],
                            $goal->days_left <= 30=> ['color' => 'yellow', 'icon' => 'clock', 'text' => 'Urgent'],
                                default => ['color' => 'blue', 'icon' => 'flag', 'text' => 'Active'],
                                };
                                @endphp

                                <flux:badge color="{{ $badge['color'] }}" icon="{{ $badge['icon'] }}">
                                    {{ $badge['text'] }}
                                </flux:badge>
                    </div>
                </div>

                {{-- Target Date Info --}}
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <div class="flex items-center gap-1 mb-2">
                        <flux:icon.calendar-days variant="micro" />
                        <span>Target: {{ \Carbon\Carbon::parse($goal->target_date)->format('d M Y') }}</span>
                    </div>

                    @if($goal->days_left_human >= 0)
                    <span class="text-blue-600 dark:text-blue-400 font-medium">
                        ({{ $goal->days_left_human }})
                    </span>
                    @else
                    <span class="text-red-600 dark:text-red-400 font-medium">
                        ({{ $goal->days_left_human }})
                    </span>
                    @endif
                </div>

                {{-- Progress Information --}}
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <flux:text size="sm" class="font-medium">
                            Progress
                        </flux:text>
                        <flux:text size="sm" class="font-bold">
                            {{ min(number_format($goal->progress_percentage, 1), 100) }}%
                        </flux:text>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="w-full bg-zinc-200 dark:bg-zinc-700 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-500 relative"
                            style="width: {{ min($goal->progress_percentage, 100) }}%">
                            @if($goal->progress_percentage > 15)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <flux:text size="xs" class="text-white font-medium">
                                    {{ min(number_format($goal->progress_percentage, 0), 100) }}%
                                </flux:text>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Amount Information --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <flux:text size="sm">Collected:</flux:text>
                            <flux:text size="sm" class="font-semibold text-green-600 dark:text-green-400">
                                Rp {{ number_format($goal->current_amount, 0, ',', '.') }}
                            </flux:text>
                        </div>
                        <div class="flex justify-between items-center">
                            <flux:text size="sm">Collected:</flux:text>
                            <flux:text size="sm" class="font-semibold text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
                            </flux:text>
                        </div>
                        @if($goal->remaining_amount > 0)
                        <div
                            class="flex justify-between items-center pt-2 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:text size="sm">Remaining:</flux:text>
                            <flux:text size="sm" class="font-bold text-orange-600 dark:text-orange-400">
                                Rp {{ number_format($goal->remaining_amount, 0, ',', '.') }}
                            </flux:text>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="bg-zinc-50 dark:bg-zinc-950 px-6 py-4">
                <div class="flex justify-between items-center">
                    @if($goal->status !== 'completed')
                    <flux:button icon="check-circle" wire:click="markAsCompleted({{ $goal->id }})" variant="primary"
                        size="sm" class="mr-auto">
                        Finish it
                    </flux:button>
                    @else
                    <div></div>
                    @endif

                    <div class="flex items-center gap-2">
                        <flux:button wire:click="editGoal({{ $goal->id }})" icon="pencil-square" variant="ghost"
                            size="sm" class="text-blue-600 hover:text-blue-700">
                            Edit
                        </flux:button>

                        <flux:button wire:click="confirmDelete({{ $goal->id }})" icon="trash" variant="ghost" size="sm"
                            class="text-red-600 hover:text-red-700">
                            Delete
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Empty State --}}
        <div class="col-span-full">
            <div class="text-center py-12">
                <div
                    class="mx-auto w-24 h-24 bg-zinc-100 dark:bg-zinc-900/20 rounded-full flex items-center justify-center mb-4">
                    <flux:icon.flag class="w-12 h-12 text-zinc-600 dark:text-zinc-400" />
                </div>
                <flux:heading size="lg" class="text-zinc-900 dark:text-white mb-2">
                    No Financial Goals Set
                </flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400 mb-6">
                    Set a financial goal to start saving and achieve your financial future
                </flux:text>
                <flux:modal.trigger name="add-goal">
                    <flux:button variant="primary" icon="plus">
                        Set Financial Goal
                    </flux:button>
                </flux:modal.trigger>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Add/Edit Goal Modal --}}
    <flux:modal name="add-goal" variant="flyout" @close="clearForm">
        <div class="space-y-6">
            <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
                <flux:heading size="lg">{{ $this->getModalTitle() }}</flux:heading>
                <flux:text class="mt-2 text-zinc-600 dark:text-zinc-400">
                    {{ $isEditMode ? 'Update financial goal information' : 'Set a new financial goal for a better
                    future' }}
                </flux:text>
            </div>

            <form wire:submit="saveGoal" class="space-y-6">
                {{-- Goal Name --}}
                <div>
                    <flux:input wire:model.live="name" label="Goal Name"
                        placeholder="e.g: Emergency Fund, Buy a House, Family Vacation"
                        description="Give a clear and specific name for your goal" />
                </div>

                {{-- Description --}}
                <div>
                    <flux:textarea wire:model="description" label="Description (Optional)"
                        placeholder="Describe your goal in detail..." rows="3" />
                </div>

                {{-- Target Amount --}}
                <div>
                    <livewire:widget.currency-input :id="'target_amount'" :label="'Target Amount'" name="target_amount"
                        wire:key="target_amount" :error="$errors->first('target_amount')"
                        placeholder="Enter the amount you want to save" size="sm" />
                    @error('target_amount')
                    <flux:text size="sm" class="text-red-600 dark:text-red-400 mt-1 flex items-center gap-1">
                        <flux:icon.exclamation-triangle variant="micro" />
                        {{ $message }}
                    </flux:text>
                    @enderror
                </div>

                {{-- Current Amount --}}
                <div>
                    <livewire:widget.currency-input :id="'current_amount'" :label="'Current Amount'"
                        name="current_amount" wire:key="current_amount" :error="$errors->first('current_amount')"
                        placeholder="Enter the amount you already have" size="sm" />
                    @error('current_amount')
                    <flux:text size="sm" class="text-red-600 dark:text-red-400 mt-1 flex items-center gap-1">
                        <flux:icon.exclamation-triangle variant="micro" />
                        {{ $message }}
                    </flux:text>
                    @enderror
                </div>

                {{-- Target Date --}}
                <div>
                    <flux:input type="date" wire:model.live="target_date" label="Target Date"
                        description="When do you want to achieve this goal?"
                        min="{{ now()->addDay()->format('Y-m-d') }}" />
                </div>

                {{-- Preview Calculation --}}
                @if($target_amount > 0 && $target_date)
                <div class="bg-zinc-50 dark:bg-zinc-900/20 p-4 rounded-lg">
                    <flux:heading size="sm">
                        📈 Preview Calculation
                    </flux:heading>
                    @php
                    $remaining = $target_amount - ($current_amount ?? 0);
                    $daysLeft = now()->diffInDays($target_date, false);
                    $monthsLeft = max(1, round($daysLeft / 30));
                    $weeklyTarget = $daysLeft > 0 ? $remaining / ($daysLeft / 7) : 0;
                    $monthlyTarget = $remaining / $monthsLeft;
                    @endphp

                    @if($remaining > 0 && $daysLeft > 0)
                    <div class="space-y-1 text-sm">
                        <flux:text class="text-blue-800 dark:text-blue-200">
                            Need to save <strong>Rp {{ number_format($monthlyTarget, 0, ',', '.') }}</strong> per month
                        </flux:text>
                        <flux:text class="text-blue-700 dark:text-blue-300">
                            Or <strong>Rp {{ number_format($weeklyTarget, 0, ',', '.') }}</strong> per week
                        </flux:text>
                    </div>
                    @elseif($remaining <= 0) <flux:text size="sm" class="text-green-700 dark:text-green-300">
                        🎉 Target already reached!
                        </flux:text>
                        @endif
                </div>
                @endif

                {{-- Modal Actions --}}
                <div class="flex justify-end gap-3 pt-4">
                    <flux:button type="button" variant="ghost" @click="$wire.clearForm()">
                        Cancel
                    </flux:button>
                    <flux:button type="submit" variant="primary" :disabled="!$name || !$target_amount || !$target_date">
                        {{ $this->getSubmitButtonText() }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>