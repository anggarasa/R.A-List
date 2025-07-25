<?php

namespace Database\Seeders;

use App\Models\StatusTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusTasks = [
            'Panding',
            'In Progress',
            'Completed',
            'Error',
            'Revisi',
        ];

        foreach($statusTasks as $status) {
            StatusTask::create(['name_status_task' => $status]);
        }
    }
}
