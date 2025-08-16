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

    #[Validate('required|integer')]
    public $balance;

    #[Validate('nullable')]
    public $accountNumber;

    public ?FinancialAccount $accountEdit = null;

    #[On('currency-updated')]
    public function handleCurrencyUpdate($data)
    {
        if ($data['name'] === 'balance') {
            $this->balance = $data['value'];
        }
    }

    public function setEdit(FinancialAccount $account)
    {
        if($account) {
            $this->accountEdit = $account;
            $this->name = $account->name;
            $this->type = $account->type;
            $this->accountNumber = $account->account_number;
            $this->dispatch('update-value-input-currency', number_format($account->balance, 0, '.', ','));
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

            $this->dispatch('clear-input-currency');
            $this->dispatch('notification', type: 'success', message: 'Successfully updated the financial account');
        } else {
            FinancialAccount::create([
                'name' => $this->name,
                'type' => $this->type,
                'balance' => $this->balance,
                'account_number' => $this->accountNumber
            ]);

            $this->dispatch('clear-input-currency');
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
        $this->accountEdit = null;
        $this->dispatch('clear-input-currency');
    }

    public function render()
    {
        return view('livewire.financial.financial-account-page', [
            'accounts' => FinancialAccount::latest()->get(),
        ]);
    }
}
