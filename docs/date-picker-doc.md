# Dokumentasi DatePicker Livewire Component

## Deskripsi
DatePicker adalah komponen Livewire yang fleksibel untuk pemilihan tanggal dengan dukungan mode single date dan date range. Komponen ini dilengkapi dengan styling yang responsif dan mendukung dark mode.

## Fitur Utama
- ✅ Mode single date dan date range
- ✅ Validasi tanggal otomatis
- ✅ Dukungan dark mode
- ✅ Responsive design
- ✅ Event listener untuk update data
- ✅ Kustomisasi styling
- ✅ Validasi min/max date

## Instalasi

### 1. Simpan File Component
Simpan file PHP component di:
```
app/Livewire/Widget/DatePiker.php
```

### 2. Buat File View
Buat file view di:
```
resources/views/livewire/widget/date-piker.blade.php
```

### 3. Pastikan Dependencies
Pastikan Anda memiliki:
- Laravel Livewire
- Tailwind CSS
- Carbon (sudah termasuk di Laravel)

## Cara Penggunaan

### Mode Single Date

#### Penggunaan Dasar
```blade
<livewire:widget.date-piker 
    mode="single" 
    label="Pilih Tanggal" 
    placeholder="Masukkan tanggal"
/>
```

#### Dengan Validasi Required
```blade
<livewire:widget.date-piker 
    mode="single" 
    label="Tanggal Lahir" 
    :required="true"
    min-date="1900-01-01"
    max-date="{{ now()->format('Y-m-d') }}"
/>
```

#### Dengan Custom Class
```blade
<livewire:widget.date-piker 
    mode="single" 
    label="Event Date"
    input-class="border-2 border-blue-500"
    container-class="mb-6"
/>
```

### Mode Date Range

#### Penggunaan Dasar
```blade
<livewire:widget.date-piker 
    mode="range" 
    label="Periode Laporan"
/>
```

#### Dengan Batasan Tanggal
```blade
<livewire:widget.date-piker 
    mode="range" 
    label="Periode Liburan"
    min-date="{{ now()->format('Y-m-d') }}"
    max-date="{{ now()->addYear()->format('Y-m-d') }}"
    :required="true"
/>
```

## Parameter Component

| Parameter | Type | Default | Deskripsi |
|-----------|------|---------|-----------|
| `mode` | string | 'single' | Mode pemilihan: 'single' atau 'range' |
| `label` | string | '' | Label untuk input |
| `placeholder` | string | 'Pilih tanggal' | Placeholder text |
| `required` | boolean | false | Input wajib diisi |
| `disabled` | boolean | false | Input dalam keadaan disabled |
| `min-date` | string | today | Tanggal minimum (format: Y-m-d) |
| `max-date` | string | '' | Tanggal maksimum (format: Y-m-d) |
| `input-class` | string | '' | Custom CSS class untuk input |
| `container-class` | string | '' | Custom CSS class untuk container |

## Event Handling

### Mendengarkan Perubahan Tanggal
Component akan mengirim event `dateChanged` setiap kali tanggal berubah:

```php
// Di component parent
#[On('dateChanged')]
public function handleDateChange($data)
{
    $mode = $data['mode'];
    $singleDate = $data['singleDate'];
    $startDate = $data['startDate'];
    $endDate = $data['endDate'];
    
    // Proses data sesuai kebutuhan
    if ($mode === 'single') {
        $this->selectedDate = $singleDate;
    } else {
        $this->dateRange = [
            'start' => $startDate,
            'end' => $endDate
        ];
    }
}
```

### Mengupdate Tanggal dari Parent Component
Anda dapat mengupdate tanggal dari component lain:

```php
// Reset tanggal
$this->dispatch('updateDate', [
    'mode' => 'single',
    'reset' => true
]);

// Set tanggal single
$this->dispatch('updateDate', [
    'mode' => 'single',
    'singleDate' => '2024-12-25',
    'reset' => false
]);

// Set date range
$this->dispatch('updateDate', [
    'mode' => 'range',
    'startDate' => '2024-12-01',
    'endDate' => '2024-12-31',
    'reset' => false
]);
```

## Contoh Implementasi Lengkap

### 1. Form dengan Single Date
```blade
{{-- resources/views/livewire/user-form.blade.php --}}
<form wire:submit.prevent="save">
    <div class="space-y-4">
        <livewire:widget.date-piker 
            mode="single" 
            label="Tanggal Lahir"
            :required="true"
            max-date="{{ now()->subYear(17)->format('Y-m-d') }}"
        />
        
        <button type="submit" class="btn btn-primary">
            Simpan
        </button>
    </div>
</form>
```

```php
<?php
// app/Livewire/UserForm.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class UserForm extends Component
{
    public $birthDate = '';
    
    #[On('dateChanged')]
    public function updateBirthDate($data)
    {
        if ($data['mode'] === 'single') {
            $this->birthDate = $data['singleDate'];
        }
    }
    
    public function save()
    {
        $this->validate([
            'birthDate' => 'required|date|before:' . now()->subYear(17)->format('Y-m-d')
        ]);
        
        // Simpan data
    }
    
    public function render()
    {
        return view('livewire.user-form');
    }
}
```

### 2. Filter dengan Date Range
```blade
{{-- resources/views/livewire/report-filter.blade.php --}}
<div class="bg-white p-6 rounded-lg shadow">
    <h3 class="text-lg font-semibold mb-4">Filter Laporan</h3>
    
    <livewire:widget.date-piker 
        mode="range" 
        label="Periode Laporan"
        :required="true"
    />
    
    <div class="mt-4">
        <button wire:click="generateReport" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                :disabled="!startDate || !endDate">
            Generate Laporan
        </button>
    </div>
</div>
```

```php
<?php
// app/Livewire/ReportFilter.php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Carbon\Carbon;

class ReportFilter extends Component
{
    public $startDate = '';
    public $endDate = '';
    
    #[On('dateChanged')]
    public function updateDateRange($data)
    {
        if ($data['mode'] === 'range') {
            $this->startDate = $data['startDate'];
            $this->endDate = $data['endDate'];
        }
    }
    
    public function generateReport()
    {
        if (!$this->startDate || !$this->endDate) {
            session()->flash('error', 'Pilih periode laporan terlebih dahulu');
            return;
        }
        
        $start = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);
        
        // Logic generate report
        session()->flash('success', "Laporan periode {$start->format('d M Y')} - {$end->format('d M Y')} berhasil dibuat");
    }
    
    public function render()
    {
        return view('livewire.report-filter');
    }
}
```

## Styling dan Kustomisasi

### Custom Styling
```blade
<livewire:widget.date-piker 
    mode="single" 
    label="Custom Date"
    input-class="border-2 border-purple-500 focus:ring-purple-500 focus:border-purple-500"
    container-class="bg-gray-50 p-4 rounded-lg"
/>
```

## Troubleshooting

### Tanggal Tidak Terupdate
Pastikan Anda mendengarkan event `dateChanged`:
```php
#[On('dateChanged')]
public function handleDateChange($data) { /* ... */ }
```

### Styling Tidak Sesuai
Pastikan Tailwind CSS sudah dikonfigurasi dengan benar dan class yang digunakan sudah ada.

### Validasi Error
Gunakan `@error` directive untuk menampilkan pesan error:
```blade
@error('startDate')
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror
```

## Tips Penggunaan

1. **Gunakan Carbon untuk manipulasi tanggal:**
   ```php
   use Carbon\Carbon;
   
   $formattedDate = Carbon::parse($this->singleDate)->format('d F Y');
   ```

2. **Validasi di backend:**
   ```php
   $this->validate([
       'startDate' => 'required|date|after_or_equal:today',
       'endDate' => 'required|date|after:startDate'
   ]);
   ```

3. **Set default value:**
   ```php
   public function mount()
   {
       $this->dispatch('updateDate', [
           'mode' => 'single',
           'singleDate' => now()->format('Y-m-d'),
           'reset' => false
       ]);
   }
   ```

## Contoh Use Case

- Form pendaftaran (tanggal lahir)
- Booking sistem (tanggal checkin/checkout)
- Filter laporan (periode tanggal)
- Event scheduling (tanggal mulai/selesai)
- Form pengajuan cuti (tanggal mulai/akhir cuti)