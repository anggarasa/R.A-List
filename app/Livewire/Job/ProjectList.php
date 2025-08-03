<?php

namespace App\Livewire\Job;

use Livewire\Attributes\On;
use Livewire\Component;

class ProjectList extends Component
{
    public $startDate;
    public $endDate;
    
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
    
    public function render()
    {
        return view('livewire.job.project-list');
    }
}
