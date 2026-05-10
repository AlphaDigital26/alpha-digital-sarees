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
                                        <button class="wishlist-btn" onclick="event.preventDefault();"><i data-lucide="heart"></i></button>
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