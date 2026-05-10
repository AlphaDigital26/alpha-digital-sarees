<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product;
use App\Models\Occasion as OccasionModel;

class Occasion extends Component
{
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