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

    <div 
        x-data="{ show: false, message: '' }"
        @cart-updated.window="show = true; message = 'Item added to cart'; setTimeout(() => show = false, 3000)"
        @show-toast.window="show = true; message = $event.detail?.message || $event.detail?.[0]?.message || $event.detail || 'Success!'; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-4"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-4"
        class="fixed top-24 right-8 z-[9999] bg-[#800020] text-white px-6 py-3 rounded-md shadow-2xl flex items-center gap-3 font-sans text-sm tracking-wide"
        style="display: none;"
    >
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        <span x-text="message"></span>
    </div>

    @livewireScripts
    <script>
        // Render the icons on the first page load
        lucide.createIcons();

        // Re-render the icons whenever you navigate to a new page using wire:navigate
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>