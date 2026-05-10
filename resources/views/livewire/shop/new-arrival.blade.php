<main class="arrival-container" style="padding-top: 1.5rem;">
    
    <div class="arrival-header" style="margin-bottom: 2rem;">
        <p class="subtitle">SPRING SUMMER 2026</p>
        <h1 style="margin-top: 0.5rem;">Just Introduced</h1>
        <p class="description">Discover the latest masterpieces from our looms, where traditional artistry meets modern silhouettes.</p>
    </div>

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
            <div class="arrival-card">
                <a href="{{ route('shop.product', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                    <div class="img-box">
                        @php
                            $img = is_array($product->images) && count($product->images) > 0 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80';
                        @endphp
                        <img src="{{ $img }}" alt="{{ $product->name }}">
                        <span class="tag">NEW</span>
                    </div>
                    <div class="arrival-info">
                        <h3>{{ $product->name }}</h3>
                        <p class="price">₹{{ number_format($product->current_price, 2) }}</p>
                        <button class="btn-view">QUICK VIEW</button>
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