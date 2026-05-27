<x-layouts.app>
    <section class="hero">
    <div class="hero-slides">
        @php 
            $totalSlides = $carousels->count(); 
            $animationDuration = $totalSlides > 0 ? $totalSlides * 5 : 5;
        @endphp

        @foreach($carousels as $index => $carousel)
            <div class="slide" 
                 style="background-image: url('{{ asset("storage/" . $carousel->image) }}');
                        animation: fadeHero {{ $animationDuration }}s infinite;
                        animation-delay: {{ $index * 5 }}s;
                        display: flex; align-items: center; justify-content: flex-start; padding: 0 10%;">
                
                <div class="hero-content" style="position: relative; z-index: 2;">
                    
                    @if($carousel->sub_heading)
                        <p class="subtitle" style="color: white;">{{ $carousel->sub_heading }}</p>
                    @endif

                    @if($carousel->heading)
                        <h1>{{ $carousel->heading }}</h1>
                    @endif

                    @if($carousel->text)
                        <p style="margin-bottom: 2rem; max-width: 500px; line-height: 1.6;">{{ $carousel->text }}</p>
                    @endif

                    @if($carousel->button_text && $carousel->button_link)
                        <a href="{{ $carousel->button_link }}" class="btn-primary" style="display: inline-block; text-decoration: none;">
                            {{ $carousel->button_text }}
                        </a>
                    @endif

                </div>
            </div>
        @endforeach
        
        @if($totalSlides === 0)
            <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80'); opacity: 1;">
                <div class="hero-content" style="position: relative; z-index: 2;">
                    <h1>Welcome to Our Store</h1>
                    <a href="/shop" class="btn-primary" style="display: inline-block; text-decoration: none;">SHOP NOW</a>
                </div>
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
                    <p>₹{{ number_format($product->current_price, 2) }}</p>
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
                    <p>₹{{ number_format($product->current_price, 2) }}</p>
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
        function fixCarouselClicks() {
            // Check the opacity of the slides every 100 milliseconds
            setInterval(() => {
                const slides = document.querySelectorAll('.hero-slides .slide');
                slides.forEach(slide => {
                    // Get the current opacity from the CSS animation
                    const opacity = parseFloat(window.getComputedStyle(slide).opacity);
                    
                    // If the slide is visible, allow clicks. If it's fading out/invisible, disable clicks.
                    if (opacity > 0.1) {
                        slide.style.pointerEvents = 'auto';
                    } else {
                        slide.style.pointerEvents = 'none';
                    }
                });
            }, 100);
        }

        // Run the fix when the page loads
        document.addEventListener('DOMContentLoaded', fixCarouselClicks);
        document.addEventListener('livewire:navigated', fixCarouselClicks);
    </script>
</x-layouts.app>