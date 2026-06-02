<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
    protected static string $view = 'filament.resources.order-resource.pages.view-order';

    public $shippingModalOpen = false;
    public $courier_partner = '';
    public $tracking_number = '';
    public $expected_delivery_date = '';
    
    public $new_note = '';
    public $editingNoteId = null;
    public $editNoteContent = '';

    public $cancelModalOpen = false;
    public $cancellation_reason = '';
    public $custom_cancellation_reason = '';

    // ADDED: Reject Refund Modal Properties
    public $rejectRefundModalOpen = false;
    public $refund_rejection_reason = '';

    protected function getHeaderActions(): array { return []; }

    public function openShippingModal()
    {
        $this->courier_partner = $this->record->courier_partner;
        $this->tracking_number = $this->record->tracking_number;
        $this->expected_delivery_date = $this->record->expected_delivery_date ? \Carbon\Carbon::parse($this->record->expected_delivery_date)->format('Y-m-d') : '';
        $this->shippingModalOpen = true;
    }

    public function saveShippingDetails()
    {
        $this->validate([
            'courier_partner' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'expected_delivery_date' => 'nullable|date',
        ]);
        $this->record->update([
            'courier_partner' => $this->courier_partner,
            'tracking_number' => $this->tracking_number,
            'expected_delivery_date' => $this->expected_delivery_date,
        ]);
        $this->shippingModalOpen = false;
        Notification::make()->title('Shipment Details Saved')->success()->send();
    }

    public function openCancelModal()
    {
        $this->cancellation_reason = '';
        $this->custom_cancellation_reason = '';
        $this->cancelModalOpen = true;
    }

    public function confirmCancellation()
    {
        $this->validate([
            'cancellation_reason' => 'required|string',
            'custom_cancellation_reason' => 'required_if:cancellation_reason,Other|max:1000',
        ]);

        $finalReason = $this->cancellation_reason === 'Other' ? $this->custom_cancellation_reason : $this->cancellation_reason;
        $refundRequired = (strtolower($this->record->payment_status) === 'paid');

        $this->record->update([
            'status' => 'cancelled',
            'cancellation_reason' => $finalReason,
            'cancelled_by' => auth()->id(),
            'cancelled_by_role' => 'admin',
            'cancelled_at' => now(),
            'refund_required' => $refundRequired,
            'stock_restored' => true,
            'stock_restored_at' => now(),
            'stock_restored_by' => auth()->id(),
        ]);

        if (!$this->record->getOriginal('stock_restored')) {
            foreach ($this->record->items as $item) {
                if ($item->product) $item->product->increment('stock', $item->quantity);
            }
        }

        $this->cancelModalOpen = false;
        Notification::make()->title('Order Cancelled Successfully')->success()->send();
    }

    public function updateOrderStatus($newStatus)
    {
        $updateData = ['status' => $newStatus];
        if ($newStatus === 'shipped') $updateData['shipping_date'] = now();
        if ($newStatus === 'delivered') $updateData['delivered_at'] = now();
        
        $this->record->update($updateData);
        Notification::make()->title('Order status updated')->success()->send();
    }

    // --- REFUND MANAGEMENT ACTIONS ---
    public function openRejectRefundModal()
    {
        $this->refund_rejection_reason = '';
        $this->rejectRefundModalOpen = true;
    }

    public function confirmRejectRefund()
    {
        $this->validate(['refund_rejection_reason' => 'required|string|max:1000']);
        if (strtolower($this->record->status) !== 'refund_requested') return;

        $this->record->update([
            'status' => 'refund_rejected',
            'refund_rejected_by' => auth()->id(),
            'refund_rejected_at' => now(),
            'refund_rejection_reason' => $this->refund_rejection_reason,
        ]);

        $this->rejectRefundModalOpen = false;
        Notification::make()->title('Refund Rejected')->success()->send();
    }

    public function approveRefund()
    {
        if (strtolower($this->record->status) !== 'refund_requested') return;
        
        $this->record->update([
            'status' => 'refund_approved',
            'refund_approved_by' => auth()->id(),
            'refund_approved_at' => now(),
            'refund_required' => true,
        ]);
        Notification::make()->title('Refund Approved')->success()->send();
    }

    public function processRefund()
    {
        if (!in_array(strtolower($this->record->status), ['refund_approved', 'cancelled', 'canceled'])) return;

        $this->record->update([
            'status' => 'refunded',
            'payment_status' => 'refunded',
            'refund_required' => false,
            'refund_processed_by' => auth()->id(),
            'refund_processed_at' => now(),
        ]);
        Notification::make()->title('Refund Processed Successfully')->success()->send();
    }

    public function verifyReturnAndRestoreInventory()
    {
        if ($this->record->stock_restored) {
            Notification::make()->title('Stock already restored')->warning()->send();
            return;
        }

        foreach ($this->record->items as $item) {
            if ($item->product) $item->product->increment('stock', $item->quantity);
        }

        $this->record->update([
            'stock_restored' => true,
            'stock_restored_at' => now(),
            'stock_restored_by' => auth()->id(),
            'return_received_at' => now(),
            'return_verified_by' => auth()->id(),
        ]);
        
        Notification::make()->title('Return verified and inventory restored')->success()->send();
    }

    // --- ADMIN NOTES ---
    public function addNote()
    {
        $this->validate(['new_note' => 'required|string|max:1500']);
        $this->record->notes()->create(['user_id' => auth()->id(), 'note' => $this->new_note]);
        $this->new_note = ''; 
        $this->record->refresh(); 
        Notification::make()->title('Note added')->success()->send();
    }
    public function startEditingNote($noteId)
    {
        $note = $this->record->notes()->find($noteId);
        if ($note) { $this->editingNoteId = $noteId; $this->editNoteContent = $note->note; }
    }
    public function cancelEditingNote() { $this->editingNoteId = null; $this->editNoteContent = ''; }
    public function saveEditedNote()
    {
        $this->validate(['editNoteContent' => 'required|string|max:1500']);
        $note = $this->record->notes()->find($this->editingNoteId);
        if ($note) $note->update(['note' => $this->editNoteContent]);
        $this->cancelEditingNote();
        $this->record->refresh();
        Notification::make()->title('Note updated')->success()->send();
    }
    public function deleteNote($noteId)
    {
        $note = $this->record->notes()->find($noteId);
        if ($note) $note->delete();
        $this->record->refresh();
        Notification::make()->title('Note deleted')->success()->send();
    }
}