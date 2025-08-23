<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use App\Models\financial\FinancialCategory;
use Database\Seeders\financial\FinancialGoalsSeeder;
use Database\Seeders\financial\FinancialAccountSeeder;
use Database\Seeders\financial\FinancialCategorySeeder;
use Database\Seeders\financial\FinancialTransactionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            FinancialCategorySeeder::class,
            FinancialAccountSeeder::class,
            // FinancialGoalsSeeder::class,
            // FinancialTransactionSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Anggara Saputra',
            'email' => 'anggarasaputra273@gmail.com',
            'password' => Hash::make("anggara#r.a_list"),
        ]);
    }
}
