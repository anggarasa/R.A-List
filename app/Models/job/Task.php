<?php

namespace App\Models\job;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'category',
        'priority',
        'due_date',
    ];

    // Belongs To
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
