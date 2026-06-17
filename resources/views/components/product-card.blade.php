@props(['product', 'showWishlist' => false, 'isNewArrival' => false])

<div class="{{ $isNewArrival ? 'arrival-card' : 'product-card' }}" style="{{ $isNewArrival ? 'position: relative;' : '' }}" wire:key="product-{{ $product->id }}">
    <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" class="block" @if($isNewArrival) wire:navigate style="text-decoration: none; color: inherit; display: block; position: relative;" @endif>
        <div class="{{ $isNewArrival ? 'img-box' : 'img-wrapper' }}" @if($isNewArrival) style="position: relative;" @endif>
            @php
                $mainImg = is_array($product->images) && count($product->images) > 0 
                    ? asset('storage/' . $product->images[0]) 
                    : 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80';
                $hoverImg = is_array($product->images) && count($product->images) > 1 
                    ? asset('storage/' . $product->images[1]) 
                    : $mainImg;
            @endphp
            <img src="{{ $mainImg }}" alt="{{ $product->name }}" class="main-img" loading="lazy" decoding="async">
            <img src="{{ $hoverImg }}" alt="{{ $product->name }} (Hover)" class="hover-img" loading="lazy" decoding="async">
            
            @if($isNewArrival)
                <span class="tag">NEW</span>
            @endif

            @if($showWishlist)
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
            @endif
        </div>
        
        @if($isNewArrival)
        <div class="arrival-info">
            <h3>{{ $product->name }}</h3>
            <div class="flex flex-wrap items-baseline justify-center gap-x-2 gap-y-1 mt-1 mb-2">
                <p class="font-bold text-[#800020] m-0 text-base md:text-lg whitespace-nowrap">₹{{ number_format($product->current_price, 2) }}</p>
                @if($product->original_price > $product->current_price)
                    <p class="text-gray-400 line-through text-sm m-0 font-normal whitespace-nowrap" style="color: #9ca3af !important;">₹{{ number_format($product->original_price, 2) }}</p>
                    @php
                        $discountPercent = round((($product->original_price - $product->current_price) / $product->original_price) * 100);
                    @endphp
                    <span class="text-green-600 text-xs font-bold whitespace-nowrap">({{ $discountPercent }}% OFF)</span>
                @endif
            </div>
            <button class="btn-view" tabindex="-1">QUICK VIEW</button>
        </div>
        @else
        <h3>{{ $product->name }}</h3>
        <div class="flex flex-wrap items-baseline justify-center gap-x-2 gap-y-1 mt-1 mb-2">
            <p class="font-bold text-[#800020] m-0 text-base md:text-lg whitespace-nowrap">₹{{ number_format($product->current_price, 2) }}</p>
            @if($product->original_price > $product->current_price)
                <p class="text-gray-400 line-through text-sm m-0 font-normal whitespace-nowrap" style="color: #9ca3af !important;">₹{{ number_format($product->original_price, 2) }}</p>
                @php
                    $discountPercent = round((($product->original_price - $product->current_price) / $product->original_price) * 100);
                @endphp
                <span class="text-green-600 text-xs font-bold whitespace-nowrap">({{ $discountPercent }}% OFF)</span>
            @endif
        </div>
        @endif
    </a>
</div>
