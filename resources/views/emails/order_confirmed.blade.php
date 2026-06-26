<x-mail::message>

# Order Confirmed! ✨

Hi **{{ $customerName }}**,

Thank you for your purchase! We have successfully received your payment, and your order is now confirmed. We are getting everything ready for you.

**Order Number:** #{{ $orderNumber }}<br>
**Order Date:** {{ $orderDate }}<br>
**Payment Method:** {{ $paymentMethod }}

### Order Details

<x-mail::table>
| Item | Quantity | Price |
| :--- | :--- | :--- |
@foreach($orderItems as $item)
| {{ $item->name }} | {{ $item->quantity }} | ₹{{ number_format($item->price, 2) }} |
@endforeach
| | **Subtotal:** | ₹{{ number_format($subtotal, 2) }} |
| | **Shipping:** | ₹{{ number_format($shipping, 2) }} |
| | **Total:** | **₹{{ number_format($total, 2) }}** |
</x-mail::table>

### Shipping Address
**{{ $customerName }}**<br>
{{ $streetAddress }}<br>
{{ $city }}, {{ $state }} {{ $zipCode }}

We will send you another email with tracking information as soon as your order ships. If you have any questions or need to make changes, please contact us at {{ $supportEmail }} within the next 24 hours.

<x-mail::button :url="$websiteUrl" color="primary">
Visit Our Website
</x-mail::button>

Warm regards,<br>
**The ALPHA DIGITAL SREES Team**
</x-mail::message>