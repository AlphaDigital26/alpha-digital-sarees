<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;

class Cart extends Component
{
    // Using Computed properties is the most secure way to handle Cart models in Livewire 3
    #[Computed]
    public function cartData()
    {
        $sessionCart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        if (!empty($sessionCart)) {
            // Fetch products along with their fabric names
            $products = Product::with('fabric')->whereIn('id', array_keys($sessionCart))->get();

            foreach ($products as $product) {
                $qty = $sessionCart[$product->id] ?? 1;
                $items[$product->id] = [
                    'product' => $product,
                    'qty' => $qty,
                ];
                
                // FIXED: Using current_price from your database
                $subtotal += ($product->current_price * $qty); 
            }
        }

        // Apply complimentary shipping over 10,000
        $shipping = ($subtotal > 10000 || $subtotal == 0) ? 0 : 150;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ];
    }

    public function incrementQty($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]++;
            session()->put('cart', $cart);
        }
    }

    public function decrementQty($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]);
                session()->flash('success', 'Item removed from cart');
            }
            session()->put('cart', $cart);
        }
    }

    public function removeItem($productId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            session()->flash('success', 'Item removed from cart');
        }
    }

    public function checkout()
    {
        // 1. Check if logged in first
        if (!auth('customer')->check()) {
            $this->dispatch('open-login-modal');
            return;
        }
        
        if (empty(session()->get('cart', []))) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }
        session()->flash('message', 'Proceeding to secure checkout...');
    }

    public function render()
    {
        return view('livewire.shop.cart');
    }
}