<x-mail::message>

@if($product->stock == 0)
# Out of Stock Alert 🚨

Hi **Admin**,

The following product is completely out of stock and unavailable for purchase!
@else
# Low Stock Alert ⚠️

Hi **Admin**,

The following product is running low on stock and needs to be replenished.
@endif

### Product Details
**Name:** {{ $product->name }}<br>
**Current Stock:** **{{ $product->stock }}**

Please update the inventory in the admin panel as soon as possible.

<x-mail::button :url="url('/admin/products/' . $product->id . '/edit')" color="primary">
Update Stock
</x-mail::button>

Warm regards,<br>
**The ALPHA DIGITAL SREES System**
</x-mail::message>
