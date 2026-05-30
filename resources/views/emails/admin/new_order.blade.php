<x-mail::message>
# New Order Received: #{{ $order->order_number }}

A new order has been placed on the store.

**Customer Details:**
- Name: {{ $order->customer->name ?? 'Guest' }}
- Email: {{ $order->customer->email ?? 'N/A' }}

**Order Amount:** Rs. {{ number_format($order->total_amount) }}

**Items:**
@foreach($order->items as $item)
- {{ $item->product->name ?? 'Product' }} (Qty: {{ $item->quantity }})
@endforeach

<x-mail::button :url="url('/admin/orders')">
View Order in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
