
<main class="occasion-container">
    <div class="page-header">
        <p class="subtitle">CURATED COLLECTIONS</p>
        <h1>Shop by Occasion</h1>
    </div>

    @php
        // A list of distinct placeholder images for the large feature cards
        $featureImages = [
            'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1610030469915-055106670868?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1544441893-675973eebb39?auto=format&fit=crop&q=80'
        ];
    @endphp

    @forelse($occasions as $index => $occasion)
        @php
            // Get products for this specific occasion
            $occProducts = $productsByOccasion->get($occasion->name, collect());
        @endphp

        @if($occProducts->count() > 0)
            <div class="occasion-row">
                
                <div class="occ-feature-card">
                    <img src="{{ $featureImages[$index % count($featureImages)] }}" alt="{{ $occasion->name }}" class="occ-feature-img">
                    <div class="occ-overlay">
                        <h2>{{ $occasion->name }}</h2>
                        <a href="/shop" class="occ-btn">SHOP NOW</a>
                    </div>
                </div>
                
                <div class="slider-wrapper">
                    <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: -350, behavior: 'smooth'})" class="slider-btn left">
                        <i data-lucide="chevron-left"></i>
                    </button>

                    <div class="occ-slider-container">
                        @foreach($occProducts as $product)
                            <div class="product-card">
                                <a href="{{ route('shop.product', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                                    <div class="img-wrapper">
                                        @php
                                            $img = is_array($product->images) && count($product->images) > 0 
                                                ? asset('storage/' . $product->images[0]) 
                                                : 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80';
                                        @endphp
                                        <img src="{{ $img }}" alt="{{ $product->name }}">
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
                                    <h3>{{ $product->name }}</h3>
                                    <p>₹{{ number_format($product->current_price, 2) }}</p>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: 350, behavior: 'smooth'})" class="slider-btn right">
                        <i data-lucide="chevron-right"></i>
                    </button>
                </div>

            </div>
        @endif
    @empty
        <div style="text-align: center; padding: 4rem 0;">
            <p style="color: #666; font-style: italic;">No occasion collections available at the moment.</p>
        </div>
    @endforelse

</main>