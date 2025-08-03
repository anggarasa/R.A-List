<?php

namespace App\Livewire\Widget;

use Livewire\Component;

class DatePiker extends Component
{
    public $mode = 'single'; // 'single' atau 'range'
    public $singleDate = '';
    public $startDate = '';
    public $endDate = '';
    public $label = '';
    public $placeholder = 'Pilih tanggal';
    public $required = false;
    public $disabled = false;
    public $minDate = '';
    public $maxDate = '';
    
    // Properties untuk styling
    public $inputClass = '';
    public $containerClass = '';
    
    public function mount($mode = 'single', $label = '', $placeholder = 'Pilih tanggal', $required = false, $disabled = false, $minDate = '', $maxDate = '')
    {
        $this->mode = $mode;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->minDate = $minDate;
        $this->maxDate = $maxDate;
        
        // Set default min date to today if not specified
        if (empty($this->minDate)) {
            $this->minDate = now()->format('Y-m-d');
        }
    }
    
    public function updatedStartDate($value)
    {
        if ($this->mode === 'range' && $value) {
            // Jika start date diubah dan ada end date yang sudah dipilih
            // Reset end date jika kurang dari start date
            if ($this->endDate && $this->endDate < $value) {
                $this->endDate = '';
            }
        }
        
        $this->dispatch('dateChanged', [
            'mode' => $this->mode,
            'singleDate' => $this->singleDate,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }
    
    public function updatedEndDate($value)
    {
        $this->dispatch('dateChanged', [
            'mode' => $this->mode,
            'singleDate' => $this->singleDate,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }
    
    public function updatedSingleDate($value)
    {
        $this->dispatch('dateChanged', [
            'mode' => $this->mode,
            'singleDate' => $this->singleDate,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
    }
    
    public function getMinDateForEndDate()
    {
        if ($this->mode === 'range' && $this->startDate) {
            return $this->startDate;
        }
        return $this->minDate;
    }
    
    public function render()
    {
        return view('livewire.widget.date-piker');
    }
}
