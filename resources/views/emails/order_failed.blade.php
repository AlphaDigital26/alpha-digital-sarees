<x-mail::message>
# Payment Failed

Hi {{ $order->customer->name }},

Unfortunately, the payment for your recent order attempt (**{{ $order->order_number }}**) could not be processed successfully. 
If you have been charged, a refund will be processed and will be available to you in the next 3–5 business days.

Here is what was in your cart:
@foreach($order->items as $item)
- **{{ $item->product->name ?? 'Product' }}** (Qty: {{ $item->quantity }})
@endforeach

If you would like to try placing your order again, your cart is still saved!

<x-mail::button :url="route('cart')">
Return to Cart
</x-mail::button>

If you continue to experience issues, please contact our support team.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
