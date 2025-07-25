<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobList extends Model
{
    protected $fillable = [
        'category_task_id',
        'name_job_list',
        'description',
        'date_job',
    ];

    // Belongs To
    public function categorytask()
    {
        return $this->belongsTo(CategoryTask::class);
    }
}
