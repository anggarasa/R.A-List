<?php

namespace Database\Seeders\financial;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\financial\FinancialTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinancialTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['income', 'expense'];

        // Loop tanggal 1-31 Juli 2025
        for ($day = 1; $day <= 31; $day++) {
            FinancialTransaction::create([
                'financial_category_id' => rand(1, 13),
                'financial_account_id' => rand(1, 4),
                'type' => $types[array_rand($types)],
                'amount' => rand(10000, 5000000), // random 10rb - 5jt
                'description' => 'Dummy transaction ' . Str::random(5),
                'transaction_date' => "2025-07-" . str_pad($day, 2, '0', STR_PAD_LEFT),
            ]);
        }
    }
}
