<?php

namespace App\Livewire\Financial;

use Livewire\Component;

class FinancialPage extends Component
{
    public function mount()
    {
        // Dispatch event ketika halaman financial dashboard berhasil dimuat
        $this->dispatch('financial-page-loaded');
    }
    
    public function render()
    {
        return view('livewire.financial.financial-page');
    }
}
