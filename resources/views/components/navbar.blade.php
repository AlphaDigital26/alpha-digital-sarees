<header class="navbar">
    <!-- Updated Logo -->
    <a href="/" class="flex items-center gap-2 text-2xl font-bold tracking-widest">
        @if($settings && $settings->logo_type === 'image' && $settings->logo_image)
        <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Logo" class="h-12 w-auto object-contain">
        @else
        {{ $settings->logo_text ?? 'ALMAARI' }}
        @endif
    </a>

    <nav class="main-nav">
        <a href="{{ route('home') }}">HOME</a>
        <a href="{{ route('shop.index') }}">ALL SAREES</a>

        <!-- Updated New Arrival Link -->
        <a href="{{ route('shop.new-arrival') }}">NEW ARRIVAL</a>

        <!-- Updated Occasion Link -->
        <a href="{{ route('shop.occasion') }}">OCCASION</a>

        <!-- Updated Our Story Link -->
        <a href="{{ route('shop.about') }}">OUR STORY</a>
    </nav>

    <div class="nav-actions">
        <div class="search-bar">
            <i data-lucide="search" class="icon-search"></i>
            <input type="text" placeholder="Search">
        </div>

        <div class="nav-icons">
            <a href="#" title="Wishlist">
                <i data-lucide="heart"></i>
            </a>
            <a href="#" title="Cart">
                <i data-lucide="shopping-bag"></i>
            </a>
            <a href="#" title="Profile">
                <i data-lucide="user"></i>
            </a>
        </div>
    </div>
</header>