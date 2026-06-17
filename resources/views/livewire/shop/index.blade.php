
<main class="shop-container" x-data="{ mobileFiltersOpen: false }" x-init="
    // Close mobile filter panel whenever this page is (re)loaded or navigated to
    document.addEventListener('livewire:navigated', () => { mobileFiltersOpen = false; });
">
    <!-- Mobile Filter Toggle Button -->
    <div class="md:hidden w-full mb-4">
        <button @click="mobileFiltersOpen = !mobileFiltersOpen" class="w-full flex items-center justify-between bg-white border border-[#E5E0DA] py-3 px-4 font-sans font-bold text-[#2A211F] text-[0.8rem] tracking-[1px] shadow-sm">
            <span>FILTER COLLECTION</span>
            <svg x-show="!mobileFiltersOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
            <svg x-show="mobileFiltersOpen" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <aside class="sidebar" :class="mobileFiltersOpen ? 'block mb-8' : 'hidden md:block'">
        <div class="flex items-center justify-between" style="margin-bottom: 1.5rem; border-bottom: 1px solid #E5E0DA; padding-bottom: 0.5rem;">
            <h2 class="filter-title" style="border-bottom: none; padding-bottom: 0; margin-bottom: 0;">
                FILTERS 
            </h2>
            @if(count($selectedFabrics) > 0 || count($selectedColors) > 0 || count($selectedPatterns) > 0 || $priceRange || $search)
                <button wire:click="resetFilters" class="text-xs text-[#800020] uppercase font-bold tracking-widest hover:underline cursor-pointer">Clear All</button>
            @endif
        </div>
        
        <div class="filter-group" x-data="{ open: false }">
            <h3 @click="open = !open">
                FABRIC
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
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

        <div class="filter-group" x-data="{ open: false }">
            <h3 @click="open = !open">
                COLOR
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
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

        <div class="filter-group" x-data="{ open: false }">
            <h3 @click="open = !open">
                PRICE
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
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

        <div class="filter-group" x-data="{ open: false }">
            <h3 @click="open = !open">
                PATTERN
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
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

        <div class="product-grid grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6" wire:loading.class="opacity-50">
            @forelse($products as $product)
                <x-product-card :product="$product" :showWishlist="true" />
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