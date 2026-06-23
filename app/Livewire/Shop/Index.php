<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Models\Fabric;
use App\Models\Color;
use App\Models\Pattern;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    #[Url]
    public $filter = null;

    #[Url]
    public $occasion = null;

    // Filter Arrays
    #[Url]
    public $selectedFabrics = [];
    
    #[Url]
    public $selectedColors = [];
    
    #[Url]
    public $selectedPatterns = [];
    
    // Load More Property
    public $amount = 20;
    
    // Price & Sorting Filters from your UI
    public $priceRange = null;
    public $minPrice = null;
    public $maxPrice = null;
    public $sortBy = 'latest';

    // Reset amount when any filter is clicked
    public function updated($propertyName)
    {
        $this->amount = 20;
    }
    
    public function loadMore()
    {
        $this->amount += 16;
    }

    public function resetFilters()
    {
        // Removed selectedOccasions from reset
        $this->reset(['selectedFabrics', 'selectedColors', 'selectedPatterns', 'priceRange', 'minPrice', 'maxPrice', 'search']);
        $this->amount = 8;
    }

    // Paste this inside the Index class, right before public function render()
    public function toggleWishlist($productId)
    {
        // 1. If they are a guest, stop them and open the Login Popup!
        if (!auth('customer')->check()) {
            session()->put('pending_wishlist_item', $productId);
            session()->put('url.intended', request()->header('Referer'));
            $this->dispatch('open-login-modal');
            return; 
        }

        // 2. Toggle in DB
        $added = \App\Services\WishlistService::toggle($productId);
        
        if ($added) {
            $this->dispatch('toast', msg: 'Added to Wishlist!', type: 'success');
        } else {
            $this->dispatch('toast', msg: 'Removed from Wishlist', type: 'success');
        }
        
        $this->dispatch('wishlist-updated');
    }

    #[On('wishlist-updated')]
    #[On('refresh-wishlist')]
    public function refreshWishlistState()
    {
        // Empty method to trigger re-render
    }

    public function render()
    {
        $query = Product::query()->with(['fabric', 'color', 'pattern']);

        // Search Filter
        $query->when(!empty($this->search), function($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('description', 'like', '%' . $this->search . '%');
        });

        // Best Seller Filter
        $query->when($this->filter === 'best_seller', function($q) {
            $q->where('is_best_seller', true);
        });

        // Occasion Filter
        $query->when(!empty($this->occasion), function($q) {
            $q->where('occasion', $this->occasion);
        });

        // Checkbox Filters (Removed occasion filter logic)
        $query->when(!empty($this->selectedFabrics), fn($q) => $q->whereIn('fabric_id', $this->selectedFabrics));
        $query->when(!empty($this->selectedColors), fn($q) => $q->whereIn('color_id', $this->selectedColors));
        $query->when(!empty($this->selectedPatterns), fn($q) => $q->whereIn('pattern_id', $this->selectedPatterns));

        // Radio Button Price Filters
        if ($this->priceRange === 'under_2k') $query->where('current_price', '<', 2000);
        if ($this->priceRange === '2k_3k') $query->whereBetween('current_price', [2000, 3000]);
        if ($this->priceRange === '3k_5k') $query->whereBetween('current_price', [3000, 5000]);
        if ($this->priceRange === 'above_5k') $query->where('current_price', '>', 5000);

        // Custom Min/Max Price Filters
        $query->when($this->minPrice, fn($q) => $q->where('current_price', '>=', $this->minPrice));
        $query->when($this->maxPrice, fn($q) => $q->where('current_price', '<=', $this->maxPrice));

        // Sorting Logic
        if ($this->sortBy === 'price_desc') {
            $query->orderBy('current_price', 'desc');
        } elseif ($this->sortBy === 'price_asc') {
            $query->orderBy('current_price', 'asc');
        } else {
            $query->latest();
        }

        return view('livewire.shop.index', [
            'products' => $query->paginate($this->amount), 
            'fabrics' => Fabric::has('products')->get(),
            'colors' => Color::has('products')->get(),
            'patterns' => Pattern::has('products')->get(),
        ]);
    }

    
}