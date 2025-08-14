<?php

namespace App\Livewire\Financial;

use App\Models\financial\FinancialAccount;
use Flux\Flux;
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

    public function saveAccount()
    {
        $this->validate();

        FinancialAccount::create([
            'name' => $this->name,
            'type' => $this->type,
            'balance' => $this->balance,
            'account_number' => $this->accountNumber
        ]);

        $this->dispatch('notification', type: 'success', message: 'Successfully created a new financial account');
        $this->reset(['name', 'type', 'balance', 'accountNumber']);
        Flux::modal('add-account')->close();
    }
    
    public function render()
    {
        return view('livewire.financial.financial-account-page', [
            'accounts' => FinancialAccount::latest()->get(),
        ]);
    }
}
