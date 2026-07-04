@props(['title', 'image', 'products', 'shopLink' => '/shop'])

<div class="editorial-slider-section">
    <div class="editorial-slider-row">
        <!-- Featured Banner Card -->
        <div class="editorial-banner group">
            <img src="{{ $image }}" alt="{{ $title }}" width="400" height="600" class="editorial-banner-img" loading="lazy" decoding="async">
            <div class="editorial-overlay">
                <h2>{{ $title }}</h2>
                <a href="{{ $shopLink }}" class="editorial-btn">SHOP NOW</a>
            </div>
        </div>

        <!-- Product Slider -->
        <div class="editorial-slider-wrapper">
            <button onclick="this.parentElement.querySelector('.editorial-slider-container').scrollBy({left: -350, behavior: 'smooth'})" class="editorial-slider-btn left">
                <i data-lucide="chevron-left"></i>
            </button>

            <div class="editorial-slider-container">
                @foreach($products as $product)
                    <div class="editorial-card">
                        <a href="{{ route('shop.product', $product->slug ?? $product->id) }}" class="editorial-card-link">
                            @php
                                $mainImgPath = is_array($product->images) && count($product->images) > 0 ? $product->images[0] : null;
                                $hoverImgPath = is_array($product->images) && count($product->images) > 1 ? $product->images[1] : $mainImgPath;

                                $getVariants = function($path) {
                                    if (!$path) return ['src' => 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80', 'srcset' => ''];
                                    $info = pathinfo($path);
                                    $dir = $info['dirname'] === '.' ? '' : $info['dirname'] . '/';
                                    $base = $info['filename'];
                                    $src400 = asset('storage/' . $dir . $base . '-400w.webp');
                                    $src800 = asset('storage/' . $dir . $base . '-800w.webp');
                                    $srcOriginal = asset('storage/' . $path);
                                    return ['src' => $srcOriginal, 'srcset' => "{$src400} 400w, {$src800} 800w, {$srcOriginal} 1200w"];
                                };

                                $mainImg = $getVariants($mainImgPath);
                                $hoverImg = $getVariants($hoverImgPath);
                            @endphp
                            <img srcset="{{ $mainImg['srcset'] }}" sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
                                 src="{{ $mainImg['src'] }}" width="800" height="1200" alt="{{ $product->name }}" class="editorial-card-img main-img" loading="lazy" decoding="async">
                            <img data-src="{{ $hoverImg['src'] }}" data-srcset="{{ $hoverImg['srcset'] }}" sizes="(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw"
                                 src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" width="800" height="1200"
                                 alt="{{ $product->name }} (Hover)" class="editorial-card-img hover-img lazy-hover" loading="lazy" decoding="async">
                            
                            <button 
                                wire:click.prevent="toggleWishlist({{ $product->id }})" 
                                class="editorial-wishlist-btn"
                                title="Add to Wishlist"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="transition: all 0.3s; {{ in_array($product->id, \App\Services\WishlistService::getWishlistProductIds()) ? 'fill: #800020; color: #800020;' : 'fill: none; color: #706663;' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </a>
                    </div>
                @endforeach
            </div>

            <button onclick="this.parentElement.querySelector('.editorial-slider-container').scrollBy({left: 350, behavior: 'smooth'})" class="editorial-slider-btn right">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>
</div>
