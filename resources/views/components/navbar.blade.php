<header class="navbar">
    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-2xl font-bold tracking-widest">
        @if($settings && $settings->logo_type === 'image' && $settings->logo_image)
        <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Logo" class="h-12 w-auto object-contain">
        @else
        {{ $settings->logo_text ?? 'ALPHA DIGITAL' }}
        @endif
    </a>

    <nav class="main-nav">
        <a href="{{ route('home') }}" wire:navigate>HOME</a>
        <a href="{{ route('shop.index') }}" wire:navigate>ALL SAREES</a>

        <a href="{{ route('shop.new-arrival') }}" wire:navigate>NEW ARRIVAL</a>

        <a href="{{ route('shop.occasion') }}" wire:navigate>OCCASION</a>

        <a href="{{ route('shop.about') }}" wire:navigate>OUR STORY</a>
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
            
            <a href="{{ route('cart') }}" wire:navigate class="{{ request()->routeIs('cart') ? 'active' : '' }}" title="Cart">
                <i data-lucide="shopping-bag"></i>
            </a>
            
            <a href="#" title="Profile">
                <i data-lucide="user"></i>
            </a>
        </div>
    </div>
</header>