<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\WishlistService;
use App\Services\CartService;

class Wishlist extends Component
{
    #[Computed]
    public function wishlistItems()
    {
        return WishlistService::getWishlist()->pluck('product');
    }

    public function removeItem($productId)
    {
        WishlistService::remove($productId);
        $this->dispatch('toast', msg: 'Item removed from wishlist', type: 'success');
        $this->dispatch('wishlist-updated');
    }

    public function moveToCart($productId)
    {
        CartService::add($productId);
        WishlistService::remove($productId); // Removing it from wishlist since it's "moved"
        
        $this->dispatch('cart-updated');
        $this->dispatch('wishlist-updated');
        
        session()->flash('success', 'Added to your Shopping Bag!');
        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.shop.wishlist');
    }
}