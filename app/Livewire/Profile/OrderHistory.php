<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Order;

class OrderHistory extends Component
{
    use WithFileUploads;

    public $refundModalOpen = false;
    public $refundOrderId = null;
    public $refund_reason = '';
    public $refund_custom_reason = '';
    public $refund_evidence_files = [];

    public function render()
    {
        $orders = Order::with('items.product.color')
            ->where('customer_id', auth('customer')->id())
            ->where(function($query) {
                $query->whereNotIn('payment_status', ['pending'])
                      ->orWhereNull('payment_status');
            })
            ->latest()
            ->get();

        return view('livewire.profile.order-history', compact('orders'))
            ->layout('components.profile-layout');
    }

    public function cancelOrder($orderId)
    {
        $order = Order::with('items.product')->where('id', $orderId)
            ->where('customer_id', auth('customer')->id())
            ->first();

        if (!$order) {
            $this->dispatch('toast', msg: 'Order not found.', type: 'error');
            return;
        }

        if (!in_array(strtolower($order->status), ['new', 'processing'])) {
            $this->dispatch('toast', msg: 'This order cannot be cancelled at this stage. Please contact support.', type: 'error');
            return;
        }

        $refundRequired = (strtolower($order->payment_status) === 'paid');

        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => 'Customer Request',
            'cancelled_by_role' => 'customer',
            'cancelled_at' => now(),
            'refund_required' => $refundRequired,
            'stock_restored' => true,
        ]);

        foreach ($order->items as $item) {
            if ($item->product) $item->product->increment('stock', $item->quantity);
        }

        $this->dispatch('toast', msg: 'Order #' . $order->order_number . ' cancelled successfully.', type: 'success');
    }

    public function openRefundModal($orderId)
    {
        $this->reset(['refund_reason', 'refund_custom_reason', 'refund_evidence_files']);
        $this->refundOrderId = $orderId;
        $this->refundModalOpen = true;
    }

    public function submitRefundRequest()
    {
        $this->validate([
            'refund_reason' => 'required|string',
            'refund_custom_reason' => 'required_if:refund_reason,Other|max:1000',
            'refund_evidence_files.*' => 'image|max:5120', 
        ]);

        $order = Order::where('id', $this->refundOrderId)->where('customer_id', auth('customer')->id())->first();

        if (!$order || strtolower($order->status) !== 'delivered') {
            $this->dispatch('toast', msg: 'Refund request unavailable for this order.', type: 'error');
            return;
        }

        $deliveredDate = $order->delivered_at ?? $order->updated_at;
        if (now()->diffInDays($deliveredDate) > 7) {
            $this->dispatch('toast', msg: 'Refund window (7 days) has expired.', type: 'error');
            return;
        }

        $paths = [];
        if (!empty($this->refund_evidence_files)) {
            foreach ($this->refund_evidence_files as $file) {
                $paths[] = $file->store('refund_evidence', 'public');
            }
        }

        $order->update([
            'status' => 'refund_requested',
            'refund_reason' => $this->refund_reason === 'Other' ? $this->refund_custom_reason : $this->refund_reason,
            'refund_evidence' => $paths,
            'refund_requested_at' => now(),
        ]);

        $this->refundModalOpen = false;
        $this->dispatch('toast', msg: 'Refund request submitted successfully.', type: 'success');
    }
}