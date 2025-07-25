<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class JobListForm extends Form
{
    #[Validate('required')]
    public $categoryTaskId;
    
    #[Validate('required')]
    public $nameJob = '';

    #[Validate('required')]
    public $description = '';

    public function create()
    {
        $this->validate();
    }
}
