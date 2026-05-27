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

    // Handles the "Add to Cart" button
    public function addToCart($productId, $isBuyNow = false)
    {
        // 1. Get current cart from session
        $cart = session()->get('cart', []);

        // 2. Add or increment quantity using the selected quantity
        if (isset($cart[$productId])) {
            $cart[$productId] += $this->quantity;
        } else {
            $cart[$productId] = $this->quantity;
        }

        // 3. Save back to session
        session()->put('cart', $cart);

        // 5. Update cart counter (if navbar is listening)
        $this->dispatch('cart-updated');
        
        if (!$isBuyNow) {
            $this->dispatch('toast', msg: 'Item added to cart', type: 'success');
        }
    }

    // Handles the "Buy It Now" button
    public function buyNow($productId)
    {
        // 1. Add to the main cart silently in the background (no toast)
        $this->addToCart($productId, true);

        // 2. Create an isolated cart session just for this direct purchase
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

        // 2. If they are logged in, run your normal wishlist logic
        $wishlist = session()->get('wishlist', []);
        
        if (in_array($productId, $wishlist)) {
            $wishlist = array_filter($wishlist, fn($id) => $id != $productId);
            $this->dispatch('toast', msg: 'Removed from Wishlist', type: 'success');
        } else {
            $wishlist[] = $productId;
            $this->dispatch('toast', msg: 'Added to Wishlist!', type: 'success');
        }
        
        session()->put('wishlist', $wishlist);
        $this->dispatch('wishlist-updated');
    }

    public function submitReview()
    {
        if (!auth('customer')->check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        \App\Models\Review::create([
            'customer_id' => auth('customer')->id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $this->rating = 5;
        $this->comment = '';
        
        $this->product->load('reviews.customer');

        $this->dispatch('toast', msg: 'Thank you for your review!', type: 'success');
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