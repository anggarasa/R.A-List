<?php

namespace App\Livewire\ListJobs;

use App\Models\JobList;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('new-job')]
class JobListView extends Component
{
    public function render()
    {
        return view('livewire.list-jobs.job-list-view', [
            'jobLists' => JobList::with(['categoryTask', 'statusTask'])->latest()->get(),
        ]);
    }
}
