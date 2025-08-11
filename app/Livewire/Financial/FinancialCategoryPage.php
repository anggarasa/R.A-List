<?php

namespace App\Livewire\Financial;

use App\Livewire\Widget\FlexibleTable;
use App\Models\financial\FinancialCategory;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class FinancialCategoryPage extends Component
{
    public $categoryId;
    
    #[Validate('required')]
    public $name;

    #[Validate('required|in:income,expense')]
    public $type;

    public function saveCategory()
    {
        $this->validate();

        if($this->categoryId) {
            $category = FinancialCategory::find($this->categoryId);

            $category->update([
                'name' => $this->name,
                'type' => $this->type,
            ]);

            $this->dispatch('notification', type: 'success', message: 'Successfully changed financial category');
        } else {
            FinancialCategory::create([
                'name' => $this->name,
                'type' => $this->type
            ]);

            $this->dispatch('notification', type: 'success', message: 'Successfully created financial categories');
        }
        $this->clearForm();
        $this->dispatch('refresh-table')->to(FlexibleTable::class);
    }

    #[On('update-data-table')]
    public function edit($data)
    {
        if($data) {
            $this->categoryId = $data['id'];
            $this->name = $data['name'];
            $this->type = $data['type'];
        }
    }

    public function clearForm()
    {
        $this->reset(['name', 'type', 'categoryId']);
    }
    
    public function render()
    {
        return view('livewire.financial.financial-category-page', [
            'categories' => FinancialCategory::latest()->get(),
        ]);
    }
}
