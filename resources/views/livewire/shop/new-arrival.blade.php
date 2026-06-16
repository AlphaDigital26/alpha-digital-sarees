<main class="arrival-container" style="padding-top: 1.5rem;">
    
    <div class="arrival-header" style="margin-bottom: 2rem;">
        <p class="subtitle">SPRING SUMMER 2026</p>
        <h1 style="margin-top: 0.5rem;">Just Introduced</h1>
        <p class="description">Discover the latest masterpieces from our looms, where traditional artistry meets modern silhouettes.</p>
    </div>

    <x-toast-notification />



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

    <div class="arrival-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <x-product-card :product="$product" :showWishlist="true" :isNewArrival="true" />
        @empty
            <p style="grid-column: 1 / -1; text-align: center; padding: 4rem 0; color: #666; font-style: italic;">
                No new arrivals match these filters.
            </p>
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