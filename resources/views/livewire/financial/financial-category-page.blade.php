<div class="space-y-6">
    <flux:heading size="xl">Manage Financial Categories</flux:heading>

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
            <div class="flex justify-end pt-2">
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        @foreach ($categories as $category)
        <div class="p-6 rounded-xl bg-zinc-50 dark:bg-zinc-900 border border-zinc-400 dark:border-zinc-600">
            <div class="flex justify-between items-center">
                <flux:heading>{{ $category->name }}</flux:heading>

                @php
                $typeColor = match($category->type) {
                'income' => 'green',
                'expense' => 'red',
                default => 'gray'
                };

                $typeChar = match ($category->type) {
                'income' => 'Income',
                'expense' => 'Expense',
                }
                @endphp
                <flux:badge color="{{ $typeColor }}">{{ $typeChar }}</flux:badge>
            </div>

            <div class="flex items-center space-x-3 mt-5">
                <flux:button variant="primary">Edit</flux:button>
                <flux:button variant="danger">Delete</flux:button>
            </div>
        </div>
        @endforeach
    </div>
</div>