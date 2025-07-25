<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTask extends Model
{
    protected $fillable = ['name_status_task'];

    // hash many
    public function jobLists()
    {
        return $this->hasMany(JobList::class);
    }
}
