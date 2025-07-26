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
    
    public $jobId;
    public $detailJob;
    public $isEdit = false;
    
    public $categories = [];
    public $statusTasks = [];
    
    public $categoryTaskId;
    public $statusTaskId;
    public $revisionDate;
    
    protected $cachedJobLists;
    
    public function mount()
    {
        $this->categories = CategoryTask::all();
        $this->statusTasks = StatusTask::all();
    }
    
    public function showDetailTask($jobId)
    {
        if($jobId) {
            $this->jobId = $jobId;
            $this->loadJob();
            Flux::modal('detail-task')->show();
        }
    }
    
    public function loadJob()
    {
        $this->detailJob = JobList::with(['categoryTask', 'statusTask'])->find($this->jobId);
        
        if($this->detailJob) {
            $this->categoryTaskId = $this->detailJob->category_task_id;
            $this->statusTaskId = $this->detailJob->status_task_id;
            $this->revisionDate = $this->detailJob->date_job;
        }
    }

    public function editTask($jobId)
    {
        $this->form->setJobList($jobId);
        $this->isEdit = true;
    }

    public function updateTask()
    {
        $updated = $this->form->store();
        
        if ($updated) {
            $this->loadJob();
            
            $this->dispatch('notification', type: 'success', message: 'Successfully updated task');
            
            $this->cachedJobLists = null;
            
            Flux::modal('detail-task')->close();
            $this->isEdit = false;
            
            $this->form->resetForm();
        }
    }
    
    // Realtime update untuk category
    public function updatedCategoryTaskId($value)
    {
        if ($this->detailJob && $value) {
            $this->detailJob->update([
                'category_task_id' => $value
            ]);
            
            $this->loadJob();
            
            $this->cachedJobLists = null;
        }
    }
    
    // Realtime update untuk status
    public function updatedStatusTaskId($value)
    {
        if ($this->detailJob && $value) {
            $this->detailJob->update([
                'status_task_id' => $value
            ]);
            
            $this->loadJob();
            
            $this->cachedJobLists = null;   
        }
    }
    
    // Realtime update untuk revision date
    public function updatedRevisionDate($value)
    {
        if ($this->detailJob) {
            $this->detailJob->update([
                'date_job' => $value
            ]);
            
            $this->loadJob();
            
            $this->cachedJobLists = null;
        }
    }
    
    // Method untuk delete job
    public function deleteJob()
    {
        if ($this->detailJob) {
            $this->detailJob->delete();
            
            $this->reset(['jobId', 'detailJob', 'categoryTaskId', 'statusTaskId', 'revisionDate']);
            
            // Refresh cache
            $this->cachedJobLists = null;
            
            Flux::modal('detail-task')->close();
            
            $this->dispatch('notification', type: 'success', message: 'successfully deleted the task');
        }
    }
    
    // Getter untuk job lists dengan caching
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
    }
    
    public function render()
    {
        return view('livewire.list-jobs.job-list-view', [
            'jobLists' => $this->jobLists,
        ]);
    }
}
