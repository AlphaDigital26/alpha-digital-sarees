<?php

namespace App\Livewire\Profile;

use Livewire\Component;

use App\Models\Order;

class OrderHistory extends Component
{
    public function render()
    {
        $orders = Order::with('items.product')
            ->where('customer_id', auth('customer')->id())
            ->where(function($query) {
                $query->whereNotIn('payment_status', ['pending', 'failed'])
                      ->orWhereNull('payment_status');
            })
            ->latest()
            ->get();

        return view('livewire.profile.order-history', compact('orders'))
            ->layout('components.profile-layout');
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('customer_id', auth('customer')->id())
            ->first();

        if (!$order) {
            $this->dispatch('toast', msg: 'Order not found.', type: 'error');
            return;
        }

        if (now()->diffInHours($order->created_at) > 24) {
            $this->dispatch('toast', msg: 'Orders can only be canceled within 24 hours of placement.', type: 'error');
            return;
        }

        if (in_array(strtolower($order->status), ['shipped', 'delivered', 'refunded', 'canceled'])) {
            $this->dispatch('toast', msg: 'This order cannot be canceled at this stage.', type: 'error');
            return;
        }

        $order->update(['status' => 'canceled']);

        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $this->dispatch('toast', msg: 'Order #' . $order->order_number . ' canceled successfully.', type: 'success');
    }
}
