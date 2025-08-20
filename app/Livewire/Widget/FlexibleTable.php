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
    public $filters = []; // Enhanced: Now supports relations
    public $dateFilters = [];
    public $perPage = 10;
    public $search = '';
    public $sortBy = '';
    public $sortDirection = 'desc';
    public $selectedFilters = [];
    public $dateFilterValues = [];
    public $showSearch = true;
    public $showPerPage = true;
    public $showPagination = true;
    public $showFilters = true;
    public $tableClass = '';
    public $headerClass = '';
    public $bodyClass = '';
    public $darkMode = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => ''],
        'sortDirection' => ['except' => 'desc'],
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
        $filters = [],
        $dateFilters = [],
        $perPage = 10,
        $showSearch = true,
        $showPerPage = true,
        $showPagination = true,
        $showFilters = true,
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

        // Initialize filter arrays
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
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function clearFilters()
    {
        $this->selectedFilters = [];
        $this->dateFilterValues = [];
        $this->search = '';
        
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

        // Load relations for filters
        $relationsToLoad = [];
        foreach ($this->filters as $field => $filter) {
            if (isset($filter['relation'])) {
                $relationsToLoad[] = $filter['relation'];
            }
        }
        
        if (!empty($relationsToLoad)) {
            $query->with($relationsToLoad);
        }

        // Apply search
        if ($this->search && !empty($this->searchable)) {
            $query->where(function (Builder $query) {
                foreach ($this->searchable as $field) {
                    $query->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        // Apply select filters - Enhanced to handle relations
        foreach ($this->selectedFilters as $field => $value) {
            if (!empty($value) && isset($this->filters[$field])) {
                $filter = $this->filters[$field];
                
                // Check if this is a relation filter
                if (isset($filter['relation'])) {
                    // For relation filters, we need to filter by the display field value
                    // but match against the foreign key
                    $relatedModel = $this->getRelatedModel($filter['relation']);
                    $displayField = $filter['display_field'] ?? 'name';
                    
                    if ($relatedModel) {
                        $relatedRecord = $relatedModel::where($displayField, $value)->first();
                        if ($relatedRecord) {
                            $query->where($field, $relatedRecord->id);
                        }
                    }
                } else {
                    // Regular field filter
                    $query->where($field, $value);
                }
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
        } else {
            // Default: terbaru di atas
            if ($this->model::query()->getModel()->usesTimestamps()) {
                $query->orderBy('created_at', 'desc');
            } else {
                // fallback kalau model gak ada created_at
                $query->latest();
            }
        }

        return $query->paginate($this->perPage);
    }

    // Enhanced: Get filter options with relation support
    public function getFilterOptions($field)
    {
        if (!$this->model || !isset($this->filters[$field])) {
            return [];
        }

        $filter = $this->filters[$field];

        // Check if this is a relation filter
        if (isset($filter['relation'])) {
            $relatedModel = $this->getRelatedModel($filter['relation']);
            $displayField = $filter['display_field'] ?? 'name';
            
            if ($relatedModel) {
                // Get options from related table
                return $relatedModel::whereNotNull($displayField)
                    ->orderBy($displayField)
                    ->pluck($displayField)
                    ->unique()
                    ->values()
                    ->toArray();
            }
        } else {
            // Regular field filter
            return $this->model::distinct()
                ->whereNotNull($field)
                ->pluck($field)
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        }

        return [];
    }

    // New: Get the related model class from relation name
    private function getRelatedModel($relationName)
    {
        if (!$this->model) {
            return null;
        }

        try {
            $modelInstance = new $this->model;
            
            if (method_exists($modelInstance, $relationName)) {
                $relation = $modelInstance->$relationName();
                return $relation->getRelated()::class;
            }
        } catch (\Exception $e) {
            // Handle any errors gracefully
        }

        return null;
    }

    // New: Get display value for relation filter
    public function getRelationDisplayValue($field, $value)
    {
        if (!isset($this->filters[$field]['relation'])) {
            return $value;
        }

        $filter = $this->filters[$field];
        $relatedModel = $this->getRelatedModel($filter['relation']);
        $displayField = $filter['display_field'] ?? 'name';

        if ($relatedModel && !empty($value)) {
            $relatedRecord = $relatedModel::where($displayField, $value)->first();
            return $relatedRecord ? $relatedRecord->$displayField : $value;
        }

        return $value;
    }
    
    public function render()
    {
        return view('livewire.widget.flexible-table', [
            'data' => $this->data
        ]);
    }
}