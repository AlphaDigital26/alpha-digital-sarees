<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Fabric;
use App\Models\Color;
use App\Models\Pattern;

class NewArrival extends Component
{
    // Variables to track user filter selections
    public $sort = 'latest';
    public $selectedFabric = null;
    public $selectedColor = null;
    public $selectedPattern = null;

    // Added Wishlist Toggle Functionality
    public function toggleWishlist($productId)
    {
        // 1. If they are a guest, stop them and open the Login Popup!
        if (!auth('customer')->check()) {
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
        // Start with only new arrivals
        $query = Product::where('is_new', true);

        // 1. Apply Fabric Filter
        if ($this->selectedFabric) {
            $query->where('fabric_id', $this->selectedFabric);
        }
        
        // 2. Apply Color Filter
        if ($this->selectedColor) {
            $query->where('color_id', $this->selectedColor);
        }
        
        // 3. Apply Pattern Filter
        if ($this->selectedPattern) {
            $query->where('pattern_id', $this->selectedPattern);
        }

        // 4. Apply Sorting Filter
        if ($this->sort === 'price_asc') {
            $query->orderBy('current_price', 'asc');
        } elseif ($this->sort === 'price_desc') {
            $query->orderBy('current_price', 'desc');
        } else {
            $query->latest(); // Default
        }

        return view('livewire.shop.new-arrival', [
            'products' => $query->get(),
            'fabrics' => Fabric::orderBy('name')->get(),
            'colors' => Color::orderBy('name')->get(),
            'patterns' => Pattern::orderBy('name')->get(),
        ]);
    }
}