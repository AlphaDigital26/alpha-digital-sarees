<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;

class Index extends Component
{
    public $search = '';
    public $fabric = '';
    public $sort = 'latest';

    public function render()
    {
        $products = Product::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->fabric, function ($q) {
                $q->where('fabric', $this->fabric);
            })
            ->when($this->sort === 'price_low', function ($q) {
                $q->orderBy('price', 'asc');
            })
            ->when($this->sort === 'price_high', function ($q) {
                $q->orderBy('price', 'desc');
            })
            ->latest()
            ->get();

        return view('livewire.shop.index', compact('products'));
    }
}