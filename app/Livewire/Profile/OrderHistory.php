<?php

namespace App\Livewire\Profile;

use Livewire\Component;

use App\Models\Order;

class OrderHistory extends Component
{
    public function render()
    {
        $orders = Order::where('customer_id', auth('customer')->id())
            ->where(function($query) {
                $query->whereNotIn('payment_status', ['pending', 'failed'])
                      ->orWhereNull('payment_status');
            })
            ->latest()
            ->get();

        return view('livewire.profile.order-history', compact('orders'))
            ->layout('components.profile-layout');
    }
}
