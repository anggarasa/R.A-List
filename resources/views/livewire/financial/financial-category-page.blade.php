<div class="space-y-10">
    <div class="flex items-center space-x-3">
        <a href="{{ route('financial-page') }}" wire:navigate>
            <flux:icon.arrow-left />
        </a>
        <flux:heading size="xl">Manage Financial Categories</flux:heading>
    </div>

    <div
        class="p-6 space-y-4 bg-zinc-50 rounded-xl shadow border border-zinc-400 dark:bg-zinc-900 dark:border-zinc-600">
        <flux:heading size="lg">Form Category</flux:heading>

        <form wire:submit="saveCategory" class="space-y-4">
            <!-- Input Category Name -->
            <flux:input wire:model="name" label="Category Name" autocomplete="off" class="w-full"
                placeholder="Enter category name..." />

            <!-- Select Category Type -->
            <flux:select wire:model="type" label="Category Type" class="w-full">
                <flux:select.option>Choose category...</flux:select.option>
                <flux:select.option value="income">Income</flux:select.option>
                <flux:select.option value="expense">Expense</flux:select.option>
            </flux:select>

            <!-- Save Button -->
            <div class="flex justify-end pt-2 space-x-3">
                <flux:button wire:click="clearForm">Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </div>

    <livewire:widget.flexible-table :model="App\Models\financial\FinancialCategory::class" :columns="$columns"
        :searchable="$search" :sortable="$sortable" :actions="$actions" :filters="$filter" :per-page="10"
        :show-search="true" :show-per-page="true" :show-pagination="true" :show-filters="true" />
</div>