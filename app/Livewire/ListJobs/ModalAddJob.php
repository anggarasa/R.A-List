<?php

namespace App\Livewire\ListJobs;

use App\Livewire\Forms\JobListForm;
use App\Models\CategoryTask;
use App\Models\StatusTask;
use Livewire\Component;

class ModalAddJob extends Component
{
    public JobListForm $form;

    public function createTask()
    {
        $this->form->store();
        
        $this->dispatch('new-job');
        $this->dispatch('notification', type: 'success', message: 'New task created successfully!');
    }
    
    public function render()
    {
        return view('livewire.list-jobs.modal-add-job', [
            'statuses' => StatusTask::all(),
            'categories' => CategoryTask::all(),
        ]);
    }
}
