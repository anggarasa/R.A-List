<?php

namespace Database\Seeders;

use App\Models\JobList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 50; $i++) {
            JobList::create([
                'name_job_list' => 'job ' . $i,
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae voluptatibus omnis ipsa, atque commodi similique tempora tempore laborum nam aliquam, quod, sit quas itaque magni animi reprehenderit ducimus architecto hic.',
                'category_task_id' => rand(1, 3), // random antara 1-3
                'status_task_id' => rand(1, 5),   // random antara 1-5
            ]);
        }
    }
}
