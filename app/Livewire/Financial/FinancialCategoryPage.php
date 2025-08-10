<?php

namespace App\Livewire\Financial;

use App\Models\financial\FinancialCategory;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FinancialCategoryPage extends Component
{
    #[Validate('required')]
    public $name;

    #[Validate('required|in:income,expense')]
    public $type;

    public function saveCategory()
    {
        $this->validate();

        FinancialCategory::create([
            'name' => $this->name,
            'type' => $this->type
        ]);

        $this->dispatch('notification', type: 'success', message: 'Successfully created financial categories');
        $this->reset(['name', 'type']);
    }
    
    public function render()
    {
        return view('livewire.financial.financial-category-page');
    }
}
