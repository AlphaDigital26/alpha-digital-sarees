<main class="arrival-container" style="padding-top: 1.5rem;">
    
    <div class="arrival-header" style="margin-bottom: 2rem;">
        <p class="subtitle">SPRING SUMMER 2026</p>
        <h1 style="margin-top: 0.5rem;">Just Introduced</h1>
        <p class="description">Discover the latest masterpieces from our looms, where traditional artistry meets modern silhouettes.</p>
    </div>

    {{-- Success Notification for Wishlist Actions --}}
    @if (session()->has('success'))
        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px; text-align: center; font-weight: bold; border-radius: 4px; margin-bottom: 2rem; font-size: 0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 1px solid #eaeaea; padding-bottom: 1rem; gap: 1rem;">
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            
            <select wire:model.live="selectedFabric" style="padding: 8px 12px; border: 1px solid #ddd; font-size: 0.85rem; color: #333; outline: none; cursor: pointer; background-color: transparent;">
                <option value="">All Fabrics</option>
                @foreach($fabrics as $fabric)
                    <option value="{{ $fabric->id }}">{{ $fabric->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="selectedColor" style="padding: 8px 12px; border: 1px solid #ddd; font-size: 0.85rem; color: #333; outline: none; cursor: pointer; background-color: transparent;">
                <option value="">All Colors</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="selectedPattern" style="padding: 8px 12px; border: 1px solid #ddd; font-size: 0.85rem; color: #333; outline: none; cursor: pointer; background-color: transparent;">
                <option value="">All Patterns</option>
                @foreach($patterns as $pattern)
                    <option value="{{ $pattern->id }}">{{ $pattern->name }}</option>
                @endforeach
            </select>

        </div>

        <div>
            <select wire:model.live="sort" style="padding: 8px 12px; border: 1px solid #ddd; font-size: 0.85rem; color: #333; outline: none; cursor: pointer; background-color: transparent;">
                <option value="latest">Sort by: Latest</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
            </select>
        </div>
    </div>

    <div class="arrival-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem;">
        @forelse($products as $product)
            <div class="arrival-card" wire:key="product-{{ $product->id }}">
                <a href="{{ route('shop.product', $product->id) }}" wire:navigate style="text-decoration: none; color: inherit; display: block; position: relative;">
                    <div class="img-box" style="position: relative;">
                        @php
                            $img = is_array($product->images) && count($product->images) > 0 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80';
                        @endphp
                        <img src="{{ $img }}" alt="{{ $product->name }}">
                        <span class="tag">NEW</span>

                        {{-- WISHLIST HEART ICON --}}
                        <button 
                            wire:click.prevent="toggleWishlist({{ $product->id }})" 
                            style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 8px; border-radius: 50%; border: none; cursor: pointer; z-index: 10; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s ease;"
                            onmouseover="this.style.transform='scale(1.1)'"
                            onmouseout="this.style.transform='scale(1)'"
                            title="Add to Wishlist"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="transition-colors duration-300" style="{{ in_array($product->id, session()->get('wishlist', [])) ? 'fill: #800020; color: #800020;' : 'fill: none; color: #706663;' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>

                    </div>
                    <div class="arrival-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="price">₹{{ number_format($product->current_price, 2) }}</p>
                        <button class="btn-view" tabindex="-1">QUICK VIEW</button>
                    </div>
                </a>
            </div>
        @empty
            <p style="grid-column: 1 / -1; text-align: center; padding: 4rem 0; color: #666; font-style: italic;">
                No new arrivals match these filters.
            </p>
        @endforelse
    </div>
</main>