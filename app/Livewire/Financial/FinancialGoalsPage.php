<?php

namespace App\Livewire\Financial;

use App\Models\financial\FinancialGoal;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;
use PhpParser\Node\Expr\FuncCall;

class FinancialGoalsPage extends Component
{
    // Form properties with clear validation rules
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|numeric|min:1')]
    public $target_amount = 0;

    #[Validate('nullable|numeric|min:0')]
    public $current_amount = 0;

    #[Validate('required|date|after:today')]
    public $target_date = '';

    #[Validate('nullable|string|max:500')]
    public $description = '';

    // Modal state
    public $isEditMode = false;
    public $editingGoalId = null;

    // Sorting and filtering
    public $sortBy = 'target_date';
    public $sortDirection = 'asc';
    public $filterStatus = 'all';

    /**
     * Handle currency input updates from custom component
     */
    #[On('currency-updated')]
    public function handleCurrencyUpdate($data)
    {
        if ($data['name'] === 'target_amount') {
            $this->target_amount = $data['value'];
        } 
        
        if ($data['name'] === 'current_amount') {
            $this->current_amount = $data['value'];
        }
    }

    /**
     * Save or update financial goal
     */
    public function saveGoal()
    {
        $this->validate();

        try {
            if ($this->isEditMode && $this->editingGoalId) {
                $this->updateExistingGoal();
            } else {
                $this->createNewGoal();
            }

            $this->showSuccessMessage();
            $this->clearForm();
            
        } catch (\Exception $e) {
            $this->dispatch('notification', 
                type: 'error', 
                message: 'An error occurred while saving the financial goal.'
            );
        }
    }

    /**
     * Create new financial goal
     */
    private function createNewGoal()
    {
        FinancialGoal::create([
            'name' => $this->name,
            'target_amount' => $this->target_amount,
            'current_amount' => $this->current_amount ?? 0,
            'target_date' => $this->target_date,
            'description' => $this->description,
            'status' => 'active'
        ]);
    }

    /**
     * Update existing financial goal
     */
    private function updateExistingGoal()
    {
        $goal = FinancialGoal::findOrFail($this->editingGoalId);
        
        $goal->update([
            'name' => $this->name,
            'target_amount' => $this->target_amount,
            'current_amount' => $this->current_amount ?? 0,
            'target_date' => $this->target_date,
            'description' => $this->description,
        ]);
    }

    /**
     * Edit existing goal
     */
    public function editGoal($goalId)
    {
        $goal = FinancialGoal::findOrFail($goalId);
        
        $this->isEditMode = true;
        $this->editingGoalId = $goalId;
        $this->name = $goal->name;
        $this->target_date = $goal->target_date->format('Y-m-d');
        $this->description = $goal->description;
        
        $this->dispatch('update-value-input-currency', 
            value: number_format($goal->current_amount, 0, '.', ','),
            targetId: 'current_amount'
        );
        
        $this->dispatch('update-value-input-currency', 
            value: number_format($goal->target_amount, 0, '.', ','),
            targetId: 'target_amount'
        );

        Flux::modal('add-goal')->show();
    }

    /**
     * Delete financial goal with confirmation
     */
    public function confirmDelete($goalId)
    {
        $goal = FinancialGoal::find($goalId);

        $this->dispatch('notification', 
            type: 'warning', 
            message: 'Are you sure you want to delete the goal \'' . $goal->name . '\'?',
            actionEvent: 'deleteGoal',
            actionParams: [$goalId]
        );
    }

    #[On('deleteGoal')]
    public function deleteGoal($goalId)
    {
        try {
            FinancialGoal::findOrFail($goalId)->delete();
            
            $this->dispatch('notification', 
                type: 'success', 
                message: 'Financial goal successfully cleared'
            );
        } catch (\Exception $e) {
            $this->dispatch('notification', 
                type: 'error', 
                message: 'Failed to remove financial goals'
            );
        }
    }

    /**
     * Update current amount for quick updates
     */
    public function updateCurrentAmount($goalId, $amount)
    {
        try {
            $goal = FinancialGoal::findOrFail($goalId);
            $goal->update(['current_amount' => $amount]);
            
            $this->dispatch('notification', 
                type: 'success', 
                message: 'The current amount has been successfully updated.'
            );
        } catch (\Exception $e) {
            $this->dispatch('notification', 
                type: 'error', 
                message: 'Failed to update the current amount.'
            );
        }
    }

    /**
     * Clear form and reset state
     */
    public function clearForm()
    {
        $this->reset([
            'name', 'target_amount', 'current_amount', 
            'target_date', 'description', 'isEditMode', 'editingGoalId'
        ]);
        
        $this->dispatch('clear-input-currency');
        Flux::modal('add-goal')->close();
    }

    /**
     * Show appropriate success message
     */
    private function showSuccessMessage()
    {
        $message = $this->isEditMode 
            ? 'Financial goal successfully updated!' 
            : 'Financial goal successfully added!';
            
        $this->dispatch('notification', type: 'success', message: $message);
    }

    /**
     * Sort goals by specified field
     */
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Set filter status
     */
    public function setFilter($status)
    {
        $this->filterStatus = $status;
    }

    /**
     * Get filtered and sorted goals
     */
    #[Computed]
    public function filteredGoals()
    {
        $query = FinancialGoal::query();

        // Apply status filter
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->get();
    }

    /**
     * Get goals statistics for dashboard
     */
    #[Computed]
    public function goalsStatistics()
    {
        $goals = FinancialGoal::all();
        
        return [
            'total_goals' => $goals->count(),
            'active_goals' => $goals->where('status', 'active')->count(),
            'completed_goals' => $goals->where('status', 'completed')->count(),
            'total_target' => $goals->sum('target_amount'),
            'total_saved' => $goals->sum('current_amount'),
            'average_progress' => $goals->count() > 0 ? $goals->avg('progress_percentage') : 0,
        ];
    }

    /**
     * Mark goal as completed
     */
    public function markAsCompleted($goalId)
    {
        try {
            $goal = FinancialGoal::findOrFail($goalId);
            $goal->update(['status' => 'completed']);
            
            $this->dispatch('notification', 
                type: 'success', 
                message: "Well done! The goal '{$goal->name}' has been achieved!"
            );
        } catch (\Exception $e) {
            $this->dispatch('notification', 
                type: 'error', 
                message: 'Failed to update the goal status.'
            );
        }
    }

    /**
     * Get modal title based on mode
     */
    public function getModalTitle()
    {
        return $this->isEditMode ? 'Edit Financial Goal' : 'Add New Financial Goal';
    }

    /**
     * Get submit button text based on mode
     */
    public function getSubmitButtonText()
    {
        return $this->isEditMode ? 'Update Goal' : 'Add Goal';
    }

    public function render()
    {
        return view('livewire.financial.financial-goals-page');
    }
}