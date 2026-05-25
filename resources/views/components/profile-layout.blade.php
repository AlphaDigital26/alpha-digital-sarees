<profile-layout>
<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 pt-[140px] md:pt-[160px]">
        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- Sidebar --}}
            <div class="w-full md:w-1/4 shrink-0">
                <div class="bg-surface_lowest border border-outline_variant/50 rounded-sm sticky top-[120px]">
                    {{-- User Info Header --}}
                    <div class="p-6 border-b border-outline_variant/50">
                        <h2 class="text-lg font-bold text-secondary m-0 uppercase tracking-wide font-serif">
                            {{ auth('customer')->user()->name ?? 'User Name' }}
                        </h2>
                        <p class="text-sm text-tertiary mt-1 m-0 font-sans">
                            {{ auth('customer')->user()->email ?? '' }}
                        </p>
                    </div>

                    {{-- Navigation Links --}}
                    <nav class="flex flex-col py-2 font-sans">
                        <a href="{{ route('profile.account') }}" class="flex items-center justify-between px-6 py-4 text-sm font-medium transition-colors border-l-2 {{ request()->routeIs('profile.account') ? 'border-primary text-primary bg-surface' : 'border-transparent text-tertiary hover:bg-surface_low hover:text-primary' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                Account Details
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        </a>

                        <a href="{{ route('profile.orders') }}" class="flex items-center justify-between px-6 py-4 text-sm font-medium transition-colors border-l-2 {{ request()->routeIs('profile.orders') ? 'border-primary text-primary bg-surface' : 'border-transparent text-tertiary hover:bg-surface_low hover:text-primary' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                Order History
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        </a>

                        <a href="{{ route('profile.addresses') }}" class="flex items-center justify-between px-6 py-4 text-sm font-medium transition-colors border-l-2 {{ request()->routeIs('profile.addresses') ? 'border-primary text-primary bg-surface' : 'border-transparent text-tertiary hover:bg-surface_low hover:text-primary' }}">
                            <div class="flex items-center gap-3">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                Addresses
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        </a>

                        <a href="{{ route('wishlist') }}" class="flex items-center justify-between px-6 py-4 text-sm font-medium transition-colors border-l-2 border-transparent text-tertiary hover:bg-surface_low hover:text-primary">
                            <div class="flex items-center gap-3">
                                <i data-lucide="heart" class="w-4 h-4"></i>
                                Wishlist
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        </a>

                        <form method="POST" action="{{ route('customer.logout') }}" class="m-0 p-0 border-t border-outline_variant/50 mt-2">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium transition-colors border-l-2 border-transparent text-tertiary hover:bg-surface_low hover:text-primary text-left outline-none bg-transparent cursor-pointer">
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            {{-- Main Content Slot --}}
            <div class="w-full md:w-3/4">
                {{ $slot }}
            </div>
            
        </div>
    </div>
</x-layouts.app>
