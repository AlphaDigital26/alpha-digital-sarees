<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Fabric;
use App\Models\Color;
use App\Models\Pattern;

class Index extends Component
{
    use WithPagination;

    // Filter Arrays
    public $selectedFabrics = [];
    public $selectedColors = [];
    public $selectedPatterns = [];
    
    // Price & Sorting Filters from your UI
    public $priceRange = null;
    public $minPrice = null;
    public $maxPrice = null;
    public $sortBy = 'latest';

    // Reset pagination when any filter is clicked
    public function updated($propertyName)
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        // Removed selectedOccasions from reset
        $this->reset(['selectedFabrics', 'selectedColors', 'selectedPatterns', 'priceRange', 'minPrice', 'maxPrice']);
    }

    public function render()
    {
        $query = Product::query()->with(['fabric', 'color', 'pattern']);

        // Checkbox Filters (Removed occasion filter logic)
        $query->when(!empty($this->selectedFabrics), fn($q) => $q->whereIn('fabric_id', $this->selectedFabrics));
        $query->when(!empty($this->selectedColors), fn($q) => $q->whereIn('color_id', $this->selectedColors));
        $query->when(!empty($this->selectedPatterns), fn($q) => $q->whereIn('pattern_id', $this->selectedPatterns));

        // Radio Button Price Filters
        if ($this->priceRange === 'under_5k') $query->where('current_price', '<', 5000);
        if ($this->priceRange === '5k_10k') $query->whereBetween('current_price', [5000, 10000]);
        if ($this->priceRange === '10k_20k') $query->whereBetween('current_price', [10000, 20000]);
        if ($this->priceRange === 'above_20k') $query->where('current_price', '>', 20000);

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
            'products' => $query->paginate(24), 
            'fabrics' => Fabric::all(),
            'colors' => Color::all(),
            'patterns' => Pattern::all(),
        ]);
    }
}