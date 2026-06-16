<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle ?? $title ?? 'ALPHA DIGITAL | The Heirloom Collection' }}</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Alpha Digital",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo.png') }}",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+91-9876543210",
        "contactType": "customer service"
      }
    }
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <script src="https://unpkg.com/lucide@latest"></script>

    @php
        $settings = \App\Models\Setting::getSiteSettings();
    @endphp
    @if($settings && $settings->favicon_image)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $settings->favicon_image) }}?v={{ time() }}">
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings->favicon_image) }}?v={{ time() }}">
    @endif
</head>
<body>

    @php
        $settings = \App\Models\Setting::getSiteSettings();
    @endphp
    
    <x-navbar :settings="$settings" />

    <main>
        {{ $slot }}
    </main>

    <x-footer :settings="$settings" />

    <livewire:auth.login-popup />

    <x-toast-notification />

    @livewireScripts
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Render the icons on the first page load
        lucide.createIcons();
        AOS.init({
            duration: 800,
            once: false,
            offset: 100,
            easing: 'ease-out-cubic'
        });

        // Re-render the icons whenever you navigate to a new page using wire:navigate
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
            AOS.init({
                duration: 800,
                once: false,
                offset: 100,
                easing: 'ease-out-cubic'
            });
        });
    </script>

    <livewire:auth.login-popup />
</body>
</html>