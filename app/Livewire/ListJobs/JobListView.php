<?php

namespace App\Livewire\ListJobs;

use App\Livewire\Forms\JobListForm;
use App\Models\CategoryTask;
use App\Models\JobList;
use App\Models\StatusTask;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use phpDocumentor\Reflection\Types\This;

use function PHPSTORM_META\type;

#[On('new-job')]
class JobListView extends Component
{
    public JobListForm $form;
    
    public $isEdit = false;
    public $jobId;
    
    public $categories = [];
    public $statusTasks = [];
    
    public $categoryTaskId;
    public $statusTaskId;
    public $revisionDates = []; // Array untuk handle multiple revision dates
    
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
     * Getter untuk job lists dengan caching
     */
    public function getJobListsProperty()
    {
        if ($this->cachedJobLists === null) {
            $this->cachedJobLists = JobList::with(['categoryTask', 'statusTask'])
                ->latest()
                ->get();
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
