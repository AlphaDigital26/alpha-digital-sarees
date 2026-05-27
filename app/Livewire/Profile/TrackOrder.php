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

        if (strtolower($this->order->status) === 'canceled') {
            session()->flash('error', 'Canceled orders cannot be tracked.');
            return redirect()->route('profile.orders');
        }
    }

    public function render()
    {
        return view('livewire.profile.track-order')->layout('components.profile-layout');
    }
}
