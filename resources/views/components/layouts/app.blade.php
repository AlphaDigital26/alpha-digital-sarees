<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ALMAARI | The Heirloom Collection' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- This loads your CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- This calls your Navbar component -->
    <x-navbar />

    <!-- This is where the specific page content (like the homepage) will be injected -->
    <main>
        {{ $slot }}
    </main>

    <!-- This calls your Footer component -->
    <x-footer />

    <script>
        // Render the icons
        lucide.createIcons();
    </script>
</body>
</html>