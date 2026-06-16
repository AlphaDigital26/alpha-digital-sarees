@props(['type', 'data' => null])

@if($type === 'product' && $data)
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $data->name }}",
  "image": [
    @if(is_array($data->images))
        @foreach($data->images as $img)
            "{{ asset('storage/' . $img) }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
    @endif
  ],
  "description": "{{ strip_tags($data->description) }}",
  "sku": "{{ $data->id }}",
  "offers": {
    "@type": "Offer",
    "url": "{{ request()->url() }}",
    "priceCurrency": "INR",
    "price": "{{ $data->current_price }}",
    "availability": "{{ $data->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
</script>
@elseif($type === 'organization')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Alpha Digital",
  "url": "{{ url('/') }}",
  "logo": "{{ asset('images/logo.png') }}",
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+91-1234567890",
    "contactType": "customer service"
  }
}
</script>
@elseif($type === 'category' && $data)
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "{{ $data->name }} Collection",
  "description": "Browse our exclusive {{ strtolower($data->name) }} sarees.",
  "url": "{{ request()->url() }}"
}
</script>
@endif
