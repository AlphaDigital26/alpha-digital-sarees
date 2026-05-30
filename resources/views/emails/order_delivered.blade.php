<x-mail::message>
# Order Delivered! 🎉

Hi {{ $order->customer->name }},

Great news! Your order **#{{ $order->order_number }}** has been successfully delivered. We hope you love your new purchase!

Here is what was delivered:
@foreach($order->items as $item)
- **{{ $item->product->name ?? 'Product' }}** (Qty: {{ $item->quantity }})
@endforeach

<x-mail::button :url="route('profile.orders')">
View Order History
</x-mail::button>

If you have a moment, we would love to hear your thoughts. You can rate and review your products directly from your order history.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
