<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobList extends Model
{
    protected $fillable = [
        'category_task_id',
        'status_task_id',
        'name_job_list',
        'description',
        'date_job',
    ];

    // Belongs To
    public function statusTask()
    {
        return $this->belongsTo(StatusTask::class);
    }
    
    public function categoryTask()
    {
        return $this->belongsTo(CategoryTask::class);
    }
}
