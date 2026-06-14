<x-mail::message>
# Great news! Your order is on the way 🚚

Hi {{ $order->customer->name }},

Your order **#{{ $order->order_number }}** has just shipped! 

@if($order->expected_delivery_date)
**Estimated Delivery Date:** {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}
@endif

@if($order->tracking_number && $order->courier_partner)
**Shipping Details:**
- Courier: {{ $order->courier_partner }}
- Tracking Number: {{ $order->tracking_number }}
@elseif($order->tracking_number)
**Tracking Number:** {{ $order->tracking_number }}
@endif

<x-mail::button :url="route('profile.orders.track', $order->id)">
Track Your Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
