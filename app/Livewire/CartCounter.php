<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    public int $count = 0;

    public function mount(): void
    {
        $this->updateCount();
    }

    #[On('cart-updated')]
    public function updateCount(): void
    {
        $cart = session()->get('cart', []);

        $this->count = collect($cart)->sum('quantity');
    }

    public function render()
    {
        return view('components.cart-counter');
    }
}
