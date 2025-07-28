<?php

namespace App\Models\ListAnime;

use Illuminate\Database\Eloquent\Model;

class Anime extends Model
{
    protected $fillable = [
        'title',
        'season',
        'year',
        'status',
        'total_episodes',
        'air_day',
        'next_air_day',
    ];

    // Has Many
    public function animeProgreses()
    {
        return $this->hasMany(UserAnimeProgres::class);
    }
}
