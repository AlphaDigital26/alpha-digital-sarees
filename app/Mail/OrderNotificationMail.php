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
            'confirmed' => 'emails.orders.confirmed',
            'shipped' => 'emails.orders.shipped',
            'delivered' => 'emails.orders.delivered',
            'failed' => 'emails.orders.failed',
            'payment_success' => 'emails.orders.payment_success',
            default => 'emails.orders.default',
        };

        return new Content(
            view: $view,
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
