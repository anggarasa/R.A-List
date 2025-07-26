<?php

namespace App\Livewire\Forms;

use App\Models\JobList;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Form;

class JobListForm extends Form
{
    public $job_list;
    
    #[Validate('required')]
    public $statusTaskId;

    #[Validate('required')]
    public $categoryTaskId;
    
    #[Validate('required')]
    public $nameTask = '';

    #[Validate('required')]
    public $description = '';

    public function setJobList($jobId)
    {
        if(!$jobId) {
            return;
        }

        $this->job_list = JobList::find($jobId);
        if ($this->job_list) {
            $this->nameTask = $this->job_list->name_job_list;
            $this->description = $this->job_list->description;
            $this->categoryTaskId = $this->job_list->category_task_id;
            $this->statusTaskId = $this->job_list->status_task_id;
        }
    }

    public function store()
    {
        $this->validate();

        if($this->job_list) {
            $this->job_list->update([
                'name_job_list' => $this->nameTask,
                'description' => $this->description,
                'category_task_id' => $this->categoryTaskId,
                'status_task_id' => $this->statusTaskId,
            ]);

            return true;
        } else {
            JobList::create([
                'category_task_id' => $this->categoryTaskId,
                'status_task_id' => $this->statusTaskId,
                'name_job_list' => $this->nameTask,
                'description' => $this->description,
            ]);
            
            Flux::modal('add-job')->close();
            return true;
        }
    }

    public function resetForm()
    {
        $this->reset(['nameTask', 'description', 'categoryTaskId', 'statusTaskId', 'job_list']);
    }
}
