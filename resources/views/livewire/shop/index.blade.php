
<main class="shop-container" x-data="{ mobileFiltersOpen: false }">
    <!-- Mobile Filter Toggle Button -->
    <div class="md:hidden w-full mb-4">
        <button @click="mobileFiltersOpen = !mobileFiltersOpen" class="w-full flex items-center justify-between bg-white border border-[#E5E0DA] py-3 px-4 font-sans font-bold text-[#2A211F] text-[0.8rem] tracking-[1px] shadow-sm">
            <span>FILTER COLLECTION</span>
            <svg x-show="!mobileFiltersOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
            <svg x-show="mobileFiltersOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <aside class="sidebar" :class="mobileFiltersOpen ? 'block mb-8' : 'hidden md:block'">
        <h2 class="filter-title">
            FILTERS 
            <span wire:loading class="text-xs ml-2 text-gray-400">Updating...</span>
        </h2>
        
        <div class="filter-group" x-data="{ open: true }">
            <h3 @click="open = !open">
                FABRIC
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </h3>
            <div class="filter-content max-h-52 overflow-y-auto pr-2 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-[#E5E0DA] [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-[#D1C9C0]" x-show="open" x-collapse>
                @foreach($fabrics as $fabric)
                    <label>
                        <input type="checkbox" wire:model.live="selectedFabrics" value="{{ $fabric->id }}"> 
                        {{ $fabric->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="filter-group" x-data="{ open: true }">
            <h3 @click="open = !open">
                COLOR
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </h3>
            <div class="filter-content flex flex-col gap-3 mt-4 max-h-52 overflow-y-auto pr-2 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-[#E5E0DA] [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-[#D1C9C0]" x-show="open" x-collapse>
                @foreach($colors as $color)
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" wire:model.live="selectedColors" value="{{ $color->id }}" 
                               class="w-[18px] h-[18px] rounded-sm border-gray-300 text-[#800020] focus:ring-[#800020] cursor-pointer transition-colors shadow-sm">
                        
                        @php
                            $isMulti = strtolower($color->name) === 'multi' || strtolower($color->name) === 'multicolor';
                            $hex = $color->hex_code ?? str_replace(' ', '', strtolower($color->name));
                            $bgStyle = $isMulti 
                                ? 'background: conic-gradient(#ff595e 0 90deg, #ffca3a 90deg 180deg, #8ac926 180deg 270deg, #1982c4 270deg 360deg);' 
                                : 'background-color: ' . $hex . ';';
                        @endphp
                        
                        <span class="w-5 h-5 rounded-full border border-gray-200 shadow-sm block transition-transform duration-300 group-hover:scale-110" 
                              style="{{ $bgStyle }}">
                        </span>
                        
                        <span class="text-[0.9rem] font-medium text-[#555] font-sans tracking-wide group-hover:text-[#1b1c1a] transition-colors" style="font-family: 'Manrope', sans-serif;">
                            {{ $color->name }}
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="filter-group" x-data="{ open: true }">
            <h3 @click="open = !open">
                PRICE
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </h3>
            <div class="filter-content max-h-52 overflow-y-auto pr-2 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-[#E5E0DA] [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-[#D1C9C0]" x-show="open" x-collapse>
                <label><input type="radio" wire:model.live="priceRange" value="under_5k"> Under 5k</label>
                <label><input type="radio" wire:model.live="priceRange" value="5k_10k"> 5k - 10k</label>
                <label><input type="radio" wire:model.live="priceRange" value="10k_20k"> 10k - 20k</label>
                <label><input type="radio" wire:model.live="priceRange" value="above_20k"> Above 20k</label>
                
                <div class="price-range mt-3 flex items-center gap-2">
                    <p class="text-[0.8rem] m-0">Range &rarr; ₹</p>
                    <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="Min" class="w-[70px] p-1 text-[0.8rem]">
                    <span>-</span>
                    <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="Max" class="w-[70px] p-1 text-[0.8rem]">
                </div>
            </div>
        </div>

        <div class="filter-group" x-data="{ open: true }">
            <h3 @click="open = !open">
                PATTERN
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </h3>
            <div class="filter-content max-h-52 overflow-y-auto pr-2 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-[#E5E0DA] [&::-webkit-scrollbar-thumb]:rounded-full hover:[&::-webkit-scrollbar-thumb]:bg-[#D1C9C0]" x-show="open" x-collapse>
                @foreach($patterns as $pattern)
                    <label>
                        <input type="checkbox" wire:model.live="selectedPatterns" value="{{ $pattern->id }}"> 
                        {{ $pattern->name }}
                    </label>
                @endforeach
            </div>
        </div>
        
    </aside>

    <section class="listing-area">
        <div class="listing-header flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            <p class="item-count">Showing {{ $products->total() }} items</p>
            <div class="sort-dropdown">
                <span>Sort by:</span>
                <select wire:model.live="sortBy">
                    <option value="latest">Latest</option>
                    <option value="price_desc">Price: High to Low</option>
                    <option value="price_asc">Price: Low to High</option>
                </select>
            </div>
        </div>

        <div class="product-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" wire:loading.class="opacity-50">
            @forelse($products as $product)
                <div class="product-card">
                    <a href="{{ route('shop.product', $product->id) }}" class="block">
                        <div class="img-wrapper">
                            @php
                                $mainImageUrl = is_array($product->images) && count($product->images) > 0 
                                    ? asset('storage/' . $product->images[0]) 
                                    : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80';
                                $hoverImageUrl = is_array($product->images) && count($product->images) > 1 
                                    ? asset('storage/' . $product->images[1]) 
                                    : $mainImageUrl;
                            @endphp
                            <img src="{{ $mainImageUrl }}" alt="{{ $product->name }}" class="main-img">
                            <img src="{{ $hoverImageUrl }}" alt="{{ $product->name }} (Hover)" class="hover-img">
                            
                            <button 
    wire:click.prevent="toggleWishlist({{ $product->id }})" 
    style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 8px; border-radius: 50%; border: none; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s ease;"
    onmouseover="this.style.transform='scale(1.1)'"
    onmouseout="this.style.transform='scale(1)'"
    title="Add to Wishlist"
>
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="transition: all 0.3s; {{ in_array($product->id, \App\Services\WishlistService::getWishlistProductIds()) ? 'fill: #800020; color: #800020;' : 'fill: none; color: #706663;' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
</button>
                        </div>
                        <h3 class="mt-3 hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
                    </a>
                    <div class="flex flex-wrap items-baseline justify-center gap-x-2 gap-y-1 mt-1 mb-2">
                        <p class="font-bold text-[#800020] m-0 text-sm whitespace-nowrap">₹{{ number_format($product->current_price, 2) }}</p>
                        @if($product->original_price > $product->current_price)
                            <p class="text-gray-400 line-through text-xs m-0 font-normal whitespace-nowrap" style="color: #9ca3af !important;">₹{{ number_format($product->original_price, 2) }}</p>
                            @php
                                $discountPercent = round((($product->original_price - $product->current_price) / $product->original_price) * 100);
                            @endphp
                            <span class="text-green-600 text-[10px] font-bold whitespace-nowrap">({{ $discountPercent }}% OFF)</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 0;">
                    <h3>No Sarees Found</h3>
                    <p>Try adjusting your filters to see more results.</p>
                    <button wire:click="resetFilters" class="btn-discover" style="margin-top: 1rem;">Clear Filters</button>
                </div>
            @endforelse
        </div>

    <div class="load-more flex justify-center mt-16 mb-20">
        @if ($products->hasMorePages())
            <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="px-10 py-4 border border-[#800020] text-[#800020] bg-white hover:bg-[#800020] hover:text-white transition-colors duration-300 uppercase tracking-[2px] font-bold text-[0.8rem] min-w-[250px]">
                <span wire:loading.remove wire:target="loadMore">Discover More</span>
                <span wire:loading wire:target="loadMore">Loading...</span>
            </button>
        @else
            <button disabled
                    class="px-10 py-4 border border-[#e5e5e5] text-[#999] bg-[#f9f9f9] uppercase tracking-[2px] font-bold text-[0.8rem] min-w-[250px] cursor-not-allowed">
                You've Viewed All
            </button>
        @endif
    </div>
</main>