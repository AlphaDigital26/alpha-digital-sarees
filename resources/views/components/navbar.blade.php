<header class="fixed top-0 left-0 w-full h-20 flex justify-between items-center px-[5%] bg-[#F4F0EB]/90 backdrop-blur-md border-b border-[#E5E0DA] z-[1000]">
    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-2xl font-bold tracking-widest text-primary logo">
        @if($settings && $settings->logo_type === 'image' && $settings->logo_image)
            <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Alpha Digital Logo" class="h-12 w-auto object-contain">
        @else
            {{ $settings->logo_text ?? 'ALPHA DIGITAL' }}
        @endif
    </a>

    <nav class="hidden lg:flex gap-8">
        <a href="{{ route('home') }}" wire:navigate class="relative text-[#2A211F] font-sans text-xs font-semibold tracking-[1.2px] transition-colors duration-300 hover:text-primary pb-1 group {{ request()->routeIs('home') ? 'text-primary' : '' }}">
            HOME
            <span class="absolute left-0 bottom-0 w-full h-[1px] bg-[#800020] transition-transform duration-300 origin-left {{ request()->routeIs('home') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
        </a>
        <a href="{{ route('shop.index') }}" wire:navigate class="relative text-[#2A211F] font-sans text-xs font-semibold tracking-[1.2px] transition-colors duration-300 hover:text-primary pb-1 group {{ request()->routeIs('shop.index') ? 'text-primary' : '' }}">
            ALL SAREES
            <span class="absolute left-0 bottom-0 w-full h-[1px] bg-[#800020] transition-transform duration-300 origin-left {{ request()->routeIs('shop.index') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
        </a>
        <a href="{{ route('shop.new-arrival') }}" wire:navigate class="relative text-[#2A211F] font-sans text-xs font-semibold tracking-[1.2px] transition-colors duration-300 hover:text-primary pb-1 group {{ request()->routeIs('shop.new-arrival') ? 'text-primary' : '' }}">
            NEW ARRIVAL
            <span class="absolute left-0 bottom-0 w-full h-[1px] bg-[#800020] transition-transform duration-300 origin-left {{ request()->routeIs('shop.new-arrival') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
        </a>
        <a href="{{ route('shop.occasion') }}" wire:navigate class="relative text-[#2A211F] font-sans text-xs font-semibold tracking-[1.2px] transition-colors duration-300 hover:text-primary pb-1 group {{ request()->routeIs('shop.occasion') ? 'text-primary' : '' }}">
            OCCASION
            <span class="absolute left-0 bottom-0 w-full h-[1px] bg-[#800020] transition-transform duration-300 origin-left {{ request()->routeIs('shop.occasion') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
        </a>
        <a href="{{ route('shop.about') }}" wire:navigate class="relative text-[#2A211F] font-sans text-xs font-semibold tracking-[1.2px] transition-colors duration-300 hover:text-primary pb-1 group {{ request()->routeIs('shop.about') ? 'text-primary' : '' }}">
            OUR STORY
            <span class="absolute left-0 bottom-0 w-full h-[1px] bg-[#800020] transition-transform duration-300 origin-left {{ request()->routeIs('shop.about') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}"></span>
        </a>
    </nav>

    <div class="flex items-center gap-8">
        <form action="{{ route('shop.index') }}" method="GET" class="hidden lg:flex items-center bg-[#F4F0EB] py-1.5 px-4 border border-[#E5E0DA] rounded">
            <button type="submit" class="bg-transparent border-none p-0 cursor-pointer outline-none flex items-center justify-center">
                <i data-lucide="search" class="w-4 h-4 text-[#706663]"></i>
            </button>
            <input type="text" name="search" placeholder="Search Alpha Digital" value="{{ request('search') }}" class="border-none bg-transparent outline-none pl-2.5 font-sans text-xs text-[#2A211F] w-[160px]">
        </form>

        <div class="flex items-center gap-5 nav-icons">
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
                        <a href="{{ route('profile.account') }}" class="flex items-center px-5 py-3 text-sm font-medium text-[#706663] hover:bg-[#F4F0EB] hover:text-[#800020] transition-colors gap-3" style="font-family: 'Manrope', sans-serif;">
                            <i data-lucide="user" style="width: 16px; height: 16px;"></i> My Profile
                        </a>
                        <a href="{{ route('profile.orders') }}" class="flex items-center px-5 py-3 text-sm font-medium text-[#706663] hover:bg-[#F4F0EB] hover:text-[#800020] transition-colors gap-3" style="font-family: 'Manrope', sans-serif;">
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