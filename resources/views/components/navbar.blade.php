<header class="navbar">
    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-2xl font-bold tracking-widest text-[#800020]">
        @if($settings && $settings->logo_type === 'image' && $settings->logo_image)
            <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Alpha Digital Logo" class="h-12 w-auto object-contain">
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
            <input type="text" placeholder="Search Alpha Digital">
        </div>

        <div class="nav-icons flex items-center gap-5">
            @php
                $wishlistCount = count(session()->get('wishlist', []));
                $cartCount = array_sum(session()->get('cart', []));
            @endphp

            <a href="{{ route('wishlist') }}" wire:navigate class="relative {{ request()->routeIs('wishlist') ? 'active' : '' }}" title="Wishlist">
                <i data-lucide="heart" class="{{ request()->routeIs('wishlist') ? 'fill-[#800020] text-[#800020]' : '' }}"></i>
                @if($wishlistCount > 0)
                    <span class="absolute -top-2 -right-2 bg-[#800020] text-white text-[10px] font-bold h-[18px] w-[18px] flex items-center justify-center rounded-full border border-white" style="line-height: 1;">
                        {{ $wishlistCount }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('cart') }}" wire:navigate class="relative {{ request()->routeIs('cart') ? 'active' : '' }}" title="Cart">
                <i data-lucide="shopping-bag"></i>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-[#800020] text-white text-[10px] font-bold h-[18px] w-[18px] flex items-center justify-center rounded-full border border-white" style="line-height: 1;">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            
            @auth('customer')
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" title="My Account" class="bg-transparent border-none cursor-pointer p-0 m-0 outline-none">
                        <i data-lucide="user-check" class="text-[#800020]"></i>
                    </button>

                    <div x-show="open" x-cloak style="display: none;" class="absolute right-0 mt-6 w-56 bg-white border border-[#E5E0DA] shadow-xl rounded-sm py-2 z-50 text-left">
                        <div class="px-5 py-3 border-b border-[#E5E0DA] mb-2 bg-[#fbf9f5]">
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-widest" style="font-family: 'Manrope', sans-serif;">Welcome,</p>
                            <p class="text-base font-bold text-[#1b1c1a] truncate mt-1" style="font-family: 'Noto Serif', serif;">
                                {{ strtoupper(auth('customer')->user()->name ?? 'USER') }}!
                            </p>
                        </div>
                        <a href="#" class="flex items-center px-5 py-3 text-sm font-medium text-[#706663] hover:bg-[#F4F0EB] hover:text-[#800020] transition-colors gap-3" style="font-family: 'Manrope', sans-serif;">
                            <i data-lucide="user" style="width: 16px; height: 16px;"></i> Account Details
                        </a>
                        <a href="#" class="flex items-center px-5 py-3 text-sm font-medium text-[#706663] hover:bg-[#F4F0EB] hover:text-[#800020] transition-colors gap-3" style="font-family: 'Manrope', sans-serif;">
                            <i data-lucide="package" style="width: 16px; height: 16px;"></i> Order History
                        </a>
                        <div class="border-t border-[#E5E0DA] mt-2 pt-2">
                            <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-5 py-3 text-sm font-bold text-[#800020] hover:bg-[#F4F0EB] transition-colors gap-3 bg-transparent border-none cursor-pointer text-left outline-none" style="font-family: 'Manrope', sans-serif;">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <button x-data @click="$dispatch('open-login-modal')" title="Login / Signup" class="bg-transparent border-none cursor-pointer p-0 m-0 outline-none hover:text-[#800020] transition-colors">
                    <i data-lucide="user"></i>
                </button>
            @endauth
        </div>
    </div>
</header>