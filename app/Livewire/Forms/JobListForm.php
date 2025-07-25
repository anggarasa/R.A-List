<?php

namespace App\Livewire\Forms;

use App\Models\JobList;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Form;

class JobListForm extends Form
{
    #[Validate('required')]
    public $statusTaskId;

    #[Validate('required')]
    public $categoryTaskId;
    
    #[Validate('required')]
    public $nameTask = '';

    #[Validate('required')]
    public $description = '';

    public function store()
    {
        $this->validate();

        JobList::create([
            'category_task_id' => $this->categoryTaskId,
            'status_task_id' => $this->statusTaskId,
            'name_job_list' => $this->nameTask,
            'description' => $this->description,
        ]);

        $this->reset();

        Flux::modal('add-job')->close();
    }
}
