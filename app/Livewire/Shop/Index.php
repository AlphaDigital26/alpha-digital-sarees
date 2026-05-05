<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

#[Title('All Sarees | Alpha Digital')]
class Index extends Component
{
    // This tells Livewire to track the ?filter= parameter in the URL
    #[Url]
    public $filter = '';

    public function render()
    {
        // Right now, this just loads the visual page.
        // Later, we will use $this->filter here to ask the database:
        // "Hey, only give me the sarees where the occasion is 'tiranga'"
        
        return view('livewire.shop.index');
    }
}