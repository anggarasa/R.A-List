<?php

namespace App\Livewire\Financial;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Livewire\Widget\FlexibleTable;
use App\Models\financial\FinancialAccount;
use App\Models\financial\FinancialCategory;
use App\Models\financial\FinancialTransaction;

class FinancialTransactionPage extends Component
{
    public $transactionId;
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

    #[On('update-data-table')]
    public function edit($data)
    {
        if($data) {
            $this->transactionId = $data['id'];
            $this->categoryId = $data['financial_category_id'];
            $this->accountId = $data['financial_account_id'];
            $this->type = $data['type'];
            $this->dispatch('update-value-input-currency', number_format($data['amount'], 0, '.', ','));
            $this->description = $data['description'];
            $this->transactionDate = date('Y-m-d', strtotime($data['transaction_date']));

            Flux::modal('add-transaction')->show();
        }
    }

    public function saveTransaction()
    {
        if($this->type == 'transfer') {
            $this->dispatch('notification', type: 'error', message: 'Transfer type not allowed, please choose income or expense');
            Flux::modal('add-transaction')->close();
            return;
        }

        $this->validate();

        if($this->transactionId) {
            $transaction = FinancialTransaction::find($this->transactionId);

            $transaction->update([
                'financial_category_id' => $this->categoryId,
                'financial_account_id' => $this->accountId,
                'type' => $this->type,
                'amount' => $this->amount,
                'description' => $this->description,
                'transaction_date' => $this->transactionDate,
            ]);

            $this->dispatch('notification', type: 'success', message: 'Transaction updated successfully');
        } else {
            FinancialTransaction::create([
                'financial_category_id' => $this->categoryId,
                'financial_account_id' => $this->accountId,
                'type' => $this->type,
                'amount' => $this->amount,
                'description' => $this->description,
                'transaction_date' => $this->transactionDate,
            ]);

            $this->dispatch('notification', type: 'success', message: 'Transaction saved successfully');
        }
        $this->clearForm();
        $this->dispatch('refresh-table')->to(FlexibleTable::class);
        Flux::modal('add-transaction')->close();
    }

    public function clearForm()
    {
        $this->reset(['transactionId', 'categoryId', 'accountId', 'type', 'amount', 'description', 'transactionDate']);
        $this->dispatch('clear-input-currency');
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
