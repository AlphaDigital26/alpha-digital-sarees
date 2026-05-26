<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Order;

class OrderDetails extends Component
{
    public $order;

    public function mount($id)
    {
        $this->order = Order::with('items.product')
            ->where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.profile.order-details')->layout('components.profile-layout');
    }
}
