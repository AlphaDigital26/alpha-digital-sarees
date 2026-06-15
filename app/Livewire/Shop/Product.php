<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use App\Models\Product as ProductModel;

class Product extends Component
{
    public $product;
    public $activeImage;
    public $quantity = 1;

    public int $rating = 5;
    public string $comment = '';

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

    public function addToCart($productId, $isBuyNow = false)
    {
        \App\Services\CartService::add($productId, $this->quantity);
        
        if (!$isBuyNow) {
            $this->dispatch('toast', msg: 'Item added to cart', type: 'success');
        }

        // Update cart counter (if navbar is listening)
        $this->dispatch('cart-updated');
    }

    // Handles the "Buy It Now" button
    public function buyNow($productId)
    {
        // 1. If they are a guest, stop them and open the Login Popup!
        if (!auth('customer')->check()) {
            session()->put('url.intended', route('checkout.summary'));
            $this->dispatch('open-login-modal');
            return; 
        }

        // 2. Add to the main cart silently in the background (no toast)
        $this->addToCart($productId, true);

        // 3. Create an isolated cart session just for this direct purchase
        session()->put('buy_now_cart', [
            $productId => $this->quantity
        ]);
        
        return redirect()->route('checkout.summary');
    }

    // Handles the "Add to Wishlist" button
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
}