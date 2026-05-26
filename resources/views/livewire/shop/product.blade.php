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
                         class="h-7 w-7 transition-colors duration-300 {{ in_array($product->id, session()->get('wishlist', [])) ? 'fill-[#800020] text-[#800020]' : 'fill-none text-gray-400 hover:text-[#800020]' }}" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor" 
                         stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
            </div>

            {{-- UPDATED PRICE SECTION --}}
            <div class="mt-4 mb-4 flex items-baseline gap-3">
                <p class="price text-2xl font-bold text-[#800020] leading-none m-0 p-0">
                    Rs. {{ number_format($product->current_price, 2) }}
                </p>

                @if($product->original_price > $product->current_price)
                    @php 
                        $discount = round((($product->original_price - $product->current_price) / $product->original_price) * 100); 
                    @endphp
                    <p class="text-sm text-gray-400 line-through m-0">
                        Rs. {{ number_format($product->original_price, 2) }}
                    </p>
                    <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">
                        {{ $discount }}% OFF
                    </span>
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
            
            {{-- Changed to lg:grid-cols-4 and added max-w-6xl mx-auto to perfectly size the cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8 max-w-6xl mx-auto">
                {{-- Loop up to 4 products --}}
                @foreach($similarProducts->take(4) as $simProduct)
                    @php
                        $simImg = is_array($simProduct->images) && count($simProduct->images) > 0
                            ? asset('storage/' . $simProduct->images[0])
                            : 'https://images.unsplash.com/photo-1610030469668-93510ec67d9e?auto=format&fit=crop&w=500';
                    @endphp
                    
                    <div class="product-card group relative">
                        <a href="{{ route('shop.product', $simProduct->id) }}" wire:navigate class="block w-full h-full no-underline">
                            
                            {{-- Image Wrapper with Hover Effects --}}
                            <div class="img-wrapper relative bg-[#F4F0EB] aspect-[3/4] overflow-hidden rounded-sm mb-4">
                                {{-- Heart Icon (Appears on hover) --}}
                                <div class="absolute top-3 right-3 z-10 bg-white p-2 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                                
                                <img src="{{ $simImg }}" alt="{{ $simProduct->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            
                            {{-- Text Info --}}
                            <div class="text-center">
                                <h3 class="font-sans text-[0.85rem] font-semibold text-[#555] mb-2 line-clamp-2 min-h-[2.5rem]">
                                    {{ $simProduct->name }}
                                </h3>
                                <p class="font-sans text-[0.9rem] font-bold text-[#800020]">
                                    Rs. {{ number_format($simProduct->current_price, 2) }}
                                </p>
                            </div>
                            
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- REVIEWS SECTION (NEW) --}}
    <div class="w-full mt-24 pt-16 border-t border-[#E5E0DA]">
        <div class="text-center mb-12">
            <h2 class="font-serif text-3xl md:text-4xl text-[#2A211F]">Customer Reviews</h2>
        </div>
        
        <div class="max-w-4xl mx-auto">
            @if($product->reviews && $product->reviews->count() > 0)
                <div class="space-y-8">
                    @foreach($product->reviews as $review)
                        <div class="border-b border-[#E5E0DA] pb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-bold text-[#1b1c1a]">{{ $review->user_name }}</span>
                                <div class="text-yellow-500 text-sm">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed" style="font-family: 'Manrope', sans-serif;">
                                {{ $review->comment }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500 italic">No reviews yet. Be the first to review this product!</p>
            @endif
        </div>
    </div>
</main>