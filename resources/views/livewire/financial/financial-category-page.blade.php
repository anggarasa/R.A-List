<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('financial.dashboard') }}" wire:navigate>
                <flux:icon.arrow-left />
            </a>
            <flux:heading size="xl">Manage Financial Categories</flux:heading>
        </div>

        <flux:modal.trigger name="add-financial-category">
            <flux:button icon="plus" variant="primary" class="w-full sm:w-auto">
                Add Category
            </flux:button>
        </flux:modal.trigger>
    </div>


    {{-- table category --}}
    <livewire:widget.flexible-table :model="App\Models\financial\FinancialCategory::class" :columns="$columns"
        :searchable="$search" :sortable="$sortable" :actions="$actions" :filters="$filter" :per-page="10"
        :show-search="true" :show-per-page="true" :show-pagination="true" :show-filters="true" />

    {{-- modal add --}}
    <flux:modal name="add-financial-category" @close="clearForm" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $categoryId ? 'Update' : 'Add' }} Category</flux:heading>
                <flux:text class="mt-2">{{ $categoryId ? 'Change categories to organize your finances' : 'Add new
                    categories to manage your finances.' }}</flux:text>
            </div>
            <form wire:submit="saveCategory" class="space-y-6">
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
                    <flux:button type="submit" variant="primary">{{ $categoryId ? 'Change' : 'Save' }}</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>