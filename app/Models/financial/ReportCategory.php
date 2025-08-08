<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

//    Has Many
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
