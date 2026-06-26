<x-mail::message>

# New Order Received! 🛍️

Hi **Admin**,

A new order has been successfully placed on the store.

**Order Number:** #{{ $order->order_number }}<br>
**Order Amount:** ₹{{ number_format($order->total_amount, 2) }}

### Customer Details
**Name:** {{ $order->customer->name ?? 'Guest' }}<br>
**Email:** {{ $order->customer->email ?? 'N/A' }}

### Order Details

<x-mail::table>
| Item | Quantity | Price |
| :--- | :--- | :--- |
@foreach($orderItems as $item)
| {{ $item->product->name ?? 'Product' }} | {{ $item->quantity }} | ₹{{ number_format($item->price, 2) }} |
@endforeach
| | **Subtotal:** | ₹{{ number_format($subtotal, 2) }} |
| | **Shipping:** | ₹{{ number_format($shipping, 2) }} |
| | **Total:** | **₹{{ number_format($total, 2) }}** |
</x-mail::table>

<x-mail::button :url="url('/admin/orders')" color="primary">
View Order in Admin Panel
</x-mail::button>

Warm regards,<br>
**The ALPHA DIGITAL SREES System**
</x-mail::message>
