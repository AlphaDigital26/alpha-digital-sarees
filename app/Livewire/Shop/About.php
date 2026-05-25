<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Story;

class About extends Component
{
    public $story;

    public function mount()
    {
        // Fetches the first story (or a blank one)
        $this->story = Story::first() ?? new Story(['title' => 'Our Story', 'content' => 'Content coming soon...']);
    }

    public function render()
    {
        return view('livewire.shop.about');
    }
}