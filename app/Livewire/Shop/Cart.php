<?php

namespace App\Livewire\Shop;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\CartService;

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
        return CartService::getCart();
    }

    public function incrementQty($productId)
    {
        if (!CartService::incrementQty($productId)) {
            $this->dispatch('toast', msg: 'Maximum available stock reached', type: 'error');
        } else {
            $this->dispatch('cart-updated');
        }
    }

    public function decrementQty($productId)
    {
        if (CartService::decrementQty($productId)) {
            $this->dispatch('toast', msg: 'Cart updated', type: 'success');
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($productId)
    {
        CartService::remove($productId);
        $this->dispatch('toast', msg: 'Item removed from cart', type: 'success');
        $this->dispatch('cart-updated');
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