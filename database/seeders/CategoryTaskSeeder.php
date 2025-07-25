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
            'Slicing',
            'Integration API',
            'Clean Code',
        ];
        
        foreach($categoryTasks as $categori) {
            CategoryTask::create(['name_category_task' => $categori]);
        }
        
    }
}
