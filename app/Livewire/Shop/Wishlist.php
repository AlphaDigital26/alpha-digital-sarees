<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;

class Wishlist extends Component
{
    #[Computed]
    public function wishlistItems()
    {
        $wishlistIds = session()->get('wishlist', []);
        
        if (empty($wishlistIds)) {
            return collect(); 
        }

        return Product::with('fabric')->whereIn('id', $wishlistIds)->get();
    }

    public function removeItem($productId)
    {
        $wishlist = session()->get('wishlist', []);
        $wishlist = array_filter($wishlist, fn($id) => $id != $productId);
        
        session()->put('wishlist', $wishlist);
    }

    public function moveToCart($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }
        session()->put('cart', $cart);

        // DELIBERATELY REMOVED $this->removeItem($productId) SO IT STAYS IN WISHLIST
        
        session()->flash('success', 'Added to your Shopping Bag!');
        return redirect()->route('cart');
    }

    public function render()
    {
        return view('livewire.shop.wishlist');
    }
}