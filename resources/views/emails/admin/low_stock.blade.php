<x-mail::message>
@if($product->stock == 0)
# Out of Stock Alert

The following product is completely out of stock and unavailable for purchase!
@else
# Low Stock Alert

The following product is running low on stock and needs to be replenished.
@endif

**Product Details:**
- Name: {{ $product->name }}
- Current Stock: **{{ $product->stock }}**

Please update the inventory in the admin panel as soon as possible.

<x-mail::button :url="url('/admin/products/' . $product->id . '/edit')">
Update Stock
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
