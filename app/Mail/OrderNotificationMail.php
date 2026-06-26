<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $type;

    /**
     * Supported types:
     * - admin_new_order
     * - confirmed
     * - shipped
     * - delivered
     * - failed
     * - payment_success
     */
    public function __construct(Order $order, string $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'admin_new_order' => 'New Order Received: #' . $this->order->order_number,
            'confirmed' => 'Order Confirmed: #' . $this->order->order_number,
            'shipped' => 'Your Order Has Shipped: #' . $this->order->order_number,
            'delivered' => 'Your Order Has Been Delivered: #' . $this->order->order_number,
            'failed' => 'Order Failed: #' . $this->order->order_number,
            'payment_success' => 'Payment Successful for Order: #' . $this->order->order_number,
            default => 'Update on Order #' . $this->order->order_number,
        };

        return new Envelope(
            from: new Address('orders@adsarees.com', 'Alpha Digital Sarees'),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        $view = match ($this->type) {
            'admin_new_order' => 'emails.admin.new_order',
            'confirmed' => 'emails.order_confirmed',
            'shipped' => 'emails.order_shipped',
            'delivered' => 'emails.order_delivered',
            'failed' => 'emails.order_failed',
            'payment_success' => 'emails.payment_success',
            default => 'emails.order_confirmed',
        };

        $items = $this->order->items ?? collect([]);
        $subtotal = collect($items)->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        
        $total = $this->order->total_amount ?? 0;
        $shipping = max(0, $total - $subtotal);

        $customer = $this->order->customer;
        $address = null;
        if ($customer && method_exists($customer, 'addresses')) {
            $address = $customer->addresses()->where('is_default', true)->first() 
                ?? $customer->addresses()->first();
        }

        return new Content(
            markdown: $view,
            with: [
                // Safely passing data to your blade templates
                'customerName' => $this->order->customer->name ?? $this->order->first_name ?? 'Customer',
                'orderNumber' => $this->order->order_number ?? $this->order->id,
                'orderDate' => $this->order->created_at ? $this->order->created_at->format('M d, Y') : now()->format('M d, Y'),
                'paymentMethod' => $this->order->payment_method ?? 'Standard Payment',
                'transactionId' => $this->order->razorpay_payment_id ?? null,
                'orderItems' => $items,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'total' => $total,
                'streetAddress' => $address->address_1 ?? '',
                'city' => $address->city ?? '',
                'state' => $address->province ?? '',
                'zipCode' => $address->postal_code ?? '',
                'supportEmail' => 'support@adsarees.com',
                'websiteUrl' => config('app.url', 'https://adsarees.com'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}