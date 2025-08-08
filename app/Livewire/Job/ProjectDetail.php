<?php

namespace App\Livewire\Job;

use App\Models\job\Task;
use App\Models\job\Note;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\job\Project;

class ProjectDetail extends Component
{
    public ?Project $project;

    // Tab state
    public string $activeTab = 'tasks';

    // Search properties
    public string $searchTask = '';
    public string $searchNote = '';

    // Filter properties for tasks
    public bool $showFilters = false;
    public string $filterCategory = '';
    public string $filterStatus = '';
    public string $filterPriority = '';

    // Task properties
    #[Validate('required')]
    public $titleTask;

    #[Validate('required|date')]
    public $dueTask;

    #[Validate('required|in:Todo,In Progress,Done,Error,Revisi')]
    public $statusTask;

    #[Validate('required|in:Slicing,Integration API,Clean Code')]
    public $categoryTask;

    #[Validate('required|in:Low,Medium,High')]
    public $priorityTask;

    #[Validate('required')]
    public $description;

    public $taskId;

    // Note properties
    #[Validate('required')]
    public $titleNote;

    #[Validate('required')]
    public $contentNote;

    public $noteId;

    public function mount($id)
    {
        $this->project = Project::find($id);
    }

    // Tab methods
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        // Reset search when switching tabs
        $this->searchTask = '';
        $this->searchNote = '';
        $this->clearAllFilters();
    }

    // Search and Filter methods
    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearAllFilters()
    {
        $this->filterCategory = '';
        $this->filterStatus = '';
        $this->filterPriority = '';
        $this->showFilters = false;
    }

    public function hasActiveFilters()
    {
        return !empty($this->filterCategory) ||
            !empty($this->filterStatus) ||
            !empty($this->filterPriority);
    }

    // Computed properties for filtered data
    public function getFilteredTasksProperty()
    {
        $query = Task::where('project_id', $this->project->id);

        // Apply search filter
        if (!empty($this->searchTask)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchTask . '%')
                    ->orWhere('description', 'like', '%' . $this->searchTask . '%');
            });
        }

        // Apply category filter
        if (!empty($this->filterCategory)) {
            $query->where('category', $this->filterCategory);
        }

        // Apply status filter
        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        // Apply priority filter
        if (!empty($this->filterPriority)) {
            $query->where('priority', $this->filterPriority);
        }

        return $query->latest()->get();
    }

    public function getFilteredNotesProperty()
    {
        $query = Note::where('project_id', $this->project->id);

        // Apply search filter
        if (!empty($this->searchNote)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->searchNote . '%')
                    ->orWhere('content', 'like', '%' . $this->searchNote . '%');
            });
        }

        return $query->latest()->get();
    }

    // Task methods
    public function setEdit($taskId)
    {
        if ($taskId) {
            $task = Task::find($taskId);
            $this->taskId = $task->id;

            $this->dispatch('updateDate', [
                'mode' => 'single',
                'reset' => false,
                'singleDate' => $task->due_date,
            ]);

            $this->titleTask = $task->title;
            $this->description = $task->description;
            $this->categoryTask = $task->category;
            $this->priorityTask = $task->priority;
            $this->dueTask = $task->due_date;
            $this->statusTask = $task->status;

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
        $this->validate([
            'titleTask' => 'required',
            'dueTask' => 'required|date',
            'statusTask' => 'required|in:Todo,In Progress,Done,Error,Revisi',
            'categoryTask' => 'required|in:Slicing,Integration API,Clean Code',
            'priorityTask' => 'required|in:Low,Medium,High',
            'description' => 'required',
        ]);

        if ($this->taskId) {
            $task = Task::find($this->taskId);
            $task->update([
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
        $this->clearForm();
        Flux::modal('add-task')->close();
    }

    public function confirmDelete($taskId)
    {
        $task = Task::find($taskId);

        $this->dispatch('notification',
            type: 'warning',
            message: 'Are you sure you want to delete ' . $task->title . ' task?',
            actionEvent: 'deleteTask',
            actionParams: [$taskId]
        );
    }

    #[On('deleteTask')]
    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);
        $task->delete();

        $this->dispatch('notification',
            type: 'success',
            message: 'Task Deleted Successfully',
        );
    }

    public function clearForm()
    {
        $this->dispatch('updateDate', ['mode' => 'single', 'reset' => true, 'singleDate' => '']);
        $this->reset(['taskId', 'titleTask', 'description', 'categoryTask', 'priorityTask', 'dueTask', 'statusTask']);
    }

    // Note methods
    public function editNote($noteId)
    {
        if ($noteId) {
            $note = Note::find($noteId);
            $this->noteId = $note->id;
            $this->titleNote = $note->title;
            $this->contentNote = $note->content;

            Flux::modal('add-note')->show();
        }
    }

    public function createNote()
    {
        $this->validate([
            'titleNote' => 'required',
            'contentNote' => 'required',
        ]);

        if ($this->noteId) {
            $note = Note::find($this->noteId);
            $note->update([
                'title' => $this->titleNote,
                'content' => $this->contentNote,
            ]);
            $this->dispatch('notification', type: 'success', message: 'Updated Note Successfully');
        } else {
            Note::create([
                'project_id' => $this->project->id,
                'title' => $this->titleNote,
                'content' => $this->contentNote,
            ]);

            $this->dispatch('notification', type: 'success', message: 'Note Created Successfully');
        }

        $this->clearNoteForm();
        Flux::modal('add-note')->close();
    }

    public function confirmDeleteNote($noteId)
    {
        $note = Note::find($noteId);

        $this->dispatch('notification',
            type: 'warning',
            message: 'Are you sure you want to delete this note?',
            actionEvent: 'deleteNote',
            actionParams: [$noteId]
        );
    }

    #[On('deleteNote')]
    public function deleteNote($noteId)
    {
        $note = Note::find($noteId);
        $note->delete();

        $this->dispatch('notification',
            type: 'success',
            message: 'Note Deleted Successfully',
        );
    }

    public function clearNoteForm()
    {
        $this->reset(['noteId', 'titleNote', 'contentNote']);
    }

    public function render()
    {
        return view('livewire.job.project-detail', [
            'tasks' => $this->filteredTasks,
            'notes' => $this->filteredNotes,
        ]);
    }
}
