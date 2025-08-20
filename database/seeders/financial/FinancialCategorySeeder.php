<?php

namespace Database\Seeders\financial;

use App\Models\financial\FinancialCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $categories = [
            [
                'name' => 'Makanan & Minuman',
                'type' => 'expense',
            ],
            [
                'name' => 'Transportasi',
                'type' => 'expense',
            ],
            [
                'name' => 'Hiburan',
                'type' => 'expense',
            ],
            [
                'name' => 'Pendidikan',
                'type' => 'expense',
            ],
            [
                'name' => 'Pembelian',
                'type' => 'expense',
            ],
            [
                'name' => 'Tagihan',
                'type' => 'expense',
            ],
            [
                'name' => 'Kesehatan',
                'type' => 'expense',
            ],
            [
                'name' => 'Rumah Tangga',
                'type' => 'expense',
            ],
            [
                'name' => 'Investasi',
                'type' => 'expense',
            ],
            [
                'name' => 'Gajih',
                'type' => 'income',
            ],
            [
                'name' => 'Bonus',
                'type' => 'income',
            ],
            [
                'name' => 'Penjualan',
                'type' => 'income',
            ],
            [
                'name' => 'Hadiah/Donasi',
                'type' => 'income',
            ],
        ];

        foreach($categories as $category) {
            FinancialCategory::create([
                'name' => $category['name'],
                'type' => $category['type'],
            ]);
        }
    }
}
