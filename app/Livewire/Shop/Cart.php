<?php

namespace App\Livewire\Shop;

use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $subtotal = 0;
    public $shipping = 0;
    public $total = 0;

    public function mount()
    {
        // Fetch cart from session, or load default items matching your design
        $this->cartItems = session()->get('cart', [
            1 => [
                'id' => 1,
                'name' => 'Pure Linen Saree | Pastel Pink',
                'price' => 4500,
                'qty' => 1,
                'image' => 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80'
            ],
            2 => [
                'id' => 2,
                'name' => 'Handloom Silk | Sunset Orange',
                'price' => 3999,
                'qty' => 1,
                'image' => 'https://images.unsplash.com/photo-1610030469915-055106670868?auto=format&fit=crop&q=80'
            ]
        ]);

        $this->calculateTotals();
    }

    public function incrementQty($id)
    {
        if (isset($this->cartItems[$id])) {
            $this->cartItems[$id]['qty']++;
            $this->updateCart();
        }
    }

    public function decrementQty($id)
    {
        if (isset($this->cartItems[$id]) && $this->cartItems[$id]['qty'] > 1) {
            $this->cartItems[$id]['qty']--;
            $this->updateCart();
        }
    }

    public function removeItem($id)
    {
        if (isset($this->cartItems[$id])) {
            unset($this->cartItems[$id]);
            $this->updateCart();
        }
    }

    public function updateCart()
    {
        session()->put('cart', $this->cartItems);
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        foreach ($this->cartItems as $item) {
            $this->subtotal += ($item['price'] * $item['qty']);
        }
        
        // Example: Free shipping over 10000, otherwise 150
        $this->shipping = ($this->subtotal > 10000 || $this->subtotal == 0) ? 0 : 150;
        $this->total = $this->subtotal + $this->shipping;
    }

    public function checkout()
    {
        // This will be connected to Razorpay later
        session()->flash('message', 'Proceeding to checkout...');
    }

    public function render()
    {
        return view('livewire.shop.cart');
    }
}