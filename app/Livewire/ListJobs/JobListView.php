<?php

namespace App\Livewire\ListJobs;

use App\Livewire\Forms\JobListForm;
use App\Models\CategoryTask;
use App\Models\JobList;
use App\Models\StatusTask;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use phpDocumentor\Reflection\Types\This;

use function PHPSTORM_META\type;

#[On('new-job')]
class JobListView extends Component
{
    use WithPagination;
    
    public JobListForm $form;
    
    public $isEdit = false;
    public $jobId;
    
    public $categories = [];
    public $statusTasks = [];
    
    public $categoryTaskId;
    public $statusTaskId;
    public $revisionDates = []; // Array untuk handle multiple revision dates
    
    // Search properties
    public $search = '';
    public $searchCategory = '';
    public $searchStatus = '';
    
    protected $cachedJobLists;
    
    public function mount()
    {
        $this->categories = CategoryTask::all();
        $this->statusTasks = StatusTask::all();
        
        // Initialize revision dates array
        $this->initializeRevisionDates();
    }

    /**
     * Initialize revision dates untuk semua job lists
     */
    private function initializeRevisionDates()
    {
        $jobLists = JobList::all();
        foreach ($jobLists as $job) {
            $this->revisionDates[$job->id] = $job->date_job;
        }
    }

    /**
     * Reset search filters
     */
    public function resetSearch()
    {
        $this->search = '';
        $this->searchCategory = '';
        $this->searchStatus = '';
        $this->cachedJobLists = null;
    }

    /**
     * Listener untuk real-time search
     */
    public function updatedSearch()
    {
        $this->cachedJobLists = null;
    }

    public function updatedSearchCategory()
    {
        $this->cachedJobLists = null;
    }

    public function updatedSearchStatus()
    {
        $this->cachedJobLists = null;
    }

    public function editTask($jobId)
    {
        $this->jobId = $jobId;
        $this->form->setJobList($jobId);
        $this->isEdit = true;
    }

    public function updateTask()
    {
        $updated = $this->form->store();
        
        if ($updated) {
            $this->dispatch('notification', type: 'success', message: 'Successfully updated task');
            
            $this->cachedJobLists = null;
            
            // Refresh revision dates after update
            $this->initializeRevisionDates();
            
            Flux::modal('detail-job'.$this->jobId)->close();
            $this->isEdit = false;

            $this->reset('jobId');
            
            $this->form->resetForm();
        }
    }
    
    /**
     * Realtime update untuk category
     */
    public function updateCategoryTaskId($value, $jobId)
    {
        $job = JobList::find($jobId);
        
        if ($job && $value) {
            $job->update([
                'category_task_id' => $value
            ]);
            
            $this->cachedJobLists = null;
        }
    }
    
    /**
     * Realtime update untuk status
     */
    public function updateStatusTaskId($value, $jobId)
    {
        $job = JobList::find($jobId);
        
        if ($job && $value) {
            $job->update([
                'status_task_id' => $value
            ]);
            
            $this->cachedJobLists = null;
        }
    }
    
    /**
     * Realtime update untuk revision date menggunakan updatedRevisionDates
     */
    public function updatedRevisionDates($value, $key)
    {
        // $key adalah job ID, $value adalah tanggal yang baru
        $job = JobList::find($key);
        
        if ($job) {
            $job->update([
                'date_job' => $value ? $value : null
            ]);
            
            $this->cachedJobLists = null;
        }
    }
    
    /**
     * Method untuk delete job
     */
    public function deleteJob($jobId)
    {
        $job = JobList::find($jobId);
        
        if ($job) {
            $job->delete();
            
            // Remove from revision dates array
            unset($this->revisionDates[$jobId]);
            
            // Refresh cache
            $this->cachedJobLists = null;
            
            Flux::modal('detail-job' . $jobId)->close();

            $this->dispatch('notification', type: 'success', message: 'Task deleted successfully');
        }
    }
    
    /**
     * Getter untuk job lists dengan caching dan search functionality
     */
    public function getJobListsProperty()
    {
        if ($this->cachedJobLists === null) {
            $query = JobList::with(['categoryTask', 'statusTask']);
            
            // Search by name and description
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->where('name_job_list', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            }
            
            // Filter by category
            if (!empty($this->searchCategory)) {
                $query->whereHas('categoryTask', function ($q) {
                    $q->where('name_category_task', 'like', '%' . $this->searchCategory . '%');
                });
            }
            
            // Filter by status
            if (!empty($this->searchStatus)) {
                $query->whereHas('statusTask', function ($q) {
                    $q->where('name_status_task', 'like', '%' . $this->searchStatus . '%');
                });
            }
            
            $this->cachedJobLists = $query->latest()->paginate(6);
        }
        
        return $this->cachedJobLists;
    }

    public function cancelEdit()
    {
        $this->isEdit = false;
        $this->form->resetForm();
    }
    
    /**
     * Refresh job lists dan revision dates
     */
    public function refreshJobLists()
    {
        $this->cachedJobLists = null;
        $this->initializeRevisionDates();
    }
    
    public function render()
    {
        return view('livewire.list-jobs.job-list-view', [
            'jobLists' => $this->jobLists,
        ]);
    }
}