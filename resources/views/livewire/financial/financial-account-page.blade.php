<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <flux:heading size="xl">Manage Your Financial Accounts</flux:heading>

        <flux:modal.trigger name="add-account">
            <flux:button icon="plus" variant="primary">Add Account</flux:button>
        </flux:modal.trigger>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        @foreach ($accounts as $account)
        @php
        $cardColor = match ($account->type) {
        'bank' => 'border-l-blue-600',
        'cash' => 'border-l-green-600',
        'ewallet' => 'border-l-purple-600',
        'investment' => 'border-l-orange-600',
        };

        $bgIconColor = match ($account->type) {
        'bank' => 'bg-blue-100',
        'cash' => 'bg-green-100',
        'ewallet' => 'bg-purple-100',
        'investment' => 'bg-orange-100',
        };

        $iconColor = match ($account->type) {
        'bank' => 'text-blue-600',
        'cash' => 'text-green-600',
        'ewallet' => 'text-purple-600',
        'investment' => 'text-orange-600',
        };

        $typeLower = match ($account->type) {
        'bank' => 'Bank',
        'cash' => 'Cash',
        'ewallet' => 'E-Wallet',
        'investment' => 'Investment'
        }
        @endphp

        <div class="p-6 bg-zinc-50 dark:bg-zinc-900 rounded-xl border-l-4 {{ $cardColor }} shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 {{ $bgIconColor }} rounded-full flex items-center justify-center">
                        <flux:icon.building-library variant="solid" class="{{ $iconColor }}" />
                    </div>
                    <div class="flex-none space-x-1">
                        <flux:heading size="lg">{{ $account->name }}</flux:heading>

                        <flux:text>{{ $typeLower }}</flux:text>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <flux:icon.pencil-square variant="mini"
                        class="text-accent hover:text-accent-content cursor-pointer" />
                    <flux:icon.trash variant="mini" class="text-red-600 hover:text-red-700 cursor-pointer" />
                </div>
            </div>

            <div class="text-right mt-4">
                <flux:heading size="xl">{{ format_rupiah($account->balance) }}</flux:heading>
                <flux:text>{{ mask_string($account->account_number, 5, 0) }}</flux:text>
            </div>
        </div>
        @endforeach
    </div>

    {{-- modal crud --}}
    <flux:modal name="add-account" variant="flyout">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Add Account</flux:heading>
                <flux:text class="mt-2">Add an account to manage your finances.</flux:text>
            </div>

            <form wire:submit="saveAccount" class="space-y-6">
                {{-- input name akun --}}
                <flux:input wire:model="name" label="Name" autocomplete="off"
                    placeholder="Enter name account in here..." />

                {{-- select type --}}
                <flux:select wire:model="type" label="Type">
                    <flux:select.option>Choose type...</flux:select.option>
                    <flux:select.option value="bank">Bank</flux:select.option>
                    <flux:select.option value="cash">Cash</flux:select.option>
                    <flux:select.option value="ewallet">E-Wallet</flux:select.option>
                    <flux:select.option value="investment">Investment</flux:select.option>
                </flux:select>

                {{-- input total saldo --}}
                {{--
                <flux:input type="number" wire:model="balance" autocomplete="off" label="Total Balance"
                    placeholder="Enter total balance in here..." /> --}}
                <livewire:widget.currency-input label="Total Balance" name="balance" :error="$errors->first('balance')"
                    size="sm" />

                {{-- input no akun --}}
                <flux:input type="number" wire:model="accountNumber" autocomplete="off"
                    label="Account Number (opsional)" placeholder="Enter your account number in here..." />

                <div class="flex">
                    <flux:spacer />
                    <flux:button type="submit" variant="primary">Save</flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>