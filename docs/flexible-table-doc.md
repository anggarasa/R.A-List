# Dokumentasi Flexible Table - Laravel Livewire

## Deskripsi

Flexible Table adalah komponen Livewire yang menyediakan tabel data yang fleksibel dan dapat dikustomisasi dengan fitur-fitur lengkap seperti pencarian, filtering, sorting, pagination, dan aksi CRUD. Komponen ini mendukung tema gelap/terang dan dapat dengan mudah diintegrasikan ke dalam aplikasi Laravel.

## Fitur Utama

- ✅ **Pencarian Real-time** - Pencarian otomatis dengan debounce
- ✅ **Sorting Multi-kolom** - Sorting naik/turun pada kolom yang ditentukan
- ✅ **Filter Dinamis** - Filter berdasarkan nilai kolom atau relasi
- ✅ **Filter Tanggal** - Filter berdasarkan rentang tanggal
- ✅ **Pagination** - Navigasi halaman dengan opsi jumlah data per halaman
- ✅ **Aksi CRUD** - Edit, hapus, dan aksi kustom lainnya
- ✅ **Dark Mode** - Dukungan tema gelap/terang
- ✅ **Format Data** - Format otomatis untuk mata uang, tanggal, badge, dll
- ✅ **Relasi Database** - Dukungan untuk filter dan tampilan data relasi

## Instalasi

### 1. Pastikan Livewire Terinstal

```bash
composer require livewire/livewire
```

### 2. Buat Komponen Flexible Table

Salin kode komponen ke `app/Livewire/Widget/FlexibleTable.php`

### 3. Buat View Template

Salin kode template ke `resources/views/livewire/widget/flexible-table.blade.php`

### 4. Pastikan Tailwind CSS Terkonfigurasi

Komponen ini menggunakan Tailwind CSS untuk styling.

## Penggunaan Dasar

### 1. Menggunakan di Blade Template

```blade
<livewire:widget.flexible-table 
    :model="App\Models\User::class"
    :columns="[
        'name' => ['label' => 'Nama'],
        'email' => ['label' => 'Email'],
        'created_at' => ['label' => 'Tanggal Dibuat', 'format' => 'date']
    ]"
    :searchable="['name', 'email']"
    :sortable="['name', 'email', 'created_at']"
/>
```

### 2. Menggunakan di Controller/Livewire Component

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

class UserManagement extends Component
{
    public function render()
    {
        return view('livewire.user-management');
    }
}
```

**Template (user-management.blade.php):**

```blade
<div>
    <h1 class="text-2xl font-bold mb-6">Manajemen User</h1>
    
    <livewire:widget.flexible-table 
        :model="App\Models\User::class"
        :columns="$this->getColumns()"
        :searchable="['name', 'email']"
        :sortable="['name', 'email', 'created_at']"
        :actions="$this->getActions()"
        :filters="$this->getFilters()"
        :per-page="25"
    />
</div>

@push('scripts')
<script>
    // Handle update event
    Livewire.on('update-data-table', function(data) {
        console.log('Update data:', data);
        // Implementasi update logic
    });
</script>
@endpush
```

## Konfigurasi Parameter

### Model (Wajib)
Model Eloquent yang akan digunakan sebagai sumber data.

```php
:model="App\Models\Product::class"
```

### Columns (Wajib)
Definisi kolom yang akan ditampilkan dalam tabel.

```php
:columns="[
    'name' => ['label' => 'Nama Produk'],
    'price' => ['label' => 'Harga', 'format' => 'currency'],
    'status' => [
        'label' => 'Status',
        'format' => 'badge',
        'badge_colors' => [
            'active' => 'green',
            'inactive' => 'red',
            'pending' => 'yellow'
        ],
        'badge_labels' => [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'pending' => 'Menunggu'
        ]
    ],
    'category_id' => [
        'label' => 'Kategori',
        'relation' => 'category.name'
    ]
]"
```

### Format Kolom yang Tersedia

#### 1. Currency (Mata Uang)
```php
'price' => ['label' => 'Harga', 'format' => 'currency']
```
Output: `Rp 150.000`

#### 2. Date (Tanggal)
```php
'created_at' => ['label' => 'Tanggal', 'format' => 'date']
```
Output: `15 Jan 2024`

#### 3. DateTime (Tanggal & Waktu)
```php
'updated_at' => ['label' => 'Terakhir Update', 'format' => 'datetime']
```
Output: `15 Jan 2024 14:30`

#### 4. Badge (Label)
```php
'status' => [
    'label' => 'Status',
    'format' => 'badge',
    'badge_colors' => [
        'published' => 'green',
        'draft' => 'yellow',
        'archived' => 'gray'
    ],
    'badge_labels' => [
        'published' => 'Dipublikasi',
        'draft' => 'Draft',
        'archived' => 'Diarsipkan'
    ]
]
```

#### 5. Relation (Relasi Database)
```php
'user_id' => [
    'label' => 'Penulis',
    'relation' => 'user.name'
]
```

### Searchable (Opsional)
Kolom yang dapat dicari.

```php
:searchable="['name', 'email', 'description']"
```

### Sortable (Opsional)
Kolom yang dapat diurutkan.

```php
:sortable="['name', 'created_at', 'price']"
```

### Actions (Opsional)
Aksi yang dapat dilakukan pada setiap baris data.

```php
:actions="[
    [
        'label' => 'Edit',
        'method' => 'edit',
        'icon' => '<path d=\"M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7\"></path><path d=\"m18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z\"></path>',
        'class' => 'text-blue-600 hover:text-blue-900'
    ],
    [
        'label' => 'Hapus',
        'method' => 'confirmDelete',
        'confirm' => 'Apakah Anda yakin ingin menghapus data ini?',
        'icon' => '<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16\"></path>',
        'class' => 'text-red-600 hover:text-red-900'
    ]
]"
```

### Filters (Opsional)
Filter dropdown untuk kolom tertentu.

```php
:filters="[
    'status' => ['label' => 'Status'],
    'category_id' => [
        'label' => 'Kategori',
        'relation' => 'category',
        'display_field' => 'name'
    ]
]"
```

### Date Filters (Opsional)
Filter berdasarkan rentang tanggal.

```php
:date-filters="[
    'created_at' => ['label' => 'Tanggal Dibuat'],
    'updated_at' => ['label' => 'Tanggal Update']
]"
```

### Opsi Tampilan

```php
:show-search="true"          // Tampilkan pencarian
:show-per-page="true"        // Tampilkan opsi jumlah per halaman  
:show-pagination="true"      // Tampilkan pagination
:show-filters="true"         // Tampilkan filter
:per-page="10"              // Jumlah data per halaman default
:dark-mode="false"          // Mode gelap
```

## Contoh Implementasi Lengkap

### 1. Model Product

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'status', 'category_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

### 2. Livewire Component

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class ProductManagement extends Component
{
    public function getColumns()
    {
        return [
            'name' => ['label' => 'Nama Produk'],
            'price' => ['label' => 'Harga', 'format' => 'currency'],
            'status' => [
                'label' => 'Status',
                'format' => 'badge',
                'badge_colors' => [
                    'active' => 'green',
                    'inactive' => 'red'
                ],
                'badge_labels' => [
                    'active' => 'Aktif',
                    'inactive' => 'Tidak Aktif'
                ]
            ],
            'category_id' => [
                'label' => 'Kategori',
                'relation' => 'category.name'
            ],
            'created_at' => ['label' => 'Dibuat', 'format' => 'date']
        ];
    }

    public function getFilters()
    {
        return [
            'status' => ['label' => 'Status'],
            'category_id' => [
                'label' => 'Kategori',
                'relation' => 'category',
                'display_field' => 'name'
            ]
        ];
    }

    public function getDateFilters()
    {
        return [
            'created_at' => ['label' => 'Tanggal Dibuat']
        ];
    }

    public function getActions()
    {
        return [
            [
                'label' => 'Edit',
                'method' => 'edit',
                'icon' => '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="m18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>',
                'class' => 'text-blue-600 hover:text-blue-900'
            ],
            [
                'label' => 'Hapus',
                'method' => 'confirmDelete',
                'confirm' => 'Apakah Anda yakin ingin menghapus produk ini?',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>',
                'class' => 'text-red-600 hover:text-red-900'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.product-management');
    }
}
```

### 3. Blade Template

```blade
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Produk</h1>
        <p class="mt-1 text-sm text-gray-600">Kelola produk dalam sistem Anda</p>
    </div>

    <livewire:widget.flexible-table 
        :model="App\Models\Product::class"
        :columns="$this->getColumns()"
        :searchable="['name', 'description']"
        :sortable="['name', 'price', 'created_at']"
        :actions="$this->getActions()"
        :filters="$this->getFilters()"
        :date-filters="$this->getDateFilters()"
        :per-page="25"
        :show-search="true"
        :show-per-page="true"
        :show-pagination="true"
        :show-filters="true"
    />
</div>

@push('scripts')
<script>
    // Handle update event
    Livewire.on('update-data-table', function(product) {
        console.log('Update product:', product);
        // Redirect ke halaman edit atau buka modal
        window.location.href = `/products/${product.id}/edit`;
    });

    // Handle notification event
    Livewire.on('notification', function(data) {
        console.log('Notification:', data);
        // Implementasi notifikasi (bisa menggunakan library seperti SweetAlert2)
        alert(data.message);
    });
</script>
@endpush
```

## Event dan Listener

### Event yang Dipancarkan

1. **update-data-table** - Dipancarkan saat tombol edit diklik
2. **notification** - Dipancarkan untuk notifikasi
3. **refresh-table** - Untuk merefresh tabel dari luar

### Mendengarkan Event

```javascript
// Dalam blade template
Livewire.on('update-data-table', function(data) {
    // Handle update logic
});

Livewire.on('notification', function(notification) {
    // Handle notification
    if (notification.type === 'success') {
        // Show success message
    }
});
```

### Memanggil Refresh dari Luar

```php
// Dari komponen Livewire lain
$this->dispatch('refresh-table');
```

## Kustomisasi Styling

### Menggunakan Class CSS Kustom

```php
:table-class="custom-table-class"
:header-class="custom-header-class"
:body-class="custom-body-class"
```

### Override Warna Badge

```php
'status' => [
    'format' => 'badge',
    'badge_colors' => [
        'pending' => 'orange',
        'approved' => 'green',
        'rejected' => 'red'
    ]
]
```

## Tips dan Best Practices

1. **Performance**: Gunakan relasi eager loading untuk menghindari N+1 query
2. **Indexing**: Pastikan kolom yang searchable dan sortable memiliki database index
3. **Memory**: Batasi jumlah data per halaman untuk performa yang optimal
4. **Security**: Validasi input filter untuk mencegah SQL injection
5. **UX**: Gunakan debounce pada pencarian untuk mengurangi request ke server

## Troubleshooting

### Error: Model not found
Pastikan model class path benar dan model sudah ada.

### Filter tidak berfungsi
Pastikan relasi sudah didefinisikan dengan benar di model dan foreign key ada di database.

### Styling tidak muncul
Pastikan Tailwind CSS sudah terkonfigurasi dan class yang digunakan tersedia.

### Performance lambat
Gunakan database indexing dan batasi jumlah data per halaman.

## Changelog

- **v1.0** - Rilis awal dengan fitur dasar
- **v1.1** - Dukungan relasi database dan filter yang lebih baik
- **v1.2** - Dukungan dark mode dan format data yang lebih lengkap

---

**Catatan**: Komponen ini memerlukan Laravel 8+, Livewire 2.0+, dan Tailwind CSS untuk berfungsi dengan optimal.