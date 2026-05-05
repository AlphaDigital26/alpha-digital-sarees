<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Occasions | Alpha Digital')]
class Occasion extends Component
{
    public function render()
    {
        return view('livewire.shop.occasion');
    }
}