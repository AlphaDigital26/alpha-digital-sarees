<main class="shop-container">
    <aside class="sidebar">
        <h2 class="filter-title">
            FILTERS 
            <span wire:loading class="text-xs ml-2 text-gray-400">Updating...</span>
        </h2>
        
        <div class="filter-group">
            <h3>FABRIC</h3>
            @foreach($fabrics as $fabric)
                <label>
                    <input type="checkbox" wire:model.live="selectedFabrics" value="{{ $fabric->id }}"> 
                    {{ $fabric->name }}
                </label>
            @endforeach
        </div>

        <div class="filter-group">
            <h3>COLOR</h3>
            <div class="color-options">
                @foreach($colors as $color)
                    <label style="cursor: pointer;">
                        <input type="checkbox" wire:model.live="selectedColors" value="{{ $color->id }}" style="display: none;">
                        <span class="color-circle" 
                              style="background: {{ strtolower($color->name) }}; {{ in_array($color->id, $selectedColors) ? 'border: 2px solid #000; transform: scale(1.1);' : '' }}" 
                              title="{{ $color->name }}">
                        </span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="filter-group">
            <h3>PRICE</h3>
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

        <div class="filter-group">
            <h3>PATTERN (Optional)</h3>
            @foreach($patterns as $pattern)
                <label>
                    <input type="checkbox" wire:model.live="selectedPatterns" value="{{ $pattern->id }}"> 
                    {{ $pattern->name }}
                </label>
            @endforeach
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
                            
                            <button class="wishlist-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
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

        <div class="load-more">
            {{ $products->links() }} 
        </div>
    </section>
</main>