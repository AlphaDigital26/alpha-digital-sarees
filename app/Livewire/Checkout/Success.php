<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use App\Models\Order;

class Success extends BaseCheckoutComponent
{
    public Order $order;
    public $address;

    public function mount($orderId)
    {
        $this->ensureAuthenticated();

        $this->order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();
        
        $this->address = $this->getCheckoutAddress() ?? $this->getDefaultAddress();
    }

    public function render()
    {
        return view('livewire.checkout.success')->layout('components.layouts.app');
    }
}