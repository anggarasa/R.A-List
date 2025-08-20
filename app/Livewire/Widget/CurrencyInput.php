<?php

namespace App\Livewire\Widget;

use Livewire\Attributes\On;
use Livewire\Component;

class CurrencyInput extends Component
{
    public $value;
    public $rawValue = 0;
    public $label;
    public $placeholder;
    public $required = false;
    public $disabled = false;
    public $error;
    public $name;
    public $id;
    public $size = 'md'; // sm, md, lg
    
    public function mount(
        $value = null, 
        $label = null, 
        $placeholder = 'Masukkan nominal', 
        $required = false, 
        $disabled = false, 
        $error = null,
        $name = null,
        $id = null,
        $size = 'md'
    ) {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->error = $error;
        $this->name = $name;
        $this->id = $id ?: 'currency_input_' . uniqid();
        $this->size = $size;
        
        if ($value !== null) {
            $this->rawValue = $this->parseValue($value);
            $this->value = $this->formatCurrency($this->rawValue);
        }
    }
    
    // Method ini dipanggil ketika Alpine.js mengirim nilai raw
    public function updateRawValue($rawValue)
    {
        $this->rawValue = (int) $rawValue;
        $this->value = $this->formatCurrency($this->rawValue);
        
        // Emit event untuk parent component
        $this->dispatch('currency-updated', [
            'name' => $this->name,
            'value' => $this->rawValue,
            'formatted' => $this->value
        ]);
    }
    
    #[On('update-value-input-currency')]
    public function updatedValue($value)
    {
        $this->rawValue = $this->parseValue($value);
        $this->value = $this->formatCurrency($this->rawValue);
        
        // Emit event untuk parent component
        $this->dispatch('currency-updated', [
            'name' => $this->name,
            'value' => $this->rawValue,
            'formatted' => $this->value
        ]);
    }
    
    private function parseValue($value)
    {
        // Remove semua karakter kecuali angka
        return (int) preg_replace('/[^0-9]/', '', $value);
    }
    
    private function formatCurrency($value)
    {
        if ($value == 0) return '';
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
    
    public function getRawValue()
    {
        return $this->rawValue;
    }
    
    public function setValue($value)
    {
        $this->rawValue = $this->parseValue($value);
        $this->value = $this->formatCurrency($this->rawValue);
    }
    
    #[On('clear-input-currency')]
    public function clear()
    {
        $this->rawValue = 0;
        $this->value = '';
        $this->dispatch('currency-updated', [
            'name' => $this->name,
            'value' => $this->rawValue,
            'formatted' => $this->value
        ]);
    }

    public function render()
    {
        return view('livewire.widget.currency-input');
    }
}