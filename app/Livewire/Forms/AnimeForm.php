<?php

namespace App\Livewire\Forms;

use App\Models\ListAnime\Anime;
use App\Models\ListAnime\UserAnimeProgres;
use Flux\Flux;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AnimeForm extends Form
{
    public ?Anime $anime = null;
    public ?UserAnimeProgres $user_anime_progres = null;
    public $oldPoster;      

    public $poster, $title, $season, $year, $status, $totalEpisode, $airDate, $lastWatch;

    public bool $reminder = false;
    
    protected function rules()
    {
        return [
            'poster' => 'required|image|max:5000|mimes:jpg,png,jpeg',
            'title' => 'required|string|max:255',
            'season' => 'required|in:Winter,Spring,Summer,Fall',
            'year' => 'required|integer|between:1990,' . date('Y'),
            'status' => 'required|in:Ongoing,Finished',
            'totalEpisode' => 'nullable|integer|min:1',
            'airDate' => 'nullable|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'lastWatch' => 'required|integer',
            'reminder' => 'boolean',
        ];
    }

    public function store()
    {
        $this->validate();

        if($this->anime && $this->user_anime_progres) {
            // updater
        } else {
            $saveImage = $this->poster->storeAs(path: 'animes', name: $this->title . '_' . now()->timestamp . '.' . $this->poster->getClientOriginalExtension());
            
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
                'anime_id' => $animeId->id,
                'last_watched_episode' => $this->lastWatch,
                'reminder_enabled' => $this->reminder,
                'created_at' => now(),
                // 'updaterd_at' => now(),
            ]);

            $this->reset();
            Flux::modal('add-anime')->close();
        }
    }
}
