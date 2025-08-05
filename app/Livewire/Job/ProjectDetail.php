<?php

namespace App\Livewire\Job;

use App\Models\job\Task;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\job\Project;

class ProjectDetail extends Component
{
    public ?Project $project;

    #[Validate('required')]
    public $titleTask;

    #[Validate('required|date')]
    public $dueTask;

    #[Validate('required|in:Todo,In Progress,Done,Error,Revisi')]
    public $statusTask;

    #[Validate('required|in:Slicing,Integration API, Clean Code')]
    public $categoryTask;

    #[Validate('required|in:Low,Medium,High')]
    public $priorityTask;

    #[Validate('required')]
    public $description;

    public function mount($id)
    {
        $this->project = Project::find($id);
    }

    public function createTask()
    {
        $this->validate();

        Task::create([
            'project_id' => $this->project->id,
            'title' => $this->titleTask,
            'description' => $this->description,
            'status' => $this->statusTask,
            'priority' => $this->priorityTask,
            'category' => $this->categoryTask,
            'due_date' => $this->dueTask,
        ]);

        $this->dispatch('notification', type: 'success',  message: 'Task Created Successfully');
        $this->reset(['titleTask', 'description', 'statusTask', 'priorityTask', 'categoryTask']);
        Flux::modal('add-task')->close();
    }

    public function render()
    {
        return view('livewire.job.project-detail', [
            'tasks' => Task::latest()->get(),
        ]);
    }
}
