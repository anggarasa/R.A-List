<?php

namespace App\Models\job;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    // Belong To
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
