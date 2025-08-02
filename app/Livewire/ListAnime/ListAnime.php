<?php

namespace App\Livewire\ListAnime;

use App\Livewire\Forms\AnimeForm;
use App\Models\ListAnime\UserAnimeProgres;
use Livewire\Component;
use Livewire\WithFileUploads;

class ListAnime extends Component
{
    use WithFileUploads;
    
    public AnimeForm $form;

    public function save()
    {
        $this->form->store();

        $this->dispatch('notification', type: 'success', message: 'Created anime successfully');
    }
    
    public function render()
    {
        return view('livewire.list-anime.list-anime', [
            'animes' => UserAnimeProgres::latest()->get(),
        ]);
    }
}
