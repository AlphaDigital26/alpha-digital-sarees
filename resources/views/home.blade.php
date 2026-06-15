<x-layouts.app>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <section class="hero w-full relative" style="height: auto; aspect-ratio: 16/9; min-height: 400px; max-height: 80vh; background-color: #000;">
        <div class="swiper hero-swiper h-full w-full" style="background-color: #000;">
            <div class="swiper-wrapper">
                @php $totalSlides = $carousels->count(); @endphp
                
                @foreach($carousels as $carousel)
                    <div class="swiper-slide overflow-hidden relative" style="background-color: transparent;">
                        <!-- Background image with zoom effect -->
                        <div class="hero-bg absolute inset-0 w-full h-full bg-no-repeat" 
                             style="background-image: url('{{ asset("storage/" . $carousel->image) }}'); background-size: cover; background-position: top center;">
                        </div>
                        
                        <!-- Overlay -->
                        <div class="absolute inset-0 bg-black/40 z-[1]"></div>
                        
                        <!-- Content -->
                        <div class="relative z-[2] h-full w-full flex items-center justify-center text-center md:justify-start md:text-left px-12 md:px-[10%]">
                            <div class="hero-content w-full max-w-[600px]">
                                @if($carousel->sub_heading)
                                    <p class="subtitle text-sm md:text-base text-white tracking-widest mb-3" data-swiper-parallax="-100" data-swiper-parallax-opacity="0" data-swiper-parallax-duration="1000">{{ $carousel->sub_heading }}</p>
                                @endif

                                @if($carousel->heading)
                                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-normal my-4 leading-tight drop-shadow-lg text-white" data-swiper-parallax="-200" data-swiper-parallax-opacity="0" data-swiper-parallax-duration="1200">{{ $carousel->heading }}</h1>
                                @endif

                                @if($carousel->text)
                                    <p class="text-base md:text-lg mb-8 leading-relaxed text-white drop-shadow-md" data-swiper-parallax="-300" data-swiper-parallax-opacity="0" data-swiper-parallax-duration="1400">{{ $carousel->text }}</p>
                                @endif

                                @if($carousel->button_text && $carousel->button_link)
                                    <div data-swiper-parallax="-400" data-swiper-parallax-opacity="0" data-swiper-parallax-duration="1600">
                                        <a href="{{ $carousel->button_link }}" class="btn-primary inline-block no-underline px-8 py-3 text-lg">
                                            {{ $carousel->button_text }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                
                @if($totalSlides === 0)
                    <div class="swiper-slide overflow-hidden relative" style="background-color: transparent;">
                        <div class="hero-bg absolute inset-0 w-full h-full bg-no-repeat" style="background-image: url('https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80'); background-size: cover; background-position: top center;"></div>
                        <div class="absolute inset-0 bg-black/30 z-[1]"></div>
                        <div class="relative z-[2] h-full w-full flex items-center justify-center text-center md:justify-start md:text-left px-12 md:px-[10%]">
                            <div class="hero-content w-full max-w-[600px]">
                                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-normal my-4 leading-tight drop-shadow-lg text-white" data-swiper-parallax="-200">Welcome to Our Store</h1>
                                <div data-swiper-parallax="-400">
                                    <a href="/shop" class="btn-primary inline-block no-underline px-8 py-3 text-lg mt-4">SHOP NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Navigation Buttons Removed -->

            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <style>
        /* Premium Fade to Black Transition */
        .hero-swiper .swiper-slide {
            opacity: 0 !important;
            transition: opacity 0.8s ease-in-out 0s !important;
            pointer-events: none;
        }
        .hero-swiper .swiper-slide.swiper-slide-active {
            opacity: 1 !important;
            transition: opacity 0.8s ease-in-out 1.1s !important;
            pointer-events: auto;
        }

        /* Ken Burns Effect using Swiper active class */
        .hero-bg {
            transform: scale(1.05);
            transition: transform 8s ease-out;
        }
        .swiper-slide-active .hero-bg {
            transform: scale(1);
        }
        
        /* Swiper Pagination Styling - Enhanced & Prominent */
        .swiper-pagination {
            bottom: 20px !important;
        }
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.4);
            border: 2px solid #ffffff;
            opacity: 1;
            transition: all 0.4s ease;
            cursor: pointer;
        }
        .swiper-pagination-bullet-active {
            background: #ffffff;
            width: 32px;
            border-radius: 6px;
            transform: none;
        }

        /* Grab cursor for desktop drag-to-swipe */
        .hero-swiper {
            cursor: grab;
        }
        .hero-swiper:active {
            cursor: grabbing;
        }
    </style>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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
                    <a href="{{ route('shop.product', $product->id) }}" class="block">
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
                    <a href="{{ route('shop.product', $product->id) }}" class="block">
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
                    <button class="btn-heritage">OUR JOURNEY</button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initSwiper();
        });
        document.addEventListener('livewire:navigated', function() {
            initSwiper();
        });

        function initSwiper() {
            if(typeof Swiper !== 'undefined') {
                const swiperEl = document.querySelector('.hero-swiper');
                if (swiperEl && !swiperEl.swiper) {
                    new Swiper('.hero-swiper', {
                        effect: 'fade',
                        fadeEffect: { crossFade: true },
                        speed: 1900, // 0.8s fade out + 0.3s black hold + 0.8s fade in
                        parallax: true,
                        loop: true,
                        simulateTouch: true,   // Enable touch/swipe on mobile & tablet
                        grabCursor: true,      // Show grab cursor on desktop drag
                        touchRatio: 1,
                        touchAngle: 45,
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                    });
                }
            }
        }
    </script>
</x-layouts.app>