<x-mail::message>

# Payment Failed ⚠️

Hi **{{ $order->customer->name ?? 'Customer' }}**,

Unfortunately, the payment for your recent order attempt (**#{{ $order->order_number }}**) could not be processed successfully, and your order is not confirmed.

If you have been charged, a refund will be automatically processed and will be available to you in the next 3–5 business days.

### Cart Details

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

If you would like to try placing your order again, your cart is still saved!

<x-mail::button :url="route('cart')" color="primary">
Return to Cart
</x-mail::button>

If you continue to experience issues, please contact our support team.

Warm regards,<br>
**The ALPHA DIGITAL SAREES Team**
</x-mail::message>
