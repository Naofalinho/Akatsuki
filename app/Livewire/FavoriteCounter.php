<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class FavoriteCounter extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->loadCount();
    }

    public function loadCount(): void
    {
        $this->count = count(session()->get('favoris', []));
    }

    #[On('favoris-updated')]
    public function refreshCount(): void
    {
        $this->loadCount();
    }

    public function render()
    {
        return view('components.favorite-counter');
    }
}
