<?php

namespace Database\Seeders\financial;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\financial\FinancialGoal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinancialGoalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $goals = [
            [
                'name' => 'Dana Darurat 6 Bulan',
                'description' => 'Menyiapkan dana darurat untuk kebutuhan hidup 6 bulan ke depan',
                'target_amount' => 30000000,
                'current_amount' => 15000000,
                'target_date' => Carbon::now()->addMonths(8),
                'start_date' => Carbon::now()->subMonths(2),
                'status' => 'active',
                'priority' => 'high',
                'category' => 'emergency',
                'settings' => json_encode([
                    'auto_save' => true,
                    'monthly_target' => 5000000,
                    'reminders' => true
                ])
            ],
            [
                'name' => 'DP Rumah Idaman',
                'description' => 'Down payment untuk membeli rumah impian di area Bandung',
                'target_amount' => 150000000,
                'current_amount' => 45000000,
                'target_date' => Carbon::now()->addYears(2),
                'start_date' => Carbon::now()->subMonths(6),
                'status' => 'active',
                'priority' => 'high',
                'category' => 'house',
                'milestones' => json_encode([
                    ['amount' => 50000000, 'description' => 'Milestone pertama - 1/3 target', 'achieved' => false],
                    ['amount' => 100000000, 'description' => 'Milestone kedua - 2/3 target', 'achieved' => false],
                    ['amount' => 150000000, 'description' => 'Target tercapai!', 'achieved' => false]
                ])
            ],
            [
                'name' => 'Liburan Keluarga ke Jepang',
                'description' => 'Trip keluarga 7 hari ke Jepang termasuk hotel, transportasi, dan jajan',
                'target_amount' => 25000000,
                'current_amount' => 18000000,
                'target_date' => Carbon::now()->addMonths(4),
                'start_date' => Carbon::now()->subMonths(8),
                'status' => 'active',
                'priority' => 'medium',
                'category' => 'vacation'
            ],
            [
                'name' => 'Investasi Saham Blue Chip',
                'description' => 'Modal investasi untuk membeli saham-saham blue chip Indonesia',
                'target_amount' => 50000000,
                'current_amount' => 12000000,
                'target_date' => Carbon::now()->addMonths(18),
                'start_date' => Carbon::now()->subMonths(3),
                'status' => 'active',
                'priority' => 'medium',
                'category' => 'investment'
            ],
            [
                'name' => 'Kursus S2 MBA',
                'description' => 'Biaya kuliah S2 MBA di universitas ternama',
                'target_amount' => 75000000,
                'current_amount' => 75000000,
                'target_date' => Carbon::now()->subMonths(1),
                'start_date' => Carbon::now()->subYears(2),
                'status' => 'completed',
                'priority' => 'high',
                'category' => 'education',
                'completed_at' => Carbon::now()->subMonths(1)
            ],
            [
                'name' => 'Ganti Mobil Keluarga',
                'description' => 'Tabungan untuk tukar tambah mobil keluarga yang lebih besar',
                'target_amount' => 80000000,
                'current_amount' => 22000000,
                'target_date' => Carbon::now()->addMonths(15),
                'start_date' => Carbon::now()->subMonths(4),
                'status' => 'paused',
                'priority' => 'low',
                'category' => 'car'
            ],
            [
                'name' => 'Renovasi Kamar Tidur',
                'description' => 'Merenovasi kamar tidur utama dengan furniture baru',
                'target_amount' => 15000000,
                'current_amount' => 3000000,
                'target_date' => Carbon::now()->addMonths(6),
                'start_date' => Carbon::now()->subMonth(),
                'status' => 'active',
                'priority' => 'low',
                'category' => 'other'
            ],
            [
                'name' => 'Gadget Gaming Setup',
                'description' => 'Membeli gaming PC dan aksesoris untuk hobby gaming',
                'target_amount' => 20000000,
                'current_amount' => 8500000,
                'target_date' => Carbon::now()->addMonths(10),
                'start_date' => Carbon::now()->subMonths(2),
                'status' => 'active',
                'priority' => 'low',
                'category' => 'other'
            ]
        ];

        foreach ($goals as $goal) {
            FinancialGoal::create($goal);
        }
    }
}
