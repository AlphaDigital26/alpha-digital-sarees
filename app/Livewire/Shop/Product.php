<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Ivory Linen Saree | Alpha Digital')]
class Product extends Component
{
    public function render()
    {
        return view('livewire.shop.product');
    }
}