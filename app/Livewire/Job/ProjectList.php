<?php

namespace App\Livewire\Job;

use App\Models\job\Project;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ProjectList extends Component
{
    #[Validate('required')]
    public $nameProject;

    #[Validate('required|in:Planning,In Progress,Completed,On Hold')]
    public $statusProject;

    #[Validate('required|date')]
    public $startDate;

    #[Validate('required|date|after_or_equal:startDate')]
    public $endDate;

    #[Validate('required')]
    public $descriptionProject;
    
    // Property untuk search
    public $search = '';
    public $statusFilter = '';

    public $projectDetail;
    
    #[On('dateChanged')]
    public function dateChanged($data)
    {
        // Handle perubahan dari range date picker
        if ($data['mode'] === 'range') {
            if ($data['startDate']) {
                $this->startDate = $data['startDate'];
            }
            if ($data['endDate']) {
                $this->endDate = $data['endDate'];
            }
        }
    }

    public function detailProject(Project $project)
    {
        $this->projectDetail = $project;

        Flux::modal('detail-project')->show();
    }

    public function createProject()
    {
        $this->validate();

        try {
            Project::create([
                'name' => $this->nameProject,
                'description' => $this->descriptionProject,
                'status' => $this->statusProject,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate
            ]);

            $this->dispatch('notification', type: 'success', message: 'Successfully created the project');
            $this->reset(['nameProject', 'statusProject', 'startDate', 'endDate', 'descriptionProject']);
            Flux::modal('add-project')->close();
        } catch (\Exception $e) {
            $this->dispatch('notification', type: 'error', message: 'Failed to create project');
        }
    }
    
    public function clearSearch()
    {
        $this->search = '';
        $this->statusFilter = '';
    }
    
    public function render()
    {
        $query = Project::query();
        
        // Filter berdasarkan search (nama project)
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        
        // Filter berdasarkan status
        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }
        
        $projects = $query->latest()->get();
        
        return view('livewire.job.project-list', [
            'projects' => $projects,
        ]);
    }
}
