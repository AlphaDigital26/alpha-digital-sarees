<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\Attributes\On;

class NavIcons extends Component
{
    public $cartCount = 0;
    public $wishlistCount = 0;

    public function mount()
    {
        $this->updateCounts();
    }

    #[On('cart-updated')]
    #[On('wishlist-updated')]
    public function updateCounts()
    {
        $this->cartCount = \App\Services\CartService::getCartCount();
        $this->wishlistCount = \App\Services\WishlistService::getWishlistCount();
    }

    public function render()
    {
        return view('livewire.nav-icons');
    }
}
