<!DOCTYPE html>
<html lang="en">
<head>
    <title>ALPHA DIGITAL</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>

    <!-- Navbar -->
    <x-navbar />

    <!-- Page Content -->
    {{ $slot }}

    <!-- Footer -->
    <x-footer />

    <script>
        lucide.createIcons();
    </script>

</body>
</html>