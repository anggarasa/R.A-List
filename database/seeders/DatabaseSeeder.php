<?php

namespace Database\Seeders;

use App\Models\financial\FinancialCategory;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // JobListSeeder::class,
        ]);

        for($i = 1; $i <= 30; $i++){
            FinancialCategory::create([
                'name' => 'Category '.$i,
                'type' => 'income'
            ]);
        }

        // User::factory()->create([
        //     'name' => 'Anggara Saputra',
        //     'email' => 'anggarasaputra273@gmail.com',
        //     'password' => Hash::make("anggara#r.a_list"),
        // ]);
    }
}
