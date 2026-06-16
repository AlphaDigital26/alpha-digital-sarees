    <meta name="description" content="{{ $metaDescription ?? 'Discover premium handcrafted heirloom sarees at Alpha Digital.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'saree, handloom, premium sarees, heirloom sarees, ethnic wear' }}">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $metaTitle ?? $title ?? 'ALPHA DIGITAL | The Heirloom Collection' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Discover premium handcrafted heirloom sarees at Alpha Digital.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/default-og.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ $metaTitle ?? $title ?? 'ALPHA DIGITAL | The Heirloom Collection' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Discover premium handcrafted heirloom sarees at Alpha Digital.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/default-og.jpg') }}">
    
    @if(isset($canonicalUrl) && $canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}" />
    @else
        <link rel="canonical" href="{{ request()->url() }}" />
    @endif
