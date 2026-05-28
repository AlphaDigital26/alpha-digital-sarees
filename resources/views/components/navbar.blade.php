<header class="fixed top-0 left-0 w-full h-[76px] flex justify-between items-center px-[4%] md:px-[6%] bg-[#F4F0EB]/90 backdrop-blur-xl border-b border-[#E5E0DA] shadow-sm z-[1000] transition-all duration-300">
    <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-2xl font-bold tracking-widest text-[#800020] logo shrink-0 transition-transform hover:scale-105 duration-300">
        @if($settings && $settings->logo_type === 'image' && $settings->logo_image)
            <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Alpha Digital Logo" class="h-14 w-auto object-contain drop-shadow-sm">
        @else
            {{ $settings->logo_text ?? 'ALPHA DIGITAL' }}
        @endif
    </a>

    <nav class="hidden lg:flex gap-10 items-center justify-center flex-1">
        <a href="{{ route('home') }}" wire:navigate class="relative text-[#2A211F] font-sans text-[13px] font-medium tracking-[1.5px] transition-all duration-300 hover:text-[#800020] pb-1 group {{ request()->routeIs('home') ? 'text-[#800020]' : '' }}">
            HOME
            <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#800020] transition-all duration-300 origin-center {{ request()->routeIs('home') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }}"></span>
        </a>
        <a href="{{ route('shop.index') }}" wire:navigate class="relative text-[#2A211F] font-sans text-[13px] font-medium tracking-[1.5px] transition-all duration-300 hover:text-[#800020] pb-1 group {{ request()->routeIs('shop.index') ? 'text-[#800020]' : '' }}">
            ALL SAREES
            <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#800020] transition-all duration-300 origin-center {{ request()->routeIs('shop.index') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }}"></span>
        </a>
        <a href="{{ route('shop.new-arrival') }}" wire:navigate class="relative text-[#2A211F] font-sans text-[13px] font-medium tracking-[1.5px] transition-all duration-300 hover:text-[#800020] pb-1 group {{ request()->routeIs('shop.new-arrival') ? 'text-[#800020]' : '' }}">
            NEW ARRIVAL
            <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#800020] transition-all duration-300 origin-center {{ request()->routeIs('shop.new-arrival') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }}"></span>
        </a>
        <a href="{{ route('shop.occasion') }}" wire:navigate class="relative text-[#2A211F] font-sans text-[13px] font-medium tracking-[1.5px] transition-all duration-300 hover:text-[#800020] pb-1 group {{ request()->routeIs('shop.occasion') ? 'text-[#800020]' : '' }}">
            OCCASION
            <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#800020] transition-all duration-300 origin-center {{ request()->routeIs('shop.occasion') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }}"></span>
        </a>
        <a href="{{ route('shop.about') }}" wire:navigate class="relative text-[#2A211F] font-sans text-[13px] font-medium tracking-[1.5px] transition-all duration-300 hover:text-[#800020] pb-1 group {{ request()->routeIs('shop.about') ? 'text-[#800020]' : '' }}">
            OUR STORY
            <span class="absolute left-0 -bottom-1 w-full h-[2px] bg-[#800020] transition-all duration-300 origin-center {{ request()->routeIs('shop.about') ? 'scale-x-100 opacity-100' : 'scale-x-0 opacity-0 group-hover:scale-x-100 group-hover:opacity-100' }}"></span>
        </a>
    </nav>

    <div class="flex items-center gap-6 shrink-0">
        <form action="{{ route('shop.index') }}" method="GET" class="hidden lg:flex items-center bg-white/50 hover:bg-white transition-colors py-2 px-5 rounded-full border border-[#E5E0DA] focus-within:border-[#800020] focus-within:bg-white focus-within:shadow-sm">
            <button type="submit" class="bg-transparent border-none p-0 cursor-pointer outline-none flex items-center justify-center text-[#706663] hover:text-[#800020] transition-colors">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
            <input type="text" name="search" placeholder="Search Alpha Digital" value="{{ request('search') }}" class="border-none bg-transparent outline-none pl-3 font-sans text-xs text-[#2A211F] placeholder-[#706663] w-[180px] transition-all focus:w-[220px]">
        </form>

        <div class="flex items-center gap-5 nav-icons">
            @php
                $wishlistCount = count(session()->get('wishlist', []));
                $cartCount = array_sum(session()->get('cart', []));
            @endphp

            <a href="{{ route('wishlist') }}" wire:navigate class="relative text-[#2A211F] hover:text-[#800020] transition-colors group" title="Wishlist">
                <i data-lucide="heart" stroke-width="1.5" class="w-5 h-5 {{ request()->routeIs('wishlist') ? 'fill-[#800020] text-[#800020]' : '' }} group-hover:scale-110 transition-transform"></i>
                @if($wishlistCount > 0)
                    <span class="absolute -top-1.5 -right-2 bg-[#800020] text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-sm">
                        {{ $wishlistCount }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('cart') }}" wire:navigate class="relative text-[#2A211F] hover:text-[#800020] transition-colors group" title="Cart">
                <i data-lucide="shopping-bag" stroke-width="1.5" class="w-5 h-5 group-hover:scale-110 transition-transform {{ request()->routeIs('cart') ? 'text-[#800020]' : '' }}"></i>
                @if($cartCount > 0)
                    <span class="absolute -top-1.5 -right-2 bg-[#800020] text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-sm">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            
            @auth('customer')
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false" title="My Account" class="bg-transparent border-none cursor-pointer p-0 m-0 outline-none text-[#2A211F] hover:text-[#800020] transition-colors group">
                        <i data-lucide="user-check" stroke-width="1.5" class="w-5 h-5 group-hover:scale-110 transition-transform {{ request()->routeIs('profile.*') ? 'text-[#800020]' : '' }}"></i>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         x-cloak 
                         style="display: none;" 
                         class="absolute right-0 mt-6 w-64 bg-[#F4F0EB]/95 backdrop-blur-xl border border-[#E5E0DA] shadow-2xl rounded-2xl py-2 z-50 text-left overflow-hidden">
                        
                        <div class="px-6 py-4 bg-white/50 mb-2 border-b border-[#E5E0DA]/50">
                            <p class="text-[10px] font-bold text-[#706663] uppercase tracking-widest font-sans">Welcome back,</p>
                            <p class="text-lg font-medium text-[#1b1c1a] truncate mt-1" style="font-family: 'Noto Serif', serif;">
                                {{ auth('customer')->user()->name ?? 'User' }}
                            </p>
                        </div>
                        <a href="{{ route('profile.account') }}" class="flex items-center px-6 py-3 text-sm font-medium text-[#2A211F] hover:bg-white/80 hover:text-[#800020] transition-colors gap-3 font-sans">
                            <i data-lucide="user" stroke-width="1.5" class="w-4 h-4"></i> My Profile
                        </a>
                        <a href="{{ route('profile.orders') }}" class="flex items-center px-6 py-3 text-sm font-medium text-[#2A211F] hover:bg-white/80 hover:text-[#800020] transition-colors gap-3 font-sans">
                            <i data-lucide="package" stroke-width="1.5" class="w-4 h-4"></i> Order History
                        </a>
                        <div class="border-t border-[#E5E0DA] mt-2 pt-2">
                            <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-6 py-3 text-sm font-medium text-[#800020] hover:bg-white/80 transition-colors gap-3 bg-transparent border-none cursor-pointer text-left outline-none font-sans">
                                    <i data-lucide="log-out" stroke-width="1.5" class="w-4 h-4"></i> Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <button x-data @click="$dispatch('open-login-modal')" title="Login / Signup" class="bg-transparent border-none cursor-pointer p-0 m-0 outline-none text-[#2A211F] hover:text-[#800020] transition-colors group">
                    <i data-lucide="user" stroke-width="1.5" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                </button>
            @endauth
        </div>
    </div>
</header>