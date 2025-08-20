<?php

namespace App\Livewire\Financial;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Models\financial\FinancialBudget;
use App\Models\financial\FinancialCategory;
use Flux\Flux;

class FinancialBudgetPage extends Component
{
    public $categories;
    public $years = [];

    #[Validate('required')]
    public $category_id;

    #[Validate('required')]
    public $month;

    #[Validate('required')]
    public $year;

    #[Validate('required|numeric')]
    public $budget;

    public function mount()
    {
        $this->categories = FinancialCategory::all();

        $currentYear = now()->year; // contoh: 2025
        $this->years = range($currentYear, $currentYear - 10); // 2025 → 2015
    }

    #[On('currency-updated')]
    public function handleCurrencyUpdate($data)
    {
        if ($data['name'] === 'budget') {
            $this->budget = $data['value'];
        }
    }

    public function saveBudget()
    {
        $this->validate();

        FinancialBudget::create([
            'financial_category_id' => $this->category_id,
            'month' => $this->month,
            'year' => $this->year,
            'amount' => $this->budget,
        ]);

        $this->dispatch('notification', type: 'success', message: 'Successfully create a budget');
        $this->clearForm();
    }

    public function clearForm()
    {
        $this->reset(['category_id', 'month', 'year', 'budget']);
        $this->dispatch('clear-input-currency');
        Flux::modal('add-budget')->close();
    }

    public function render()
    {
        return view('livewire.financial.financial-budget-page');
    }
}
