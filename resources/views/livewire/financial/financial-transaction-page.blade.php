<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <flux:heading size="xl">Manage Financial Transaction</flux:heading>

        <flux:modal.trigger name="add-transaction">
            <flux:button icon="plus" variant="primary">Add Transaction</flux:button>
        </flux:modal.trigger>
    </div>

    {{-- table transaction --}}
    <livewire:widget.flexible-table :model="App\Models\financial\FinancialTransaction::class" :columns="$columns"
        :searchable="$search" :actions="$actions" :filters="$filters"
        :date-filters="['transaction_date' => ['label' => 'Transaction Date']]" :per-page="10" :show-search="true"
        :show-per-page="true" :show-pagination="true" :show-filters="true" :sortable="['transaction_date']" />

    {{-- modal --}}
    <flux:modal name="add-transaction" variant="flyout" @close="clearForm">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $transactionId ? 'Update' : 'Add' }} Transaction</flux:heading>
                <flux:text class="mt-2">{{ $transactionId ? 'Update' : 'Add' }} new transaction.</flux:text>
            </div>
            <form wire:submit="saveTransaction" class="space-y-6">
                {{-- input select type --}}
                <flux:select wire:model.live="type" label="Type">
                    <flux:select.option>Choose type...</flux:select.option>
                    <flux:select.option value="income">Income</flux:select.option>
                    <flux:select.option value="expense">Expense</flux:select.option>
                    <flux:select.option value="transfer">Transfer</flux:select.option>
                </flux:select>

                {{-- input select category --}}
                <flux:select wire:model="categoryId" label="Category" :disabled="$type == null">
                    <flux:select.option>Choose category...</flux:select.option>
                    @foreach ($financialCategory as $category)
                    <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- input select account --}}
                <flux:select wire:model="accountId" label="From Account">
                    <flux:select.option>Choose account...</flux:select.option>
                    @foreach ($financialAccount as $account)
                    <flux:select.option value="{{ $account->id }}">{{ $account->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                {{-- input select to account (hanya muncul saat transfer) --}}
                @if ($type === 'transfer')
                <flux:select wire:model="toAccountId" label="To Account">
                    <flux:select.option>Choose destination account...</flux:select.option>
                    @foreach ($financialAccount as $account)
                    @if ($account->id != $accountId) {{-- jangan bisa transfer ke akun yang sama --}}
                    <flux:select.option value="{{ $account->id }}">{{ $account->name }}</flux:select.option>
                    @endif
                    @endforeach
                </flux:select>
                @endif

                {{-- input date --}}
                <flux:input type="date" wire:model="transactionDate" label="Select Date" />

                {{-- input amount --}}
                <livewire:widget.currency-input label="Transaction Amount" name="amount"
                    :error="$errors->first('amount')" size="sm" />

                {{-- input description --}}
                <flux:textarea wire:model="description" label="Description" placeholder="Enter description..." />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">{{ $transactionId ? 'Update' : 'Add' }} Transaction
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>