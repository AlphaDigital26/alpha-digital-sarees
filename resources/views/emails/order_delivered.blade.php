<x-mail::message>

# Order Delivered! 🎉

Hi **{{ $order->customer->name ?? 'Customer' }}**,

Great news! Your order **#{{ $order->order_number }}** has been successfully delivered. We hope you love your new purchase!

### Delivered Items

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

If you have a moment, we would love to hear your thoughts. You can rate and review your products directly from your order history.

<x-mail::button :url="route('profile.orders')" color="primary">
View Order History
</x-mail::button>

Warm regards,<br>
**The ALPHA DIGITAL SAREES Team**
</x-mail::message>
