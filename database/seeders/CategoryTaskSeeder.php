<?php

namespace Database\Seeders;

use App\Models\CategoryTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryTasks = [
            'Revisi',
            'To do',
            'Error',
            'Done',
        ];
        
        foreach($categoryTasks as $categori) {
            CategoryTask::create(['name_category_task' => $categori]);
        }
        
    }
}
