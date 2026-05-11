<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product as ProductModel;

class Product extends Component
{
    public $product;
    public $activeImage;
    public $quantity = 1;

    public function mount($id)
    {
        // Fetch the product and its attributes
        $this->product = ProductModel::with(['fabric', 'color', 'pattern'])->findOrFail($id);
        
        // Set the first image as the default active image
        if (is_array($this->product->images) && count($this->product->images) > 0) {
            $this->activeImage = $this->product->images[0];
        }
    }

    // Handles thumbnail clicks
    public function changeImage($imagePath)
    {
        $this->activeImage = $imagePath;
    }

    // Handles the '+' button
    public function incrementQty()
    {
        // Prevent ordering more than what is in stock
        if ($this->quantity < $this->product->stock) {
            $this->quantity++;
        }
    }

    // Handles the '-' button
    public function decrementQty()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function render()
    {
        // Fetch 3 similar products based on the same fabric (excluding this one)
        $similarProducts = collect();
        if ($this->product->fabric_id) {
            $similarProducts = ProductModel::where('fabric_id', $this->product->fabric_id)
                ->where('id', '!=', $this->product->id)
                ->latest()
                ->take(3)
                ->get();
        }

        // If no products match the fabric, just grab 3 random ones as a fallback
        if ($similarProducts->isEmpty()) {
            $similarProducts = ProductModel::where('id', '!=', $this->product->id)
                ->inRandomOrder()
                ->take(3)
                ->get();
        }

        return view('livewire.shop.product', [
            'similarProducts' => $similarProducts,
            'settings' => \App\Models\Setting::first(), // Pass settings to the frontend
        ]);
    }

    // Handles the "Add to Cart" button
    public function addToCart($productId)
    {
        // 1. Get current cart from session
        $cart = session()->get('cart', []);

        // 2. Add or increment quantity
        if (isset($cart[$productId])) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }

        // 3. Save back to session
        session()->put('cart', $cart);

        // 4. Show success message (Optional: You can trigger a SweetAlert or Toast here)
        session()->flash('success', 'Added to your bag!');
        
        // 5. Redirect to cart automatically (Optional, but good UX for luxury brands)
        return redirect('/cart');
    }
}