@push('scripts')
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": [
    @if(is_array($product->images))
        @foreach($product->images as $img)
            "{{ asset('storage/' . $img) }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
    @endif
  ],
  "description": "{{ strip_tags($product->description) }}",
  "sku": "{{ $product->id }}",
  "offers": {
    "@type": "Offer",
    "url": "{{ request()->url() }}",
    "priceCurrency": "INR",
    "price": "{{ $product->current_price }}",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Home",
    "item": "{{ route('home') }}"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Shop",
    "item": "{{ route('shop.index') }}"
  },{
    "@type": "ListItem",
    "position": 3,
    "name": "{{ $product->name }}"
  }]
}
</script>
@endpush

<main class="product-main">
    
    {{-- Removed upper inline notification, using toast instead --}}

    <div class="product-container">

        <div class="product-gallery" 
             x-data="{ 
                lightboxOpen: false, 
                currentIndex: 0, 
                isZoomed: false,
                zoomOriginX: '50%',
                zoomOriginY: '50%',
                images: {{ json_encode(is_array($product->images) ? array_values(array_map(function($img) { return asset('storage/' . $img); }, $product->images)) : []) }},
                openLightbox(imgUrl) {
                    if (this.images.length === 0) return;
                    let idx = this.images.indexOf(imgUrl);
                    this.currentIndex = idx !== -1 ? idx : 0;
                    this.isZoomed = false;
                    this.lightboxOpen = true;
                },
                toggleLightboxZoom(e) {
                    if (!this.isZoomed) {
                        const rect = e.target.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        this.zoomOriginX = (x / rect.width * 100) + '%';
                        this.zoomOriginY = (y / rect.height * 100) + '%';
                        this.isZoomed = true;
                    } else {
                        this.isZoomed = false;
                    }
                },
                next() {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                    this.isZoomed = false;
                },
                prev() {
                    this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                    this.isZoomed = false;
                }
             }"
             @keydown.escape.window="lightboxOpen = false"
             @keydown.arrow-right.window="if(lightboxOpen) next()"
             @keydown.arrow-left.window="if(lightboxOpen) prev()">

             <!-- Lightbox Modal -->
             <template x-teleport="body">
                 <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[2000] flex items-center justify-center bg-black bg-opacity-95 overflow-hidden" x-transition.opacity.duration.300ms>
                     
                     <!-- Prominent Close Button -->
                     <button @click="lightboxOpen = false" class="absolute top-4 right-4 md:top-8 md:right-8 text-white hover:text-red-400 z-50 bg-black/60 hover:bg-black/80 p-2.5 md:p-3 rounded-full transition-colors border border-gray-600 shadow-lg">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                     </button>
                     
                     <!-- Previous Button -->
                     <button @click.stop="prev()" class="absolute left-2 md:left-6 text-white hover:text-gray-300 z-40 bg-black/50 hover:bg-black/80 p-3 rounded-full transition-transform hover:scale-110" x-show="images.length > 1">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-10 md:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                     </button>

                     <!-- The Image container ensures perfect center with equal top/bottom padding -->
                     <div class="w-full h-full flex items-center justify-center p-8 md:p-16" @click.self="lightboxOpen = false">
                         <img :src="images[currentIndex]" 
                              class="select-none transition-transform duration-300 max-w-full max-h-full object-contain shadow-2xl"
                              :class="isZoomed ? 'cursor-zoom-out scale-[2.5]' : 'cursor-zoom-in scale-100'" 
                              :style="isZoomed ? `transform-origin: ${zoomOriginX} ${zoomOriginY};` : 'transform-origin: center center;'"
                              @click.stop="toggleLightboxZoom($event)">
                     </div>

                     <!-- Next Button -->
                     <button @click.stop="next()" class="absolute right-2 md:right-6 text-white hover:text-gray-300 z-40 bg-black/50 hover:bg-black/80 p-3 rounded-full transition-transform hover:scale-110" x-show="images.length > 1">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-10 md:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                     </button>

                     <!-- Progress Dots -->
                     <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 px-5 py-3 bg-black/60 backdrop-blur-sm rounded-full z-40" x-show="images.length > 1">
                         <template x-for="(img, index) in images" :key="index">
                             <div @click.stop="currentIndex = index" 
                                  class="w-2.5 h-2.5 rounded-full cursor-pointer transition-all"
                                  :class="currentIndex === index ? 'bg-white scale-125 shadow-[0_0_8px_rgba(255,255,255,0.8)]' : 'bg-gray-500 hover:bg-gray-300'">
                             </div>
                         </template>
                     </div>
                 </div>
             </template>

            <div class="thumbnails">
                @if(is_array($product->images))
                    @foreach($product->images as $img)
                        <img
                            src="{{ asset('storage/' . $img) }}"
                            class="thumb {{ $activeImage === $img ? 'active' : '' }}"
                            wire:click="changeImage('{{ $img }}')"
                            style="cursor: pointer;"
                        >
                    @endforeach
                @endif
            </div>

            <div class="main-display relative group cursor-crosshair" 
                 x-data="{ 
                     showZoom: false, 
                     bgPosX: '0%', 
                     bgPosY: '0%',
                     updateZoom(e) {
                         // Only enable hover zoom on desktop
                         if (window.innerWidth < 1024) return;
                         
                         const rect = this.$refs.mainImage.getBoundingClientRect();
                         const x = e.clientX - rect.left;
                         const y = e.clientY - rect.top;
                         
                         // Calculate percentage
                         const xPercent = Math.max(0, Math.min(100, (x / rect.width) * 100));
                         const yPercent = Math.max(0, Math.min(100, (y / rect.height) * 100));
                         
                         this.bgPosX = xPercent + '%';
                         this.bgPosY = yPercent + '%';
                     }
                 }"
                 @mouseenter="if(window.innerWidth >= 1024) showZoom = true"
                 @mouseleave="showZoom = false"
                 @mousemove="updateZoom($event)"
                 @click="openLightbox('{{ asset('storage/' . $activeImage) }}')">
                 
                <!-- Zoom Hint Overlay (Mobile) -->
                <div class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none z-10 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                </div>
                
                @if($activeImage)
                    <img
                        x-ref="mainImage"
                        src="{{ asset('storage/' . $activeImage) }}"
                        id="expandedImg"
                        wire:loading.class="opacity-50"
                        class="transition-opacity duration-200 w-full h-full object-cover"
                    >
                    
                    <!-- Amazon-style Zoom Pane (Hidden on mobile) -->
                    <div x-show="showZoom" x-cloak
                         x-transition.opacity.duration.200ms
                         class="absolute top-0 left-full ml-4 w-[450px] xl:w-[550px] h-[550px] xl:h-[650px] bg-white border border-[#E5E0DA] shadow-2xl z-[150] hidden lg:block pointer-events-none"
                         style="background-repeat: no-repeat; background-size: 250%;"
                         :style="`background-image: url('{{ asset('storage/' . $activeImage) }}'); background-position: ${bgPosX} ${bgPosY};`">
                    </div>
                @else
                    <img
                        x-ref="mainImage"
                        src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80"
                        id="expandedImg"
                        class="w-full h-full object-cover"
                    >
                @endif
            </div>
        </div>

        <div class="product-info flex-1 flex flex-col pt-0 mt-0"> 

            {{-- Title & Wishlist Toggle Container --}}
            <div class="flex justify-between items-start gap-4">
                <h1 class="leading-none text-3xl font-bold text-[#1b1c1a] m-0 p-0" style="font-family: 'Noto Serif', serif; line-height: 1.1;">
                    {{ $product->name }}
                </h1>
                
                <button wire:click="toggleWishlist({{ $product->id }})" class="p-2 m-0 rounded-full hover:bg-[#F4F0EB] transition-colors flex-shrink-0" title="Add to Wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-7 w-7 transition-colors duration-300 {{ in_array($product->id, \App\Services\WishlistService::getWishlistProductIds()) ? 'fill-[#800020] text-[#800020]' : 'fill-none text-gray-400 hover:text-[#800020]' }}" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor" 
                         stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            </div>

            {{-- UPDATED PRICE SECTION --}}
            <div class="mt-4 mb-4 flex flex-col md:flex-row items-start md:items-baseline gap-1 md:gap-3">
                <p class="price text-3xl md:text-4xl font-bold text-[#800020] leading-none m-0 p-0">
                    Rs. {{ number_format($product->current_price, 2) }}
                </p>

                @if($product->original_price > $product->current_price)
                    @php 
                        $discount = round((($product->original_price - $product->current_price) / $product->original_price) * 100); 
                    @endphp
                    <div class="flex items-center gap-2">
                        <p class="text-xl text-gray-400 line-through m-0">
                            Rs. {{ number_format($product->original_price, 2) }}
                        </p>
                        <span class="text-sm font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">
                            {{ $discount }}% OFF
                        </span>
                    </div>
                @endif
            </div>

            <p class="tax-tag text-xs text-gray-500 mb-6" style="font-family: 'Manrope', sans-serif;">
                Inclusive of all taxes.
            </p>

            <div class="purchase-controls">
                {{-- Stock Status --}}
                @if($product->stock > 0)
                    <span class="stock-status text-green-600 font-bold block italic text-sm mb-2">
                        In Stock ({{ $product->stock }} left)
                    </span>
                @else
                    <span class="stock-status text-red-500 font-bold block italic text-sm mb-2">
                        (Out of stock)
                    </span>
                @endif

                <div class="quantity-box mb-3">
                    <label style="font-size: 0.75rem; font-weight: 700; color: #706663; text-transform: uppercase; font-family: 'Manrope', sans-serif;">
                        Quantity
                    </label>
                    <div class="qty-selector flex items-center border border-[#E5E0DA] bg-white h-10 w-fit mt-2 rounded-sm shadow-sm">
                        <button wire:click="decrementQty" class="w-10 h-full flex items-center justify-center text-gray-600 hover:bg-[#F4F0EB] transition">-</button>
                        <span id="quantity" class="w-10 text-center font-bold text-sm border-x border-[#E5E0DA]" style="font-family: 'Manrope', sans-serif;">
                            {{ $quantity }}
                        </span>
                        <button wire:click="incrementQty" class="w-10 h-full flex items-center justify-center text-gray-600 hover:bg-[#F4F0EB] transition">+</button>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mb-10">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button
                            wire:click="addToCart({{ $product->id }})"
                            class="flex-1 bg-white border-2 border-[#800020] text-[#800020] font-bold py-3.5 px-6 rounded-sm hover:bg-[#800020] hover:text-white transition-colors shadow-sm disabled:opacity-50 uppercase tracking-widest text-xs"
                            {{ $product->stock < 1 ? 'disabled' : '' }}
                        >
                            ADD TO CART
                        </button>
                        <button
                            wire:click="buyNow({{ $product->id }})"
                            class="flex-1 bg-[#800020] text-white font-bold py-3.5 px-6 rounded-sm hover:bg-[#5D4037] transition-colors shadow-md disabled:opacity-50 uppercase tracking-widest text-xs"
                            {{ $product->stock < 1 ? 'disabled' : '' }}
                        >
                            BUY IT NOW
                        </button>
                    </div>

                    <a
                        href="https://wa.me/{{ $settings->whatsapp_number ?? '919876543210' }}?text=Hello!%20I%20am%20interested%20in%20buying%20{{ $quantity }}x%20{{ urlencode($product->name) }}."
                        target="_blank"
                        class="flex w-full items-center justify-center gap-2 rounded-sm bg-[#25D366] px-6 py-4 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-[#20ba5a] shadow-sm"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                        </svg>
                        ORDER ON WHATSAPP
                    </a>
                </div>
            </div>
        </div>

    </div> 
    {{-- END OF .product-container --}}


    {{-- FULL WIDTH TABS SECTION (Moved outside the grid to stretch) --}}
    <div x-data="{ tab: 'description' }" class="w-full mt-10 pt-8 border-t border-[#E5E0DA]">
        
        {{-- Tabs Navigation --}}
        <div class="flex flex-wrap gap-8 mb-8 border-b border-[#E5E0DA]">
            <button @click="tab = 'description'" :class="tab === 'description' ? 'border-[#800020] text-[#800020]' : 'border-transparent text-gray-500'" class="pb-3 font-bold border-b-2 uppercase text-[0.85rem] tracking-widest transition hover:text-[#800020]">
                Product Description
            </button>
            <button @click="tab = 'specs'" :class="tab === 'specs' ? 'border-[#800020] text-[#800020]' : 'border-transparent text-gray-500'" class="pb-3 font-bold border-b-2 uppercase text-[0.85rem] tracking-widest transition hover:text-[#800020]">
                Specification & Dimension
            </button>
            <button @click="tab = 'care'" :class="tab === 'care' ? 'border-[#800020] text-[#800020]' : 'border-transparent text-gray-500'" class="pb-3 font-bold border-b-2 uppercase text-[0.85rem] tracking-widest transition hover:text-[#800020]">
                Care & Maintenance
            </button>
        </div>

        {{-- Tabs Content --}}
        <div class="w-full">
            <div x-show="tab === 'description'" class="text-gray-600 prose prose-sm max-w-none" style="font-family: 'Manrope', sans-serif;">
                {!! $product->description ?? 'No description available.' !!}
            </div>
            
            <div x-show="tab === 'specs'" x-cloak class="text-gray-600 prose prose-sm max-w-none" style="font-family: 'Manrope', sans-serif; display: none;" :style="tab === 'specs' ? 'display: block;' : 'display: none;'">
                {!! $product->specifications ?? 'No specifications available.' !!}
            </div>
            
            <div x-show="tab === 'care'" x-cloak class="text-gray-600 prose prose-sm max-w-none" style="font-family: 'Manrope', sans-serif; display: none;" :style="tab === 'care' ? 'display: block;' : 'display: none;'">
                {!! $product->care_instructions ?? 'Dry clean recommended.' !!}
            </div>
        </div>

    </div>

    {{-- UPDATED: BEAUTIFUL FULL WIDTH SIMILAR PRODUCTS SECTION --}}
    @if($similarProducts && $similarProducts->count() > 0)
        <div class="w-full mt-24 pt-16 border-t border-[#E5E0DA]">
            <div class="text-center mb-12">
                <h2 class="font-serif text-3xl md:text-4xl text-[#2A211F]">You May Also Like</h2>
            </div>
            
            {{-- Slider Container with Alpine.js --}}
            <div class="relative max-w-7xl mx-auto px-12 sm:px-16 lg:px-24 group" x-data="{
                scrollLeft() { $refs.slider.scrollBy({ left: -$refs.slider.clientWidth, behavior: 'smooth' }); },
                scrollRight() { $refs.slider.scrollBy({ left: $refs.slider.clientWidth, behavior: 'smooth' }); }
            }">
                
                {{-- Prev Button --}}
                <button @click="scrollLeft()" class="absolute left-0 lg:left-4 top-[35%] -translate-y-1/2 z-10 bg-white/90 shadow-md p-3 rounded-full text-gray-500 hover:text-[#800020] hover:bg-white transition hidden md:flex items-center justify-center cursor-pointer border border-[#E5E0DA]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </button>

                {{-- Scrollable Area --}}
                <div x-ref="slider" class="flex gap-4 md:gap-6 lg:gap-8 overflow-x-auto snap-x snap-mandatory pb-8" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        [x-ref="slider"]::-webkit-scrollbar { display: none; }
                    </style>
                    
                    {{-- Loop ALL similar products --}}
                    @foreach($similarProducts as $simProduct)
                        @php
                            $mainImg = is_array($simProduct->images) && count($simProduct->images) > 0
                                ? asset('storage/' . $simProduct->images[0])
                                : 'https://images.unsplash.com/photo-1610030469668-93510ec67d9e?auto=format&fit=crop&w=500';
                            $hoverImg = is_array($simProduct->images) && count($simProduct->images) > 1
                                ? asset('storage/' . $simProduct->images[1])
                                : $mainImg;
                        @endphp
                        
                        {{-- Sizing: 2 per row on mobile, 3 on tablet, 4 on large screens --}}
                        <div class="product-card group relative flex-none w-[calc(50%-8px)] md:w-[calc(33.333%-16px)] lg:w-[calc(25%-24px)] snap-start">
                            <a href="{{ route('shop.product', $simProduct->slug) }}" wire:navigate class="block w-full h-full no-underline">
                                
                                {{-- Image Wrapper with Hover Effects --}}
                                <div class="img-wrapper relative bg-[#F4F0EB] aspect-[3/4] overflow-hidden rounded-sm mb-4">
                                    {{-- Heart Icon (Appears on hover) --}}
                                    <div class="absolute top-3 right-3 z-10 bg-white p-2 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="transition: all 0.3s; {{ in_array($simProduct->id, \App\Services\WishlistService::getWishlistProductIds()) ? 'fill: #800020; color: #800020;' : 'fill: none; color: #706663;' }}">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
    </svg>
                                    </div>
                                    
                                    <img src="{{ $mainImg }}" alt="{{ $simProduct->name }}" class="main-img w-full h-full object-cover">
                                    <img src="{{ $hoverImg }}" alt="{{ $simProduct->name }} (Hover)" class="hover-img absolute inset-0 w-full h-full object-cover opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                
                                {{-- Text Info --}}
                                <div class="text-center">
                                    <h3 class="font-sans text-[0.85rem] font-semibold text-[#555] mb-2 line-clamp-2 min-h-[2.5rem]">
                                        {{ $simProduct->name }}
                                    </h3>
                                    <div class="flex flex-col items-center gap-1 mt-1">
                                        <div class="flex flex-wrap items-baseline justify-center gap-x-2 gap-y-1">
                                            <p class="font-sans text-base font-bold text-[#800020] m-0">
                                                Rs. {{ number_format($simProduct->current_price, 2) }}
                                            </p>
                                            @if($simProduct->original_price > $simProduct->current_price)
                                                <p class="text-gray-400 line-through text-sm m-0 font-normal">
                                                    Rs. {{ number_format($simProduct->original_price, 2) }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($simProduct->original_price > $simProduct->current_price)
                                            @php
                                                $discountPercent = round((($simProduct->original_price - $simProduct->current_price) / $simProduct->original_price) * 100);
                                            @endphp
                                            <span class="text-green-600 text-xs font-bold bg-green-50 px-2 py-0.5 rounded">({{ $discountPercent }}% OFF)</span>
                                        @endif
                                    </div>
                                </div>
                                
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Next Button --}}
                <button @click="scrollRight()" class="absolute right-0 lg:right-4 top-[35%] -translate-y-1/2 z-10 bg-white/90 shadow-md p-3 rounded-full text-gray-500 hover:text-[#800020] hover:bg-white transition hidden md:flex items-center justify-center cursor-pointer border border-[#E5E0DA]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    @endif

    {{-- REVIEWS SECTION (NEW) --}}
    <div id="reviews" class="w-full mt-24 pt-16 border-t border-[#E5E0DA]">
        <div class="text-center mb-8">
            <h2 class="font-serif text-3xl md:text-4xl text-[#2A211F]">Customer Reviews</h2>
        </div>
        
        {{-- Reviews Filter UI --}}
        @if($product->reviews && $product->reviews->count() > 0)
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                <button wire:click="$set('ratingFilter', null)" class="px-5 py-2 rounded-full text-sm font-bold border transition-colors {{ is_null($ratingFilter) ? 'bg-[#800020] text-white border-[#800020]' : 'bg-white text-gray-600 border-[#E5E0DA] hover:bg-gray-50' }}">
                    All
                </button>
                @for($i = 5; $i >= 1; $i--)
                    <button wire:click="$set('ratingFilter', {{ $i }})" class="px-4 py-2 rounded-full text-sm font-bold border transition-colors flex items-center gap-1.5 {{ $ratingFilter === $i ? 'bg-[#800020] text-white border-[#800020]' : 'bg-white text-gray-600 border-[#E5E0DA] hover:bg-gray-50' }}">
                        {{ $i }} <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 {{ $ratingFilter === $i ? 'text-yellow-400' : 'text-yellow-500' }} fill-current" viewBox="0 0 24 24" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    </button>
                @endfor
            </div>
        @endif
        
        <div class="max-w-4xl mx-auto" x-data="{
            isOpen: false,
            activeImage: '',
            review: null,
            init() {
                this.$watch('isOpen', value => {
                    if (value) {
                        this._scrollY = window.pageYOffset;
                        document.body.style.position = 'fixed';
                        document.body.style.top = '-' + this._scrollY + 'px';
                        document.body.style.width = '100%';
                        document.body.style.overflowY = 'scroll';
                    } else {
                        document.body.style.position = '';
                        document.body.style.top = '';
                        document.body.style.width = '';
                        document.body.style.overflowY = '';
                        window.scrollTo(0, this._scrollY);
                    }
                });
            },
            openModal(reviewData, startImage) {
                this.review = reviewData;
                this.activeImage = startImage;
                this.isOpen = true;
            },
            prevImage() {
                let currentIndex = this.review.photos.indexOf(this.activeImage);
                if (currentIndex > 0) {
                    this.activeImage = this.review.photos[currentIndex - 1];
                } else {
                    this.activeImage = this.review.photos[this.review.photos.length - 1];
                }
            },
            nextImage() {
                let currentIndex = this.review.photos.indexOf(this.activeImage);
                if (currentIndex < this.review.photos.length - 1) {
                    this.activeImage = this.review.photos[currentIndex + 1];
                } else {
                    this.activeImage = this.review.photos[0];
                }
            }
        }">
            

            {{-- Existing Reviews --}}
            @if($product->reviews && $product->reviews->count() > 0)
                @if($reviews->count() > 0)
                    <div class="space-y-8">
                        @foreach($reviews as $review)
                            <div class="border-b border-[#E5E0DA] pb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-[#1b1c1a]">{{ $review->customer->name ?? 'Guest User' }}</span>
                                    <div class="text-yellow-500 text-sm flex gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-500 fill-current' : 'text-gray-300 fill-current' }}" viewBox="0 0 24 24" stroke="none">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-xs text-gray-400 block mb-3">{{ $review->created_at->format('M d, Y') }}</span>
                                @if($review->comment)
                                    <p class="text-gray-600 text-sm leading-relaxed break-words" style="font-family: 'Manrope', sans-serif;">
                                        {{ $review->comment }}
                                    </p>
                                @endif
                                @if(is_array($review->photos) && count($review->photos) > 0)
                                    @php
                                        $reviewData = [
                                            'name' => $review->customer->name ?? 'Guest User',
                                            'rating' => $review->rating,
                                            'comment' => $review->comment,
                                            'photos' => array_map(function($p) { return asset('storage/' . $p); }, $review->photos)
                                        ];
                                    @endphp
                                    <div class="mt-4 flex gap-2 flex-wrap">
                                        @foreach($review->photos as $photo)
                                            <a href="#" 
                                               data-review="{{ json_encode($reviewData) }}"
                                               data-photo="{{ asset('storage/' . $photo) }}"
                                               @click.prevent="openModal(JSON.parse($el.dataset.review), $el.dataset.photo)" 
                                               class="block w-20 h-20 rounded overflow-hidden border border-[#E5E0DA] hover:opacity-80 transition cursor-pointer">
                                                <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                @if($review->admin_reply)
                                    <div class="mt-4 bg-[#F5F0EB] p-4 rounded-sm border-l-4 border-[#800020]">
                                        <span class="font-bold text-[#800020] text-sm block mb-1">Response from Alpha Digital</span>
                                        <p class="text-gray-700 text-sm leading-relaxed" style="font-family: 'Manrope', sans-serif;">
                                            {{ $review->admin_reply }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 italic mb-4">No {{ $ratingFilter }}-star reviews found for this product.</p>
                        <button wire:click="$set('ratingFilter', null)" class="text-[#800020] font-bold text-sm hover:underline">View all reviews</button>
                    </div>
                @endif
            @else
                <p class="text-center text-gray-500 italic">No reviews yet. Be the first to review this product!</p>
            @endif

            {{-- Review Image Lightbox Modal --}}
            <template x-teleport="body">
                <div x-show="isOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 md:p-10" style="display: none;">
                    <div class="absolute inset-0 bg-black bg-opacity-80" @click="isOpen = false"></div>
                    
                    <div class="relative z-10 w-full max-w-6xl h-[85vh] flex flex-col md:flex-row bg-white rounded-lg shadow-2xl overflow-hidden animate-fade-in-up">
                        <button @click="isOpen = false" class="absolute top-4 right-4 z-20 text-gray-400 hover:text-black transition bg-white border border-gray-200 cursor-pointer p-2 rounded-full shadow-md hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <!-- Left: Large Image Area -->
                        <div class="w-full md:w-[60%] bg-[#F5F0EB] flex items-center justify-center p-4 relative group">
                            <!-- Prev Arrow -->
                            <button x-show="review && review.photos && review.photos.length > 1" @click="prevImage()" class="absolute left-4 z-20 text-[#800020] hover:text-white bg-white hover:bg-[#800020] transition border border-[#E5E0DA] cursor-pointer p-3 rounded-full shadow-md opacity-0 group-hover:opacity-100 focus:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            
                            <img :src="activeImage" class="max-w-full max-h-full object-contain mix-blend-multiply drop-shadow-lg">
                            
                            <!-- Next Arrow -->
                            <button x-show="review && review.photos && review.photos.length > 1" @click="nextImage()" class="absolute right-4 z-20 text-[#800020] hover:text-white bg-white hover:bg-[#800020] transition border border-[#E5E0DA] cursor-pointer p-3 rounded-full shadow-md opacity-0 group-hover:opacity-100 focus:opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>

                        <!-- Right: Review Details -->
                        <div class="w-full md:w-[40%] p-6 md:p-8 flex flex-col h-full overflow-y-auto bg-white">
                            <h3 class="font-bold text-lg text-gray-900 mb-6 border-b border-gray-100 pb-3">Customer photos and review</h3>
                            
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                                    <span x-text="review.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <span class="font-bold text-[#1b1c1a]" x-text="review.name"></span>
                            </div>

                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex gap-0.5">
                                    <template x-for="i in 5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current" :class="i <= review.rating ? 'text-[#FF9900]' : 'text-gray-300'" viewBox="0 0 24 24" stroke="none">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-[13px] font-bold text-[#C45500]">Verified Purchase</span>
                            </div>

                            <p class="text-gray-700 text-[14px] leading-relaxed mb-6 whitespace-pre-wrap break-words" x-text="review.comment" style="font-family: 'Manrope', sans-serif;"></p>

                            <!-- Thumbnails Gallery -->
                            <div class="mt-auto pt-6">
                                <div class="flex gap-2 flex-wrap">
                                    <template x-for="photo in review.photos">
                                        <button @click="activeImage = photo" 
                                                class="w-16 h-16 rounded overflow-hidden border-2 transition focus:outline-none cursor-pointer"
                                                :class="activeImage === photo ? 'border-[#e77600] shadow-sm' : 'border-transparent opacity-70 hover:opacity-100 hover:border-gray-300'">
                                            <img :src="photo" class="w-full h-full object-cover">
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</main>