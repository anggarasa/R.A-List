<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\job\Project;

class ProjectDetail extends Component
{
    public ?Project $project;

    public function mount($id)
    {
        $this->project = Project::find($id);
    }

    public function render()
    {
        return view('livewire.job.project-detail');
    }
}
