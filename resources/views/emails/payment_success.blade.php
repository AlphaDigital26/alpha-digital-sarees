<x-mail::message>
# Payment Successful

Hi {{ $order->customer->name }},

We have successfully received your payment for order **{{ $order->order_number }}**.

**Amount Paid:** ₹{{ number_format($order->total_amount, 2) }}
**Transaction ID:** {{ $order->razorpay_payment_id }}

<x-mail::button :url="route('profile.orders')">
View Your Orders
</x-mail::button>

Thanks for shopping with us,<br>
{{ config('app.name') }}
</x-mail::message>
