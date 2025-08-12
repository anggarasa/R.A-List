<?php

namespace App\Livewire\Widget;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Carbon\Carbon;

#[On('refresh-table')]
class FlexibleTable extends Component
{
    use WithPagination;

    public $model;
    public $columns = [];
    public $searchable = [];
    public $sortable = [];
    public $actions = [];
    public $filters = []; // New: Filter configuration
    public $dateFilters = []; // New: Date filter configuration
    public $perPage = 10;
    public $search = '';
    public $sortBy = '';
    public $sortDirection = 'asc';
    public $selectedFilters = []; // Changed: Now holds single values instead of arrays
    public $dateFilterValues = []; // New: Date filter values
    public $showSearch = true;
    public $showPerPage = true;
    public $showPagination = true;
    public $showFilters = true; // New: Show/hide filters
    public $tableClass = '';
    public $headerClass = '';
    public $bodyClass = '';
    public $darkMode = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => ''],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'selectedFilters' => ['except' => []],
        'dateFilterValues' => ['except' => []],
    ];

    public function mount(
        $model = null,
        $columns = [],
        $searchable = [],
        $sortable = [],
        $actions = [],
        $filters = [], // New parameter
        $dateFilters = [], // New parameter
        $perPage = 10,
        $showSearch = true,
        $showPerPage = true,
        $showPagination = true,
        $showFilters = true, // New parameter
        $darkMode = false
    ) {
        $this->model = $model;
        $this->columns = $columns;
        $this->searchable = $searchable;
        $this->sortable = $sortable;
        $this->actions = $actions;
        $this->filters = $filters;
        $this->dateFilters = $dateFilters;
        $this->perPage = $perPage;
        $this->showSearch = $showSearch;
        $this->showPerPage = $showPerPage;
        $this->showPagination = $showPagination;
        $this->showFilters = $showFilters;
        $this->darkMode = $darkMode;

        // Initialize filter arrays - changed to single values
        foreach ($this->filters as $key => $filter) {
            $this->selectedFilters[$key] = '';
        }
        
        foreach ($this->dateFilters as $key => $dateFilter) {
            $this->dateFilterValues[$key] = [
                'from' => '',
                'to' => ''
            ];
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingSelectedFilters()
    {
        $this->resetPage();
    }

    public function updatingDateFilterValues()
    {
        $this->resetPage();
    }

    public function sortByTable($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->selectedFilters = [];
        $this->dateFilterValues = [];
        $this->search = '';
        
        // Re-initialize filter arrays - changed to single values
        foreach ($this->filters as $key => $filter) {
            $this->selectedFilters[$key] = '';
        }
        
        foreach ($this->dateFilters as $key => $dateFilter) {
            $this->dateFilterValues[$key] = [
                'from' => '',
                'to' => ''
            ];
        }
        
        $this->resetPage();
    }

    public function edit($id)
    {
        $model = $this->model::find($id);

        if($model) {
            $this->dispatch('update-data-table', $model);
        }
    }

    public function confirmDelete($id, $message)
    {
        if($id) {
            $this->dispatch('notification', type: 'warning', message: $message, actionEvent: 'deleteDataTable',
            actionParams: [$id]);
        } else {
            $this->dispatch('notification', type: 'error', message: 'The data you want to delete was not found');
        }
    }

    #[On('deleteDataTable')]
    public function deleteDataTable($id)
    {
        $model = $this->model::find($id);

        $model->delete();

        $this->dispatch('notification', type: 'success', message: 'Successfully deleted data');
    }

    public function getDataProperty()
    {
        if (!$this->model) {
            return collect();
        }

        $query = $this->model::query();

        // Apply search
        if ($this->search && !empty($this->searchable)) {
            $query->where(function (Builder $query) {
                foreach ($this->searchable as $field) {
                    $query->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        // Apply select filters - changed to handle single values
        foreach ($this->selectedFilters as $field => $value) {
            if (!empty($value) && isset($this->filters[$field])) {
                $query->where($field, $value);
            }
        }

        // Apply date filters
        foreach ($this->dateFilterValues as $field => $dateRange) {
            if (!empty($dateRange['from']) || !empty($dateRange['to'])) {
                if (!empty($dateRange['from'])) {
                    $query->whereDate($field, '>=', $dateRange['from']);
                }
                if (!empty($dateRange['to'])) {
                    $query->whereDate($field, '<=', $dateRange['to']);
                }
            }
        }

        // Apply sorting
        if ($this->sortBy && in_array($this->sortBy, $this->sortable)) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
    }

    // Get filter options dynamically
    public function getFilterOptions($field)
    {
        if (!$this->model) {
            return [];
        }

        return $this->model::distinct()
            ->whereNotNull($field)
            ->pluck($field)
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
    
    public function render()
    {
        return view('livewire.widget.flexible-table', [
            'data' => $this->data
        ]);
    }
}