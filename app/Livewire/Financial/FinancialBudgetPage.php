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
    // Edit state
    public ?FinancialBudget $editingBudget = null;
    public bool $isEditing = false;
    
    // Form properties
    public $categories;
    public $years = [];

    #[Validate('required', message: 'Please select a category')]
    public $category_id = '';

    #[Validate('required', message: 'Please select a month')]
    public $month = '';

    #[Validate('required', message: 'Please select a year')]
    public $year = '';

    #[Validate('required|numeric|min:1000', message: 'Budget amount must be at least Rp 1.000')]
    public $budget_amount = '';

    // Filter properties
    public $filterMonth;
    public $filterYear;

    public function mount()
    {
        $this->initializeData();
        $this->setDefaultFilters();
    }

    private function initializeData()
    {
        $this->categories = FinancialCategory::where('type', 'expense')->orderBy('name')->get();
        $this->generateYearOptions();
    }

    private function generateYearOptions()
    {
        $currentYear = now()->year;
        // Generate dari tahun sekarang sampai 10 tahun ke belakang
        $this->years = range($currentYear, $currentYear - 10);
    }

    private function setDefaultFilters()
    {
        $this->filterMonth = now()->month;
        $this->filterYear = now()->year;
    }

    // Currency input now binds directly via wire:model on hidden input

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        Flux::modal('budget-modal')->show();
    }

    public function openEditModal($budgetId)
    {
        $budget = FinancialBudget::findOrFail($budgetId);
        
        $this->editingBudget = $budget;
        $this->isEditing = true;
        $this->category_id = $budget->financial_category_id;
        $this->month = $budget->month;
        $this->year = $budget->year;
        $this->budget_amount = $budget->amount;
        
        // Update currency input dengan format yang benar
        $this->budget_amount = (int) $budget->amount;
        
        Flux::modal('budget-modal')->show();
    }

    public function saveBudget()
    {
        $this->validate();

        // Cek apakah sudah ada budget untuk kategori, bulan, dan tahun yang sama
        $existingBudget = FinancialBudget::where('financial_category_id', $this->category_id)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->when($this->isEditing, fn($q) => $q->where('id', '!=', $this->editingBudget->id))
            ->first();

        if ($existingBudget) {
            $this->addError('category_id', 'Budget for this category in the selected month and year already exists.');
            return;
        }

        $data = [
            'financial_category_id' => $this->category_id,
            'month' => $this->month,
            'year' => $this->year,
            'amount' => $this->budget_amount,
        ];

        if ($this->isEditing) {
            $this->editingBudget->update($data);
            $message = 'Budget category successfully updated!';
        } else {
            FinancialBudget::create($data);
            $message = 'Budget category successfully created!';
        }

        $this->dispatch('notification', type: 'success', message: $message);
        $this->closeModal();
    }

    public function confirmDeleteBudget($budgetId)
    {
        $budget = FinancialBudget::findOrFail($budgetId);

        if ($budget) {
            $this->dispatch('notification', 
                type: 'warning', 
                message: "Are you sure you want to delete the category budget ". $budget->category->name . "?",
                actionEvent: 'deleteBudget',
                actionParams: [$budgetId]
            );
        }
    }

    #[On('deleteBudget')]
    public function deleteBudget($budgetId)
    {
        $budget = FinancialBudget::findOrFail($budgetId);
        $categoryName = $budget->category->name;
        
        $budget->delete();
        
        $this->dispatch('notification', 
            type: 'success', 
            message: "Budget category {$categoryName} successfully deleted!"
        );
    }

    public function closeModal()
    {
        $this->resetForm();
        Flux::modal('budget-modal')->close();
    }

    private function resetForm()
    {
        $this->reset([
            'category_id', 'month', 'year', 'budget_amount', 
            'editingBudget', 'isEditing'
        ]);
        $this->resetValidation();
    }

    public function updatedFilterMonth()
    {
        // Auto refresh data ketika filter berubah
    }

    public function updatedFilterYear()
    {
        // Auto refresh data ketika filter berubah
    }

    private function getBudgetData()
    {
        return FinancialBudget::with('category')
            ->where('month', $this->filterMonth)
            ->where('year', $this->filterYear)
            ->get()
            ->map(function ($budget) {
                $usedAmount = $budget->used_amount;
                $budgetAmount = $budget->amount;
                $percentage = $budgetAmount > 0 ? ($usedAmount / $budgetAmount * 100) : 0;
                $remaining = $budgetAmount - $usedAmount;

                return (object) [
                    'id' => $budget->id,
                    'category' => $budget->category,
                    'used_amount' => $usedAmount,
                    'budget_amount' => $budgetAmount,
                    'remaining_amount' => $remaining,
                    'percentage_used' => $percentage,
                    'status' => $this->getBudgetStatus($percentage),
                    'progress_color' => $this->getProgressColor($percentage),
                ];
            });
    }

    private function getBudgetStatus($percentage)
    {
        if ($percentage >= 100) return 'over_budget';
        if ($percentage >= 90) return 'critical';
        if ($percentage >= 70) return 'warning';
        return 'safe';
    }

    private function getProgressColor($percentage)
    {
        if ($percentage >= 100) return 'red-600';
        if ($percentage >= 90) return 'red-500';
        if ($percentage >= 70) return 'yellow-500';
        return 'green-500';
    }

    private function getSummaryData($budgets)
    {
        $totalBudget = $budgets->sum('budget_amount');
        $totalUsed = $budgets->sum('used_amount');
        $totalRemaining = $totalBudget - $totalUsed;
        $overallPercentage = $totalBudget > 0 ? ($totalUsed / $totalBudget * 100) : 0;

        return [
            'total_budget' => $totalBudget,
            'total_used' => $totalUsed,
            'total_remaining' => $totalRemaining,
            'overall_percentage' => $overallPercentage,
            'budget_count' => $budgets->count(),
            'over_budget_count' => $budgets->where('status', 'over_budget')->count(),
            'critical_count' => $budgets->where('status', 'critical')->count(),
        ];
    }

    public function render()
    {
        $budgets = $this->getBudgetData();
        $summary = $this->getSummaryData($budgets);
        
        // Get month and year names for display
        $monthName = $this->getMonthName($this->filterMonth);
        $currentPeriod = "{$monthName} {$this->filterYear}";

        return view('livewire.financial.financial-budget-page', compact(
            'budgets', 'summary', 'currentPeriod'
        ));
    }

    private function getMonthName($monthNumber)
    {
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March',
            4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September',
            10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return $months[$monthNumber] ?? 'Unknown';
    }
}