<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Story;

class About extends Component
{
    public function render()
    {
        // Always fetch the latest record from the database
        $story = Story::find(1) ?? new Story();

        return view('livewire.shop.about', [
            'story' => $story
        ]);
    }
}