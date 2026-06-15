<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithFileUploads;

class OrderDetails extends Component
{
    use WithFileUploads;

    public $order;
    public $cancelModalOpen = false;

    public $cancellation_reason = '';
    public $custom_cancellation_reason = '';

    public $refundModalOpen = false;
    public $refund_reason = '';
    public $refund_custom_reason = '';
    public $refund_evidence = [];

    // Review Modal State
    public $reviewModalOpen = false;
    public $isEditingReview = false;
    public $reviewProductId = null;
    public $reviewRating = 5;
    public $reviewComment = '';
    public $reviewPhotos = [];
    public $newPhotos = [];
    public $existingPhotos = [];
    public $uploadIteration = 0; // To clear the file input

    // View Review Modal State
    public $viewReviewModalOpen = false;
    public $viewingReview = null;

    public function mount($id)
    {
        $this->order = Order::where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->firstOrFail();
    }

    public function updatedNewPhotos()
    {
        $this->validate([
            'newPhotos.*' => 'image|max:5120',
        ]);

        foreach ($this->newPhotos as $photo) {
            $this->reviewPhotos[] = $photo;
        }

        // Reset so they can upload more without replacing
        $this->newPhotos = [];
        $this->uploadIteration++;
    }

    public function openViewReviewModal($productId)
    {
        $this->viewingReview = \App\Models\Review::where('customer_id', auth('customer')->id())
            ->where('product_id', $productId)
            ->first();

        if ($this->viewingReview) {
            $this->viewReviewModalOpen = true;
        }
    }

    public function openReviewModal($productId)
    {
        $this->reviewProductId = $productId;
        $this->reviewRating = 5;
        $this->reviewComment = '';
        $this->reviewPhotos = [];
        $this->newPhotos = [];
        $this->existingPhotos = [];
        $this->uploadIteration++;
        $this->isEditingReview = false;
        $this->reviewModalOpen = true;
    }

    public function editReview($productId)
    {
        $review = \App\Models\Review::where('customer_id', auth('customer')->id())
            ->where('product_id', $productId)
            ->first();

        if ($review) {
            $this->reviewProductId = $productId;
            $this->reviewRating = $review->rating;
            $this->reviewComment = $review->comment;
            $this->reviewPhotos = [];
            $this->newPhotos = [];
            $this->existingPhotos = is_array($review->photos) ? $review->photos : [];
            $this->uploadIteration++;
            $this->isEditingReview = true;
            
            $this->viewReviewModalOpen = false;
            $this->reviewModalOpen = true;
        }
    }

    public function goBackToViewReview()
    {
        $this->reviewModalOpen = false;
        if ($this->reviewProductId) {
            $this->openViewReviewModal($this->reviewProductId);
        }
    }

    public function removeExistingPhoto($index)
    {
        if (isset($this->existingPhotos[$index])) {
            unset($this->existingPhotos[$index]);
            $this->existingPhotos = array_values($this->existingPhotos);
        }
    }

    public function removeNewPhoto($index)
    {
        if (isset($this->reviewPhotos[$index])) {
            unset($this->reviewPhotos[$index]);
            $this->reviewPhotos = array_values($this->reviewPhotos);
        }
    }

    public function submitReview()
    {
        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewComment' => 'nullable|string|max:1000',
            'reviewProductId' => 'required|exists:products,id',
            'reviewPhotos.*' => 'nullable|image|max:5120', // 5MB max per image
        ]);

        $photoPaths = [];
        if (!empty($this->reviewPhotos)) {
            foreach ($this->reviewPhotos as $photo) {
                $photoPaths[] = $photo->store('reviews', 'public');
            }
        }

        $existing = \App\Models\Review::where('customer_id', auth('customer')->id())
            ->where('product_id', $this->reviewProductId)
            ->first();

        if ($existing) {
            $allPhotos = array_merge($this->existingPhotos, $photoPaths);

            $existing->update([
                'rating' => $this->reviewRating,
                'comment' => $this->reviewComment,
                'is_read' => false,
                'photos' => $allPhotos,
            ]);
            $this->dispatch('toast', msg: 'Review updated successfully!', type: 'success');
        } else {
            \App\Models\Review::create([
                'customer_id' => auth('customer')->id(),
                'product_id' => $this->reviewProductId,
                'rating' => $this->reviewRating,
                'comment' => $this->reviewComment,
                'is_read' => false,
                'photos' => empty($photoPaths) ? null : $photoPaths,
            ]);
            $this->dispatch('toast', msg: 'Thank you for your review!', type: 'success');
        }

        $this->reviewModalOpen = false;
        $this->reviewPhotos = [];
        
        // Refresh the viewing review if it was open
        if ($this->viewingReview && $this->viewingReview->product_id == $this->reviewProductId) {
            $this->viewingReview->refresh();
        }
    }

    public function render()
    {
        return view('livewire.profile.order-details')->layout('components.profile-layout');
    }

    public function openCancelModal()
    {
        $this->cancellation_reason = '';
        $this->custom_cancellation_reason = '';
        $this->cancelModalOpen = true;
    }

    public function openRefundModal()
    {
        $this->refund_reason = '';
        $this->refund_custom_reason = '';
        $this->refund_evidence = [];
        $this->refundModalOpen = true;
    }

    public function submitRefundRequest()
    {
        // 1. Validate status
        if (strtolower($this->order->status) !== 'delivered') {
            $this->dispatch('toast', msg: 'Refunds can only be requested after the order has been delivered.', type: 'error');
            return;
        }

        // 2. Validate 7-day window
        $deliveredAt = $this->order->delivered_at ? \Carbon\Carbon::parse($this->order->delivered_at) : $this->order->updated_at;
        if (now()->diffInDays($deliveredAt) > 7) {
            $this->dispatch('toast', msg: 'The 7-day refund window has expired for this order.', type: 'error');
            $this->refundModalOpen = false;
            return;
        }

        // 3. Validation Rules
        $this->validate([
            'refund_reason' => 'required|in:Damaged Product,Wrong Product Received,Product Defect,Quality Issue,Missing Item,Delivery Delay,Incorrect Product Description,Other',
            'refund_custom_reason' => 'required_if:refund_reason,Other|string|max:1000',
            'refund_evidence.*' => 'image|max:2048', // max 2MB per image
        ], [
            'refund_custom_reason.required_if' => 'Please provide a specific reason for your refund request.'
        ]);

        // 4. File uploads
        $evidencePaths = [];
        if (!empty($this->refund_evidence)) {
            foreach ($this->refund_evidence as $photo) {
                $evidencePaths[] = $photo->store('refund_evidence', 'public');
            }
        }

        // 5. Update Order
        $this->order->update([
            'status' => 'refund_requested',
            'refund_status' => 'pending', // Razorpay prep
            'refund_reason' => $this->refund_reason,
            'refund_custom_reason' => $this->refund_custom_reason,
            'refund_evidence' => $evidencePaths,
            'refund_requested_at' => now(),
        ]);

        $this->refundModalOpen = false;
        $this->dispatch('toast', msg: 'Refund request submitted successfully.', type: 'success');
        $this->order->refresh();
    }

    public function cancelOrder()
    {
        if (!in_array(strtolower($this->order->status), ['new', 'processing'])) {
            $this->dispatch('toast', msg: 'This order cannot be cancelled at this stage. Please contact support.', type: 'error');
            return;
        }

        $this->validate([
            'cancellation_reason' => 'required|string',
            'custom_cancellation_reason' => 'required_if:cancellation_reason,Other|string|max:255',
        ], [
            'custom_cancellation_reason.required_if' => 'Please provide a specific reason for cancellation.'
        ]);

        $finalReason = $this->cancellation_reason === 'Other' ? $this->custom_cancellation_reason : $this->cancellation_reason;

        $refundRequired = (strtolower($this->order->payment_status) === 'paid');
        $wasStockRestored = $this->order->stock_restored;

        $this->order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $finalReason,
            'cancellation_evidence' => null,
            'cancelled_by' => auth('customer')->id(),
            'cancelled_by_role' => 'customer',
            'cancelled_at' => now(),
            'refund_required' => $refundRequired,
            'stock_restored' => true,
            'stock_restored_at' => now(),
            'stock_restored_by' => null, // Customer action
        ]);

        if (!$wasStockRestored) {
            foreach ($this->order->items as $item) {
                if ($item->product) $item->product->increment('stock', $item->quantity);
            }
        }

        $this->cancelModalOpen = false;
        $this->dispatch('toast', msg: 'Order #' . $this->order->order_number . ' cancelled successfully.', type: 'success');
        $this->order->refresh();
    }
}
