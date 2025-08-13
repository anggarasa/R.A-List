<div class="space-y-10">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <flux:heading size="xl">Manage Your Financial Accounts</flux:heading>

        <flux:modal.trigger name="add-account">
            <flux:button icon="plus" variant="primary">Add Account</flux:button>
        </flux:modal.trigger>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="p-6 bg-zinc-50 dark:bg-zinc-900 rounded-xl border-l-4 border-l-blue-600 shadow">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <flux:icon.building-library variant="solid" class="text-blue-600" />
                    </div>
                    <div class="flex-none space-x-1">
                        <flux:heading size="lg">nama akun</flux:heading>

                        <flux:text>Bank</flux:text>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <flux:icon.pencil-square variant="mini"
                        class="text-accent hover:text-accent-content cursor-pointer" />
                    <flux:icon.trash variant="mini" class="text-red-600 hover:text-red-700 cursor-pointer" />
                </div>
            </div>

            <div class="text-right mt-4">
                <flux:heading size="xl">Rp 2.000.000</flux:heading>
                <flux:text>1234***</flux:text>
            </div>
        </div>
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
                <flux:input type="number" wire:model="balance" autocomplete="off" label="Total Balance"
                    placeholder="Enter total balance in here..." />

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