    <meta name="description" content="{{ $metaDescription ?? 'Discover premium, authentic sarees at Alpha Digital. Browse our latest arrivals and experience the perfect blend of tradition and modern elegance.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'sarees, traditional sarees, online saree shopping, alpha digital, Indian ethnic wear' }}">
    <meta name="author" content="Alpha Digital">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $metaTitle ?? $title ?? 'Alpha Digital Saree | Shop the Best Adsarees Online' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Discover premium, authentic sarees at Alpha Digital. Browse our latest arrivals and experience the perfect blend of tradition and modern elegance.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('images/og-default.jpg') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="{{ $metaTitle ?? $title ?? 'Alpha Digital Saree | Shop the Best Adsarees Online' }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? 'Discover premium, authentic sarees at Alpha Digital. Browse our latest arrivals and experience the perfect blend of tradition and modern elegance.' }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset('images/default-og.jpg') }}">
    
    @if(isset($canonicalUrl) && $canonicalUrl)
        <link rel="canonical" href="{{ $canonicalUrl }}" />
    @else
        <link rel="canonical" href="{{ request()->url() }}" />
    @endif
