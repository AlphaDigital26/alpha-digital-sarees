<div class="flex items-center gap-4 sm:gap-5 nav-icons">
    <a href="{{ route('wishlist') }}" wire:navigate class="relative text-[#2A211F] hover:text-[#800020] transition-colors group" title="Wishlist">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 {{ request()->routeIs('wishlist') ? 'fill-[#800020] text-[#800020]' : 'fill-none' }} group-hover:scale-110 transition-transform">
            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
        </svg>
        @if($wishlistCount > 0)
            <span class="absolute -top-1.5 -right-2 bg-[#800020] text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-sm">
                {{ $wishlistCount }}
            </span>
        @endif
    </a>
    
    <a href="{{ route('cart') }}" wire:navigate class="relative text-[#2A211F] hover:text-[#800020] transition-colors group" title="Cart">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 group-hover:scale-110 transition-transform {{ request()->routeIs('cart') ? 'text-[#800020]' : '' }}">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
            <path d="M3 6h18"/>
            <path d="M16 10a4 4 0 0 1-8 0"/>
        </svg>
        @if($cartCount > 0)
            <span class="absolute -top-1.5 -right-2 bg-[#800020] text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-sm">
                {{ $cartCount }}
            </span>
        @endif
    </a>
    
    @auth('customer')
        <div class="relative hidden sm:block" x-data="{ open: false }">
            <button @click="open = !open" @click.outside="open = false" title="My Account" class="bg-transparent border-none cursor-pointer p-0 m-0 outline-none text-[#800020] transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 group-hover:scale-110 transition-transform">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <polyline points="16 11 18 13 22 9"/>
                </svg>
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
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    My Profile
                </a>
                <a href="{{ route('profile.orders') }}" class="flex items-center px-6 py-3 text-sm font-medium text-[#2A211F] hover:bg-white/80 hover:text-[#800020] transition-colors gap-3 font-sans">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="m7.5 4.27 9 5.15"/>
                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/>
                        <path d="M12 22V12"/>
                    </svg>
                    Order History
                </a>
                <div class="border-t border-[#E5E0DA] mt-2 pt-2">
                    <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-6 py-3 text-sm font-medium text-[#800020] hover:bg-white/80 transition-colors gap-3 bg-transparent border-none cursor-pointer text-left outline-none font-sans">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <button x-data @click="$dispatch('open-login-modal')" title="Login / Signup" class="hidden sm:block bg-transparent border-none cursor-pointer p-0 m-0 outline-none text-[#2A211F] hover:text-[#800020] transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 group-hover:scale-110 transition-transform">
                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </button>
    @endauth
</div>
