<?php

namespace App\Livewire\Job;

use App\Models\job\Task;
use Flux\Flux;
use Livewire\Attributes\On;
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

    public $taskId;

    public function mount($id)
    {
        $this->project = Project::find($id);
    }

    public function setEdit($taskId)
    {
        if ($taskId) {
            $this->taskId = Task::find($taskId);

            $this->dispatch('updateDate', [
                'mode' => 'single',
                'reset' => false,
                'singleDate' => $this->taskId->due_date,
            ]);

            $this->titleTask = $this->taskId->title;
            $this->description = $this->taskId->description;
            $this->categoryTask = $this->taskId->category;
            $this->priorityTask = $this->taskId->priority;
            $this->dueTask = $this->taskId->due_date;
            $this->statusTask = $this->taskId->status;

            Flux::modal('add-task')->show();
        }
    }

    #[On('dateChanged')]
    public function dateChanged($data)
    {
        if ($data['mode'] === 'single' && $data['singleDate']) {
            $this->dueTask = $data['singleDate'];
        }
    }

    public function createTask()
    {
        $this->validate();

        if ($this->taskId) {
            $this->taskId->update([
                'title' => $this->titleTask,
                'description' => $this->description,
                'category' => $this->categoryTask,
                'priority' => $this->priorityTask,
                'due_date' => $this->dueTask,
                'status' => $this->statusTask,
            ]);
            $this->dispatch('notification', type: 'success', message: 'Updated Task Successfully');
        } else {
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
        }
        $this->dispatch('updateDate', ['mode' => 'single', 'reset' => true]);
        $this->reset(['titleTask', 'description', 'statusTask', 'priorityTask', 'categoryTask', 'taskId']);
        Flux::modal('add-task')->close();
    }

    public function clearForm()
    {
        $this->dispatch('updateDate', ['mode' => 'single', 'reset' => true, 'singleDate' => '']);
        $this->reset(['taskId', 'titleTask', 'description', 'categoryTask', 'priorityTask', 'dueTask', 'statusTask']);
    }

    public function render()
    {
        return view('livewire.job.project-detail', [
            'tasks' => Task::latest()->get(),
        ]);
    }
}
