<?php

namespace App\Livewire\Financial;

use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use App\Livewire\Widget\FlexibleTable;
use App\Models\financial\FinancialCategory;

class FinancialCategoryPage extends Component
{
    public $categoryId;
    
    #[Validate('required')]
    public $name;

    #[Validate('required|in:income,expense,transfer')]
    public $type;

    #[On('update-data-table')]
    public function edit($data)
    {
        if($data) {
            $this->categoryId = $data['id'];
            $this->name = $data['name'];
            $this->type = $data['type'];

            Flux::modal('add-financial-category')->show();
        }
    }

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

    public function clearForm()
    {
        $this->reset(['name', 'type', 'categoryId']);
        Flux::modal('add-financial-category')->close();
    }

    // Start Manage view flexible table category
    public $columns = [
        'name' => ['label' => 'Category Name'],
        'type' => [
            'label' => 'Type',
            'format' => 'badge',
            'badge_colors' => [
                'income' => 'green',
                'expense' => 'red',
                'transfer' => 'blue',
            ],
            'badge_labels' => [
                'income' => 'Income',
                'expense' => 'Expense',
                'transfer' => 'Transfer',
            ],
        ],
    ];

    public $filter = ['type' => ['label', 'Type']];

    public $search = ['name'];

    public $sortable = ['name'];

    public $actions = [
        [
            'method' => 'edit',
            'label' => 'Edit',
            'class' => 'bg-lime-400 text-black hover:bg-lime-600 cursor-pointer'
        ],
        [
            'method' => 'confirmDelete',
            'label' => 'Delete',
            'class' => 'text-white bg-red-600 hover:bg-red-700 cursor-pointer',
            'confirm' => 'Are you sure you want to delete this category?'
        ]
    ];
    // End Manage view flexible table category
    
    public function render()
    {
        return view('livewire.financial.financial-category-page', [
            'categories' => FinancialCategory::latest()->get(),
        ]);
    }
}
