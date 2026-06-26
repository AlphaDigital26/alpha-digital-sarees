<x-mail::message>

# Great news! Your order is on the way 🚚

Hi **{{ $order->customer->name ?? 'Customer' }}**,

Your order **#{{ $order->order_number }}** has just shipped and is on its way to you! 

@if($order->expected_delivery_date)
**Estimated Delivery Date:** {{ \Carbon\Carbon::parse($order->expected_delivery_date)->format('M d, Y') }}<br>
@endif
@if($order->tracking_number && $order->courier_partner)
**Courier:** {{ $order->courier_partner }}<br>
**Tracking Number:** {{ $order->tracking_number }}
@elseif($order->tracking_number)
**Tracking Number:** {{ $order->tracking_number }}
@endif

You can track the progress of your shipment by clicking the button below.

<x-mail::button :url="route('profile.orders.track', $order->id)" color="primary">
Track Your Order
</x-mail::button>

Warm regards,<br>
**The ALPHA DIGITAL SREES Team**
</x-mail::message>
