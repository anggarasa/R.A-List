<?php

namespace App\Livewire;

use App\Models\financial\FinancialAccount;
use App\Models\financial\FinancialTransaction;
use App\Models\financial\FinancialBudget;
use App\Models\financial\FinancialGoal;
use App\Models\job\Project;
use App\Models\job\Task;
use App\Models\job\Note;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalBalance = 0;
    public $monthlyIncome = 0;
    public $monthlyExpense = 0;
    public $activeProjects = 0;
    public $completedTasks = 0;
    public $pendingTasks = 0;
    public $recentTransactions = [];
    public $budgetStatus = [];
    public $financialGoals = [];
    public $recentProjects = [];
    public $upcomingTasks = [];
    public $isLoading = false;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function refreshData()
    {
        $this->isLoading = true;
        $this->loadDashboardData();
        $this->isLoading = false;
    }

    public function loadDashboardData()
    {
        // Financial Data
        $this->totalBalance = FinancialAccount::sum('balance');
        
        $this->monthlyIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
            
        $this->monthlyExpense = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');

        // Project & Task Data
        $this->activeProjects = Project::where('status', 'In Progress')->count();
        $this->completedTasks = Task::where('status', 'Done')->count();
        $this->pendingTasks = Task::where('status', 'Todo')->count();

        // Recent Transactions
        $this->recentTransactions = FinancialTransaction::with(['category', 'account'])
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        // Budget Status
        $this->budgetStatus = FinancialBudget::with('category')
            ->where('month', now()->month)
            ->where('year', now()->year)
            ->get()
            ->map(function ($budget) {
                $used = $budget->category->transactions()
                    ->where('type', 'expense')
                    ->whereMonth('transaction_date', $budget->month)
                    ->whereYear('transaction_date', $budget->year)
                    ->sum('amount');
                
                return [
                    'category' => $budget->category->name,
                    'budget' => $budget->amount,
                    'used' => $used,
                    'remaining' => $budget->amount - $used,
                    'percentage' => $budget->amount > 0 ? ($used / $budget->amount) * 100 : 0
                ];
            });

        // Financial Goals
        $this->financialGoals = FinancialGoal::where('status', 'active')
            ->orderBy('target_date', 'asc')
            ->limit(3)
            ->get();

        // Recent Projects
        $this->recentProjects = Project::withCount('tasks')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();

        // Upcoming Tasks
        $this->upcomingTasks = Task::with('project')
            ->where('due_date', '>=', now())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
