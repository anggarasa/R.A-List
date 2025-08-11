<?php

namespace App\Livewire\Widget;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

#[On('refresh-table')]
class FlexibleTable extends Component
{
    use WithPagination;

    public $model;
    public $columns = [];
    public $searchable = [];
    public $sortable = [];
    public $actions = [];
    public $perPage = 10;
    public $search = '';
    public $sortBy = '';
    public $sortDirection = 'asc';
    public $showSearch = true;
    public $showPerPage = true;
    public $showPagination = true;
    public $tableClass = '';
    public $headerClass = '';
    public $bodyClass = '';
    public $darkMode = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => ''],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function mount(
        $model = null,
        $columns = [],
        $searchable = [],
        $sortable = [],
        $actions = [],
        $perPage = 10,
        $showSearch = true,
        $showPerPage = true,
        $showPagination = true,
        $darkMode = false
    ) {
        $this->model = $model;
        $this->columns = $columns;
        $this->searchable = $searchable;
        $this->sortable = $sortable;
        $this->actions = $actions;
        $this->perPage = $perPage;
        $this->showSearch = $showSearch;
        $this->showPerPage = $showPerPage;
        $this->showPagination = $showPagination;
        $this->darkMode = $darkMode;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
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

        // Apply sorting
        if ($this->sortBy && in_array($this->sortBy, $this->sortable)) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
    }
    
    public function render()
    {
        return view('livewire.widget.flexible-table', [
            'data' => $this->data
        ]);
    }
}
