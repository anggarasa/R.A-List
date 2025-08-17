<?php

namespace App\Livewire\Financial;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\financial\FinancialAccount;
use App\Models\financial\FinancialCategory;
use App\Models\financial\FinancialTransaction;

class FinancialTransactionPage extends Component
{
    public $financialCategory;
    public $financialAccount;

    #[Validate('required')]
    public $categoryId;

    #[Validate('required')]
    public $accountId;

    #[Validate('required|in:income,expense,transfer')]
    public $type;

    #[Validate('required|numeric')]
    public $amount;

    #[Validate('required')]
    public $description;

    #[Validate('required|date')]
    public $transactionDate;

    public function mount()
    {
        $this->financialCategory = FinancialCategory::all();
        $this->financialAccount = FinancialAccount::all();
    }

    #[On('currency-updated')]
    public function handleCurrencyUpdate($data)
    {
        if ($data['name'] === 'amount') {
            $this->amount = $data['value'];
        }
    }

    public function saveTransaction()
    {
        $this->validate();

        FinancialTransaction::create([
            'financial_category_id' => $this->categoryId,
            'financial_account_id' => $this->accountId,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'transaction_date' => $this->transactionDate,
        ]);

        $this->dispatch('notification', type: 'success', message: 'Transaction saved successfully');
        $this->dispatch('clear-input-currency');
        $this->reset(['categoryId', 'accountId', 'type', 'amount', 'description', 'transactionDate']);
        Flux::modal('add-transaction')->close();
    }

    // Start manage view flexible table transaction
    public $columns = [
        'transaction_date' => ['label' => 'Transaction Date', 'format' => 'date'],
        'description' => ['label' => 'Description'],
        'financial_category_id' => ['label' => 'Category Name', 'relation' => 'category.name'],
        'financial_account_id' => ['label' => 'Account Name', 'relation' => 'account.name'],
        'type' => [
            'label' => 'Type',
            'format' => 'badge',
            'badge_colors' => [
                'income' => 'green',
                'expense' => 'red',
                'transfer' => 'blue',
            ],
            'badge_labels' => [
                'income' => 'Income',
                'expense' => 'Expense',
                'transfer' => 'Transfer',
            ],
        ],
        'amount' => ['label' => 'Amount', 'format' => 'currency'],
    ];

    public $filters = [
        'type' => [
            'label' => 'Type'
        ],
        'financial_category_id' => [
            'label' => 'Category',
            'relation' => 'category',
            'display_field' => 'name'
        ],
        'financial_account_id' => [
            'label' => 'Account',
            'relation' => 'account',
            'display_field' => 'name'
        ]
    ];

    public $search = ['description'];

    public $actions = [
        [
            'method' => 'edit',
            'label' => 'Edit',
            'class' => 'bg-lime-400 text-black hover:bg-lime-600 cursor-pointer'
        ],
        [
            'method' => 'confirmDelete',
            'label' => 'Delete',
            'class' => 'text-white bg-red-600 hover:bg-red-700 cursor-pointer',
            'confirm' => 'Are you sure you want to delete this category?'
        ]
    ];
    // End manage view flexible table transaction
    
    public function render()
    {
        return view('livewire.financial.financial-transaction-page');
    }
}
