<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $metaTitle ?? $title ?? 'Alpha Digital Saree | Shop the Best Adsarees Online' }}</title>

    <x-seo.meta 
        :metaTitle="$metaTitle ?? null"
        :title="$title ?? null"
        :metaDescription="$metaDescription ?? null"
        :metaKeywords="$metaKeywords ?? null"
        :ogType="$ogType ?? null"
        :ogImage="$ogImage ?? null"
        :canonicalUrl="$canonicalUrl ?? url()->current()" 
    />

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <x-seo.schema type="organization" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192x192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon-192x192.png') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">

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