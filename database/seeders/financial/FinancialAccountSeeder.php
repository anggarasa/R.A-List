<?php

namespace Database\Seeders\financial;

use Illuminate\Database\Seeder;
use App\Models\financial\FinancialAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinancialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'BCA',
                'type' => 'bank',
            ],
            [
                'name' => 'DANA',
                'type' => 'ewallet',
            ],
            [
                'name' => 'CASH',
                'type' => 'cash',
            ],
            [
                'name' => 'ShopeePay',
                'type' => 'ewallet',
            ],
            [
                'name' => 'Ajaib',
                'type' => 'investment',
            ],
        ];

        foreach($accounts as $account) {
            FinancialAccount::create([
                'name' => $account['name'],
                'type' => $account['type'],
            ]);
        }
    }
}
