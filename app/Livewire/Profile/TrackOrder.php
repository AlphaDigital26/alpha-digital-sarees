<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Order;

class TrackOrder extends Component
{
    public $order;

    public function mount($id)
    {
        $this->order = Order::where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.profile.track-order')->layout('components.profile-layout');
    }
}
