<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'customer_id', 'total_amount', 'status', 'razorpay_order_id', 'razorpay_payment_id', 'payment_status'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted(): void
    {
        static::updated(function (Order $order) {
            if ($order->isDirty('status') && strtolower($order->status) === 'delivered') {
                \Illuminate\Support\Facades\Mail::to($order->customer->email)->send(new \App\Mail\OrderDeliveredMail($order));
            }
        });
    }
}