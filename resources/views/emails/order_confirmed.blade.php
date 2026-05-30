<x-mail::message>
# Order Confirmed

Thank you for your purchase, {{ $order->customer->name }}!

Your order **{{ $order->order_number }}** has been successfully confirmed. 
We are currently processing it and will notify you once it ships.

**Order Total:** ₹{{ number_format($order->total_amount, 2) }}

<x-mail::button :url="route('profile.orders')">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
