
<main class="shop-container">
    <aside class="sidebar">
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
            <div class="filter-content" x-show="open" x-collapse>
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
            <div class="filter-content color-options" x-show="open" x-collapse>
                @foreach($colors as $color)
                    <label style="cursor: pointer;">
                        <input type="checkbox" wire:model.live="selectedColors" value="{{ $color->id }}" style="display: none;">
                        <span class="color-circle" 
                              style="background-color: {{ $color->hex_code ?? str_replace(' ', '', strtolower($color->name)) }}; {{ in_array($color->id, $selectedColors) ? 'border: 2px solid #000; transform: scale(1.1);' : '' }}" 
                              title="{{ $color->name }}">
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
            <div class="filter-content" x-show="open" x-collapse>
                <label><input type="radio" wire:model.live="priceRange" value="under_5k"> Under 5k</label>
                <label><input type="radio" wire:model.live="priceRange" value="5k_10k"> 5k - 10k</label>
                <label><input type="radio" wire:model.live="priceRange" value="10k_20k"> 10k - 20k</label>
                <label><input type="radio" wire:model.live="priceRange" value="above_20k"> Above 20k</label>
                
                <div class="price-range mt-3">
                    <p>Range &rarr; ₹</p>
                    <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="Min">
                    <span>-</span>
                    <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="Max">
                </div>
            </div>
        </div>

        <div class="filter-group" x-data="{ open: true }">
            <h3 @click="open = !open">
                PATTERN (Optional)
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
            </h3>
            <div class="filter-content" x-show="open" x-collapse>
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
        <div class="listing-header">
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

        <div class="product-grid" wire:loading.class="opacity-50">
            @forelse($products as $product)
                <div class="product-card">
                    <a href="{{ route('shop.product', $product->id) }}" class="block">
                        <div class="img-wrapper">
                            @php
                                $imageUrl = is_array($product->images) && count($product->images) > 0 
                                    ? asset('storage/' . $product->images[0]) 
                                    : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80';
                            @endphp
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                            
                            <button 
    wire:click.prevent="toggleWishlist({{ $product->id }})" 
    style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 8px; border-radius: 50%; border: none; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s ease;"
    onmouseover="this.style.transform='scale(1.1)'"
    onmouseout="this.style.transform='scale(1)'"
    title="Add to Wishlist"
>
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="transition: all 0.3s; {{ in_array($product->id, session()->get('wishlist', [])) ? 'fill: #800020; color: #800020;' : 'fill: none; color: #706663;' }}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
</button>
                        </div>
                        <h3 class="mt-3 hover:text-indigo-600 transition-colors">{{ $product->name }}</h3>
                    </a>
                    <p>₹{{ number_format($product->current_price, 2) }}</p>
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