<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('New Arrivals | Alpha Digital')]
class NewArrival extends Component
{
    public function render()
    {
        return view('livewire.shop.new-arrival');
    }
}