<?php

namespace App\Livewire\Checkout;

use Livewire\Component;
use App\Models\Order;

class Success extends Component
{
    public Order $order;
    public $address;

    public function mount($orderId)
    {
        $this->order = Order::with('items.product')
            ->where('id', $orderId)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();
        
        // Fetching the default address to display on the success page
        $this->address = auth('customer')->user()->addresses()->where('is_default', true)->first() 
                         ?? auth('customer')->user()->addresses()->first();
    }

    public function render()
    {
        return view('livewire.checkout.success')->layout('components.layouts.app');
    }
}