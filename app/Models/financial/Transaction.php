<?php

namespace App\Models\financial;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'report_category_id',
        'date',
        'amount',
        'description',
        'type',
    ];

    //    Belongs To
    public function ReportCategory()
    {
        return $this->belongsTo(ReportCategory::class);
    }
}
