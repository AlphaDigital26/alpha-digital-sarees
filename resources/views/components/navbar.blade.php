<header x-data="{ mobileMenuOpen: false }" class="fixed top-0 left-0 w-full h-[76px] flex justify-between items-center px-[4%] md:px-[6%] bg-[#F4F0EB]/90 backdrop-blur-xl border-b border-[#E5E0DA] shadow-sm z-[1000] transition-all duration-300">
    
    <!-- Left Section: Hamburger & Logo -->
    <div class="flex items-center gap-4">
        <!-- Mobile Hamburger Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="flex items-center lg:hidden text-[#2A211F] hover:text-[#800020] transition-colors focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileMenuOpen" x-cloak style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-2 text-xl sm:text-2xl font-bold tracking-widest text-[#800020] logo shrink-0 transition-transform hover:scale-105 duration-300">
            @if($settings && $settings->logo_type === 'image' && $settings->logo_image)
                <img src="{{ asset('storage/' . $settings->logo_image) }}" alt="Alpha Digital Logo" class="h-10 sm:h-14 w-auto object-contain drop-shadow-sm">
            @else
                {{ $settings->logo_text ?? 'ALPHA DIGITAL' }}
            @endif
        </a>
    </div>

    <!-- Center Section: Navigation Links -->
    <nav class="hidden lg:flex gap-6 lg:gap-10 items-center justify-center flex-1">
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

    <div class="flex items-center gap-4 sm:gap-6 shrink-0">
        <form action="{{ route('shop.index') }}" method="GET" class="hidden lg:flex items-center h-[42px] m-0 bg-white/50 hover:bg-white transition-colors px-5 rounded-full border border-[#E5E0DA] focus-within:border-[#800020] focus-within:bg-white focus-within:shadow-sm">
            <button type="submit" class="bg-transparent border-none p-0 m-0 cursor-pointer outline-none flex items-center justify-center text-[#706663] hover:text-[#800020] transition-colors">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
            <input type="text" name="search" placeholder="Search Alpha Digital" value="{{ request('search') }}" class="border-none bg-transparent outline-none m-0 p-0 pl-3 h-full font-sans text-[13px] text-[#2A211F] placeholder-[#706663] w-[180px] transition-all focus:w-[220px]">
        </form>

        <div class="flex items-center gap-4 sm:gap-5 nav-icons">
        <livewire:nav-icons />
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <template x-teleport="body">
        <div x-show="mobileMenuOpen" 
             x-cloak style="display: none;"
             class="fixed inset-0 z-[2000] lg:hidden flex">
            
            <!-- Overlay -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 @click="mobileMenuOpen = false" 
                 class="fixed inset-0 bg-black/50"></div>
            
            <!-- Drawer Panel -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform" 
                 x-transition:enter-start="-translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transition ease-in-out duration-300 transform" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="-translate-x-full" 
                 class="relative flex flex-col w-full max-w-xs pt-5 pb-4 bg-[#F4F0EB] shadow-2xl h-full border-r border-[#E5E0DA]">
            
            <div class="flex items-center justify-between px-4 mb-6">
                <a href="{{ route('home') }}" class="text-xl font-bold tracking-widest text-[#800020] font-serif">ALPHA DIGITAL</a>
                <button @click="mobileMenuOpen = false" class="text-[#2A211F] hover:text-[#800020] focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="px-4 mb-6">
                <form action="{{ route('shop.index') }}" method="GET" class="flex items-center h-[42px] m-0 bg-white px-4 rounded-md border border-[#E5E0DA]">
                    <button type="submit" class="text-[#706663] mr-2">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                    <input type="text" name="search" placeholder="Search" value="{{ request('search') }}" class="w-full bg-transparent border-none outline-none font-sans text-[14px]">
                </form>
            </div>
            
            <div class="flex-1 px-2 space-y-1 overflow-y-auto">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors {{ request()->routeIs('home') ? 'bg-white text-[#800020]' : '' }}">Home</a>
                <a href="{{ route('shop.index') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors {{ request()->routeIs('shop.index') ? 'bg-white text-[#800020]' : '' }}">All Sarees</a>
                <a href="{{ route('shop.new-arrival') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors {{ request()->routeIs('shop.new-arrival') ? 'bg-white text-[#800020]' : '' }}">New Arrival</a>
                <a href="{{ route('shop.occasion') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors {{ request()->routeIs('shop.occasion') ? 'bg-white text-[#800020]' : '' }}">Occasion</a>
                <a href="{{ route('shop.about') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors {{ request()->routeIs('shop.about') ? 'bg-white text-[#800020]' : '' }}">Our Story</a>
                
                <div class="border-t border-[#E5E0DA] mt-4 pt-4">
                    @auth('customer')
                        <a href="{{ route('profile.account') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors">My Account</a>
                        <a href="{{ route('profile.orders') }}" class="block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#2A211F] hover:bg-white hover:text-[#800020] rounded-md transition-colors">Order History</a>
                        <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#800020] hover:bg-white rounded-md transition-colors">Sign Out</button>
                        </form>
                    @else
                        <button @click="$dispatch('open-login-modal'); mobileMenuOpen = false" class="w-full text-left block px-4 py-3 text-base font-bold tracking-widest uppercase text-[#800020] hover:bg-white rounded-md transition-colors">Login / Sign Up</button>
                    @endauth
                </div>
            </div>
        </div>
    </template>
</header>