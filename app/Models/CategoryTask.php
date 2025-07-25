<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTask extends Model
{
    protected $fillable = ['name_category_task'];

    // Hash Many
    public function JobLists()
    {
        return $this->hasMany(JobList::class);
    }
}
