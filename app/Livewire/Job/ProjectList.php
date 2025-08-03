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

    public ?Project $projectDetail = null;
    
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

    public function setEdit()
    {
        if ($this->projectDetail) {
            $this->dispatch('updateDate', [
                'mode' => 'range',
                'reset' => false,
                'startDate' => $this->projectDetail->start_date,
                'endDate' => $this->projectDetail->end_date
            ]);
            
            $this->nameProject = $this->projectDetail->name;
            $this->descriptionProject = $this->projectDetail->description;
            $this->statusProject = $this->projectDetail->status;
            $this->startDate = $this->projectDetail->start_date;
            $this->endDate = $this->projectDetail->end_date;

            Flux::modal('add-project')->show();
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

        if($this->projectDetail) {
            try {
                $this->projectDetail->update([
                    'name' => $this->nameProject,
                    'description' => $this->descriptionProject,
                    'status' => $this->statusProject,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ]);

                $this->dispatch('updateDate', ['mode' => 'range', 'reset' => true]);
                $this->dispatch('notification', type: 'success', message: 'Successfully changed the project');
                $this->reset(['nameProject', 'statusProject', 'startDate', 'endDate', 'descriptionProject', 'projectDetail']);
                Flux::modal('add-project')->close();
                Flux::modal('detail-project')->close();
            } catch (\Exception $e) {
                $this->dispatch('notification', type: 'error', message: 'Failed to changed project');
            }
        } else {
            try {
                Project::create([
                    'name' => $this->nameProject,
                    'description' => $this->descriptionProject,
                    'status' => $this->statusProject,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate
                ]);

                $this->dispatch('updateDate', ['mode' => 'range', 'reset' => true]);
                $this->dispatch('notification', type: 'success', message: 'Successfully created the project');
                $this->reset(['nameProject', 'statusProject', 'startDate', 'endDate', 'descriptionProject']);
                Flux::modal('add-project')->close();
            } catch (\Exception $e) {
                $this->dispatch('notification', type: 'error', message: 'Failed to create project');
            }
        }
    }

    public function deleteProject()
    {
        if($this->projectDetail) {
            $this->projectDetail->delete();

            Flux::modal('detail-project')->close();

            $this->dispatch('notification', type: 'success', message: 'Successfully deleted the project');

            $this->reset('projectDetail');
        }
    }
    
    public function clearSearch()
    {
        $this->search = '';
        $this->statusFilter = '';
    }

    public function clearDetail()
    {
        $this->reset(['projectDetail']);
    }

    public function clearForm()
    {
        $this->reset(['nameProject', 'descriptionProject', 'statusProject', 'startDate', 'endDate']);
        $this->dispatch('updateDate', [
            'mode' => 'range',
            'reset' => true,
            'startDate' => '',
            'endDate' => '',
        ]);
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
