<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Occasion as OccasionModel;

class Occasion extends Component
{
    // Paste this inside the Occasion class, right before public function render()
    public function toggleWishlist($productId)
    {
        // 1. If they are a guest, stop them and open the Login Popup!
        if (!auth('customer')->check()) {
            session()->put('pending_wishlist_item', $productId);
            session()->put('url.intended', request()->header('Referer'));
            $this->dispatch('open-login-modal');
            return; 
        }

        // 2. If they are logged in, run your normal wishlist logic
        $wishlist = session()->get('wishlist', []);
        
        if (in_array($productId, $wishlist)) {
            $wishlist = array_filter($wishlist, fn($id) => $id != $productId);
            session()->flash('success', 'Removed from Wishlist');
        } else {
            $wishlist[] = $productId;
            session()->flash('success', 'Added to Wishlist!');
        }
        
        session()->put('wishlist', $wishlist);
        $this->dispatch('wishlist-updated');
    }

    public function render()
    {
        // Fetch all occasions created in the admin panel
        $occasions = OccasionModel::orderBy('name')->get();

        // Fetch all products that have an occasion assigned, and group them by that occasion
        $productsByOccasion = Product::whereNotNull('occasion')
            ->latest()
            ->get()
            ->groupBy('occasion');

        return view('livewire.shop.occasion', [
            'occasions' => $occasions,
            'productsByOccasion' => $productsByOccasion
        ]);
    }
}