<?php

namespace App\Livewire\Financial;

use App\Models\financial\FinancialAccount;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FinancialAccountPage extends Component
{
    #[Validate('required')]
    public $name;

    #[Validate('required|in:bank,cash,ewallet,investment')]
    public $type;

    #[Validate('required|integer|min:0')]
    public $balance = 0;

    #[Validate('nullable')]
    public $accountNumber;

    public ?FinancialAccount $accountEdit = null;

    public function mount()
    {
        // Initialize balance as integer
        $this->balance = 0;
    }

    // Currency input now binds directly via wire:model on hidden input

    public function setEdit(FinancialAccount $account)
    {
        if($account) {
            $this->accountEdit = $account;
            $this->name = $account->name;
            $this->type = $account->type;
            $this->accountNumber = $account->account_number;
            
            // Parse balance untuk input currency (integer)
            $this->balance = $this->parseAmount($account->balance);
            
            Flux::modal('add-account')->show();
        }
    }

    public function saveAccount()
    {
        $this->validate();

        if($this->accountEdit) {
            $this->accountEdit->update([
                'name' => $this->name,
                'type' => $this->type,
                'balance' => $this->balance,
                'account_number' => $this->accountNumber
            ]);

            $this->dispatch('notification', type: 'success', message: 'Successfully updated the financial account');
        } else {
            FinancialAccount::create([
                'name' => $this->name,
                'type' => $this->type,
                'balance' => $this->balance,
                'account_number' => $this->accountNumber
            ]);

            $this->dispatch('notification', type: 'success', message: 'Successfully created a new financial account');
        }

        $this->clearFormAccount();
        Flux::modal('add-account')->close();
    }

    public function confirmDelete(FinancialAccount $account)
    {
        $this->dispatch('notification',
            type: 'warning',
            message: 'Are you sure you want to delete this account?',
            actionEvent: 'deleteAccount',
            actionParams: [$account]
        );
    }

    #[On('deleteAccount')]
    public function deleteAccount(FinancialAccount $account)
    {
        $account->delete();
        $this->dispatch('notification', type: 'success', message: 'Successfully deleted the financial account');
        $this->clearFormAccount();
    }

    public function clearFormAccount()
    {
        $this->reset(['name', 'type', 'balance', 'accountNumber']);
        $this->balance = 0; // Ensure it's always an integer
        $this->accountEdit = null;
    }

    /**
     * Parse amount from database to integer for currency input
     */
    private function parseAmount($amount)
    {
        if (is_null($amount) || $amount === '') {
            return 0;
        }
        
        // If it's already a number, return it
        if (is_numeric($amount)) {
            return (int) $amount;
        }
        
        // If it's a string with formatting, clean it
        $cleaned = str_replace(['.', ','], '', $amount);
        return (int) $cleaned;
    }

    public function render()
    {
        return view('livewire.financial.financial-account-page', [
            'accounts' => FinancialAccount::latest()->get(),
        ]);
    }
}
