<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id', 'total_amount', 'status', 'razorpay_order_id', 'razorpay_payment_id', 'payment_status',
        'courier_partner', 'tracking_number', 'shipping_date', 'expected_delivery_date', 'delivered_at',
        'customer_note',
        'cancellation_reason', 'cancellation_evidence', 'cancelled_by', 'cancelled_at', 
        'cancelled_by_role', 'refund_required',
        // ADDED: Refund Management Fields
        'refund_status', 'refund_reason', 'refund_custom_reason', 'refund_evidence', 'refund_requested_at',
        'refund_approved_by', 'refund_approved_at', 'refund_rejected_by', 'refund_rejected_at', 'refund_rejection_reason',
        'refund_processed_by', 'refund_processed_at', 'stock_restored',
        'stock_restored_at', 'stock_restored_by', 'return_received_at', 'return_verified_by'
    ];

    // ADDED: Casts for JSON arrays and Booleans
    protected $casts = [
        'refund_evidence' => 'array',
        'stock_restored' => 'boolean',
        'refund_required' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(OrderNote::class)->latest();
    }

    // Relationship to the Admin user who cancelled
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    // ADDED: Relationships for Admins handling refunds
    public function refundApprovedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refund_approved_by');
    }

    public function refundRejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refund_rejected_by');
    }

    public function refundProcessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'refund_processed_by');
    }

    public function stockRestoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'stock_restored_by');
    }

    public function returnVerifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'return_verified_by');
    }

    protected static function booted(): void
    {
        static::updated(function (Order $order) {
            if ($order->isDirty('status')) {
                \App\Models\OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'previous_status' => $order->getOriginal('status'),
                    'new_status' => $order->status,
                ]);

                if (strtolower($order->status) === 'delivered') {
                    \Illuminate\Support\Facades\Mail::to($order->customer->email)->send(new \App\Mail\OrderDeliveredMail($order));
                } elseif (strtolower($order->status) === 'shipped') {
                    \Illuminate\Support\Facades\Mail::to($order->customer->email)->send(new \App\Mail\OrderShippedMail($order));
                }
            }
        });
    }
}