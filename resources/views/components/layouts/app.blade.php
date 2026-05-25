<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ALPHA DIGITAL | The Heirloom Collection' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <script src="https://unpkg.com/lucide@latest"></script>
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

    @livewireScripts
    <script>
        // Render the icons on the first page load
        lucide.createIcons();

        // Re-render the icons whenever you navigate to a new page using wire:navigate
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>

    <livewire:auth.login-popup />
</body>
</html>