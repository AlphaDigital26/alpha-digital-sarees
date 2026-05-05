<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Our Story | Alpha Digital')]
class About extends Component
{
    public function render()
    {
        return view('livewire.shop.about');
    }
}