<x-layouts.app>
    <section class="hero bg-black" 
             x-data="{
                 activeSlide: 0,
                 visibleSlide: 0,
                 totalSlides: {{ $carousels->count() }},
                 autoPlayInterval: null,
                 touchStartX: 0,
                 transitionTimeout1: null,
                 
                 init() {
                     if(this.totalSlides <= 1) return;
                     this.startAutoPlay();
                 },
                 startAutoPlay() {
                     this.autoPlayInterval = setInterval(() => { this.next() }, 5000);
                 },
                 resetAutoPlay() {
                     clearInterval(this.autoPlayInterval);
                     this.startAutoPlay();
                 },
                 next() {
                     if(this.totalSlides <= 1) return;
                     this.changeSlide((this.activeSlide + 1) % this.totalSlides);
                 },
                 prev() {
                     if(this.totalSlides <= 1) return;
                     this.changeSlide((this.activeSlide - 1 + this.totalSlides) % this.totalSlides);
                 },
                 goTo(index) {
                     if(this.activeSlide === index) return;
                     this.changeSlide(index);
                 },
                 changeSlide(newIndex) {
                     // Clear any pending transitions so manual clicks are instant
                     clearTimeout(this.transitionTimeout1);
                     
                     this.activeSlide = newIndex;
                     this.resetAutoPlay();
                     
                     // Fade out current slide
                     this.visibleSlide = null;
                     
                     // Wait for fade out + short black screen, then fade in new slide
                     this.transitionTimeout1 = setTimeout(() => {
                         this.visibleSlide = newIndex;
                     }, 450); // 400ms fade out + 50ms black
                 },
                 handleTouchStart(e) {
                     this.touchStartX = e.touches[0].clientX;
                 },
                 handleTouchEnd(e) {
                     const touchEndX = e.changedTouches[0].clientX;
                     const diff = this.touchStartX - touchEndX;
                     
                     if (Math.abs(diff) > 50) {
                         if (diff > 0) {
                             this.next();
                         } else {
                             this.prev();
                         }
                     }
                 }
             }">
    <div class="hero-slides w-full h-full relative group" 
         @touchstart="handleTouchStart" 
         @touchend="handleTouchEnd">
        
        @foreach($carousels as $index => $carousel)
            <div class="slide flex items-center justify-center text-center md:justify-start md:text-left px-6 md:px-[10%] transition-opacity duration-[400ms] ease-in-out" 
                 :class="{ '!opacity-100 z-10 pointer-events-auto': visibleSlide === {{ $index }}, '!opacity-0 z-0 pointer-events-none': visibleSlide !== {{ $index }} }"
                 style="background-image: url('{{ asset("storage/" . $carousel->image) }}');">
                
                <div class="hero-content relative z-[2] w-full max-w-[500px] transition-all duration-[600ms] ease-out"
                     :class="{ 'translate-y-0 opacity-100 delay-[200ms]': visibleSlide === {{ $index }}, 'translate-y-8 opacity-0 delay-0': visibleSlide !== {{ $index }} }">
                    
                    @if($carousel->sub_heading)
                        <p class="subtitle text-sm md:text-base text-white tracking-widest mb-2">{{ $carousel->sub_heading }}</p>
                    @endif

                    @if($carousel->heading)
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-normal my-2 md:my-4 leading-tight drop-shadow-md text-white">{{ $carousel->heading }}</h1>
                    @endif

                    @if($carousel->text)
                        <p class="text-sm md:text-base mb-6 md:mb-8 leading-relaxed text-white">{{ $carousel->text }}</p>
                    @endif

                    @if($carousel->button_text && $carousel->button_link)
                        <a href="{{ $carousel->button_link }}" class="btn-primary inline-block no-underline">
                            {{ $carousel->button_text }}
                        </a>
                    @endif

                </div>
            </div>
        @endforeach
        
        @if($carousels->count() === 0)
            <div class="slide flex items-center justify-center text-center md:justify-start md:text-left px-6 md:px-[10%] transition-opacity duration-[600ms] !opacity-100 z-10" style="background-image: url('https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80');">
                <div class="hero-content relative z-[2] w-full max-w-[500px]">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-normal my-2 md:my-4 leading-tight drop-shadow-md text-white">Welcome to Our Store</h1>
                    <a href="/shop" class="btn-primary inline-block no-underline mt-4">SHOP NOW</a>
                </div>
            </div>
        @endif

        @if($carousels->count() > 1)
            <!-- Navigation Arrows -->
            <button @click.prevent="prev()" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 md:w-14 md:h-14 rounded-full border border-white/30 text-white/70 hover:border-white hover:text-white hover:bg-white/10 transition-all duration-300 opacity-0 group-hover:opacity-100 backdrop-blur-sm hidden sm:flex items-center justify-center">
                <svg class="w-5 h-5 md:w-6 md:h-6 ml-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click.prevent="next()" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-20 w-12 h-12 md:w-14 md:h-14 rounded-full border border-white/30 text-white/70 hover:border-white hover:text-white hover:bg-white/10 transition-all duration-300 opacity-0 group-hover:opacity-100 backdrop-blur-sm hidden sm:flex items-center justify-center">
                <svg class="w-5 h-5 md:w-6 md:h-6 mr-[-2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Dot Indicators -->
            <div class="absolute bottom-6 md:bottom-10 left-1/2 -translate-x-1/2 z-20 flex items-center justify-center gap-1 md:gap-2">
                @foreach($carousels as $index => $carousel)
                    <button @click.prevent="goTo({{ $index }})" 
                            aria-label="Go to slide {{ $index + 1 }}"
                            class="group p-2 flex items-center justify-center focus:outline-none">
                        <span class="h-1.5 md:h-2 rounded-full transition-all duration-500 ease-out shadow-sm"
                              :class="activeSlide === {{ $index }} ? 'w-8 md:w-10 bg-white' : 'w-1.5 md:w-2 bg-white/40 group-hover:bg-white/70'">
                        </span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>
    </section>

    <section class="content-section bg-neutral">
        <div class="section-header">
            <div>
                <p class="subtitle">TIMELESS FAVORITES</p>
                <h2>Best Sellers</h2>
            </div>
            <a href="{{ route('shop.index', ['filter' => 'best_seller']) }}" class="view-all">EXPLORE ALL</a>
        </div>
        <div class="product-grid">
            @forelse($bestSellers as $product)
                <div class="product-card">
                    <a href="{{ route('shop.product', $product->slug) }}" class="block">
                        <div class="img-wrapper">
                            @php
                                $mainImg = is_array($product->images) && count($product->images) > 0 
                                    ? asset('storage/' . $product->images[0]) 
                                    : 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80';
                                $hoverImg = is_array($product->images) && count($product->images) > 1 
                                    ? asset('storage/' . $product->images[1]) 
                                    : $mainImg;
                            @endphp
                            <img src="{{ $mainImg }}" alt="{{ $product->name }}" class="main-img">
                            <img src="{{ $hoverImg }}" alt="{{ $product->name }} (Hover)" class="hover-img">
                        </div>
                        <h3>{{ $product->name }}</h3>
                    </a>
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
                </div>
            @empty
                <p class="text-gray-500 italic col-span-full">Add products to your admin panel and mark them as "Best Seller" to see them here.</p>
            @endforelse
        </div>
    </section>

    <section class="content-section bg-white">
        <div class="section-header">
            <div>
                <p class="subtitle">THE NEW ETHEREAL</p>
                <h2>Latest Collection</h2>
            </div>
            <a href="{{ route('shop.new-arrival') }}" class="view-all">VIEW COLLECTION</a>
        </div>
        <div class="product-grid">
            @forelse($latestCollection as $product)
                <div class="product-card">
                    <a href="{{ route('shop.product', $product->slug) }}" class="block">
                        <div class="img-wrapper">
                            @php
                                $mainImg = is_array($product->images) && count($product->images) > 0 
                                    ? asset('storage/' . $product->images[0]) 
                                    : 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80';
                                $hoverImg = is_array($product->images) && count($product->images) > 1 
                                    ? asset('storage/' . $product->images[1]) 
                                    : $mainImg;
                            @endphp
                            <img src="{{ $mainImg }}" alt="{{ $product->name }}" class="main-img">
                            <img src="{{ $hoverImg }}" alt="{{ $product->name }} (Hover)" class="hover-img">
                        </div>
                        <h3>{{ $product->name }}</h3>
                    </a>
                    <div class="flex flex-wrap items-baseline justify-center gap-x-2 gap-y-1 mt-1 mb-2">
                        <p class="font-bold text-[#800020] m-0 text-sm whitespace-nowrap">₹{{ number_format($product->current_price, 2) }}</p>
                        @if($product->original_price > $product->current_price)
                            <p class="text-gray-400 line-through text-xs m-0 font-normal whitespace-nowrap" style="color: #9ca3af !important;">₹{{ number_format($product->original_price, 2) }}</p>
                            @php
                                $discountPercent = round((($product->original_price - $product->current_price) / $product->original_price) * 100);
                            @endphp
                            <span class="text-green-600 text-[10px] font-bold whitespace-nowrap">({{ $discountPercent }}% OFF)</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic col-span-full">Add products to your admin panel and mark them as "New Arrival" to see them here.</p>
            @endforelse
        </div>
    </section>

    <section class="content-section bg-neutral">
        <div class="section-header">
            <div>
                <p class="subtitle">OUR TEXTURED HERITAGE</p>
                <h2>Fabrics</h2>
            </div>
        </div>

        @php
            // Fetch exactly up to 4 fabrics that are marked as featured AND have an image uploaded
            $featuredFabrics = \App\Models\Fabric::where('is_featured', true)
                                ->whereNotNull('image')
                                ->take(4)
                                ->get();
        @endphp

        @if($featuredFabrics->count() > 0)
            <div class="fabrics-grid">
                
                @if(isset($featuredFabrics[0]))
                    <div class="fab-large">
                        <div class="fab-img" style="background-image: url('{{ asset('storage/' . $featuredFabrics[0]->image) }}');">
                            <button class="label">{{ strtoupper($featuredFabrics[0]->name) }}</button>
                        </div>
                    </div>
                @endif

                <div class="fab-sidebar">
                    
                    @if(isset($featuredFabrics[1]))
                        @php $fab1Url = asset('storage/' . $featuredFabrics[1]->image); @endphp
                        <div class="fab-img" style="background-image: url('{{ $fab1Url }}');">
                            <button class="label">{{ strtoupper($featuredFabrics[1]->name) }}</button>
                        </div>
                    @endif

                    <div class="fab-bottom-row">
                        @if(isset($featuredFabrics[2]))
                            <div class="fab-img" style="background-image: url('{{ asset('storage/' . $featuredFabrics[2]->image) }}');">
                                <button class="label">{{ strtoupper($featuredFabrics[2]->name) }}</button>
                            </div>
                        @endif
                        
                        @if(isset($featuredFabrics[3]))
                            <div class="fab-img" style="background-image: url('{{ asset('storage/' . $featuredFabrics[3]->image) }}');">
                                <button class="label">{{ strtoupper($featuredFabrics[3]->name) }}</button>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        @else
            <p class="text-gray-500 italic text-center py-10">Add fabric images in the admin panel and mark them as "Feature on Homepage" to see them here.</p>
        @endif
    </section>

    <section class="heritage-crafted">
        <div class="heritage-container">
            <div class="heritage-text">
                <p class="subtitle">ESTABLISHED 1994</p>
                <h2>The thread that connects generations.</h2>
                <p class="heritage-description">
                    Founded on the principles of preserving traditional Indian handlooms, ALMAARI brings you curated handlooms that tell a story of artisanal mastery and timeless elegance. Each weave is a testament to the hands that crafted it.
                </p>
                <div class="heritage-cta">
                    <a href="{{ route('shop.about') }}" wire:navigate class="btn-heritage inline-block text-center" style="line-height: inherit; text-decoration: none;">OUR JOURNEY</a>
                    <div class="craft-mark">
                        <span class="gold-text">Handcrafted with love</span>
                    </div>
                </div>
            </div>

            <div class="heritage-visual">
                <div class="main-image-frame">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" class="heritage-main-img" alt="Artisan Weaver">
                </div>
                <div class="overlap-image-frame">
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" class="heritage-sub-img" alt="Loom Detail">
                </div>
                <div class="heritage-accent-box"></div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-section">
        <div class="contact-container">
            <div class="contact-grid">
                
                @php
                    $settings = \App\Models\Setting::getSiteSettings();
                @endphp
                
                <div class="contact-info">
                    <div class="contact-underlap">CONTACT</div>
                    
                    <p class="subtitle">GET IN TOUCH</p>
                    <h2>We'd love to hear from you.</h2>
                    
                    <div class="contact-details">
                        @if($settings && $settings->contact_address)
                            <div class="detail-item">
                                <h4>THE SHOWROOM</h4>
                                <p>{!! nl2br(e($settings->contact_address)) !!}</p>
                            </div>
                        @endif
                        
                        @if($settings && ($settings->contact_email || $settings->contact_phone))
                            <div class="detail-item">
                                <h4>ASSISTANCE</h4>
                                <p>
                                    @if($settings->contact_email)
                                        {{ $settings->contact_email }}
                                    @endif
                                    
                                    @if($settings->contact_email && $settings->contact_phone)
                                        <br>
                                    @endif
                                    
                                    @if($settings->contact_phone)
                                        {{ $settings->contact_phone }}
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    
                </div>

<!-- Contact Form Livewire Component -->
                <livewire:contact-form />

            </div>
        </div>
    </section>

    {{-- Carousel script removed as it is now handled by Alpine.js --}}
</x-layouts.app>