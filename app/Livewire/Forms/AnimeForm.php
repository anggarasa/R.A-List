<?php

namespace App\Livewire\Forms;

use App\Models\ListAnime\Anime;
use App\Models\ListAnime\UserAnimeProgres;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AnimeForm extends Form
{
    public ?Anime $anime;
    public ?UserAnimeProgres $user_anime_progres;
    public $oldPoster;

    #[Validate('required|image|mimes:png,jpg,jpeg|max:5000')]
    public $poster;

    #[Validate('required')]
    public $title;
    
    #[Validate('required')]
    public $season;

    #[Validate('required|date')]
    public $year;

    #[Validate('required')]
    public $status;

    #[Validate('nullable')]
    public $totalEpisode;
    
    #[Validate('nullable')]
    public $airDate;

    #[Validate('nullable')]
    public $lastWatch;

    public $reminder;

    public function store()
    {
        $this->validate();

        if($this->anime && $this->user_anime_progres) {
            // updater
        } else {
            $saveImage = $this->storeAs('animes', $this->title . '_' . now()->timestamp . '.' . $this->poster->getClientOriginalExtension(), 'public');
            
            $animeId = Anime::create([
                'title' => $this->title,
                'image' => $saveImage,
                'season' => $this->season,
                'year' => $this->year,
                'status' => $this->status,
                'total_episodes' => $this->totalEpisode,
                'air_day' => $this->airDate,
            ]);

            UserAnimeProgres::create([
                'user_id' => auth()->user()->id,
                'anime_id' => $animeId->id,
                'last_watched_episode' => $this->lastWatch,
                'reminder_enabled' => $this->reminder,
                'updaterd_at' => now(),
            ]);

            $this->reset();
            Flux::modal('add-anime')->close();
        }
    }
}
