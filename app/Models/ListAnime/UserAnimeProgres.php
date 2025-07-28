<?php

namespace App\Models\ListAnime;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserAnimeProgres extends Model
{
    protected $fillable = [
        'user_id',
        'anime_id',
        'last_watched_episode',
        'reminder_enabled',
        'notes',
        'updated_at',
    ];

    // Belongs To
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function anime()
    {
        return $this->belongsTo(Anime::class);
    }
}
