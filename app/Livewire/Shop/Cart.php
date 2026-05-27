<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Product;

class Cart extends Component
{
    public function mount()
    {
        // If the user visits their normal cart, clear any lingering direct-purchase carts
        session()->forget('buy_now_cart');
    }

    #[Computed]
    public function cartData()
    {
        $sessionCart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        if (!empty($sessionCart)) {
            $products = Product::with('fabric')->whereIn('id', array_keys($sessionCart))->get();

            foreach ($products as $product) {
                $qty = $sessionCart[$product->id] ?? 1;
                $items[$product->id] = [
                    'product' => $product,
                    'qty' => $qty,
                ];
                $subtotal += ($product->current_price * $qty); 
            }
        }

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
            $product = Product::find($productId);
            if ($product && $cart[$productId] < $product->stock) {
                $cart[$productId]++;
                session()->put('cart', $cart);
            } elseif ($product) {
                $this->dispatch('toast', msg: 'Maximum available stock reached', type: 'error');
            }
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
                $this->dispatch('toast', msg: 'Item removed from cart', type: 'success');
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
            $this->dispatch('toast', msg: 'Item removed from cart', type: 'success');
        }
    }

    public function checkout()
    {
        if (!auth('customer')->check()) {
            $this->dispatch('open-login-modal');
            return;
        }
        
        $cart = $this->cartData();
        if (empty($cart['items'])) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        // Redirect to Step 1: Delivery Address Page
        return redirect()->route('checkout.address');
    }

    public function render()
    {
        return view('livewire.shop.cart');
    }
}