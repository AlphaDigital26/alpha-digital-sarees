<!-- <div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
</div> -->

<main class="occasion-container">
    <div class="page-header">
        <p class="subtitle">CURATED COLLECTIONS</p>
        <h1>Shop by Occasion</h1>
    </div>

    <!-- ROW 1: TIRANGA -->
    <div class="occasion-row">
        <!-- The Large Feature Card -->
        <div class="occ-feature-card">
            <img src="https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80" alt="Tiranga" class="occ-feature-img">
            <div class="occ-overlay">
                <h2>Tiranga</h2>
                <a href="{{ route('shop.index', ['filter' => 'tiranga']) }}" class="occ-btn">SHOP NOW</a>
            </div>
        </div>
        
        <!-- The Slider Section -->
        <div class="slider-wrapper">
            <!-- Left Arrow: Uses simple JS to scroll container by 350px -->
            <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: -350, behavior: 'smooth'})" class="slider-btn left">
                <i data-lucide="chevron-left"></i>
            </button>

            <!-- Scrollable Container -->
            <div class="occ-slider-container">
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Saffron Chanderi</h3>
                    <p>₹18,500</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1544441893-675973eebb39?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Ivory Pure Silk</h3>
                    <p>₹22,000</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Emerald Banarasi</h3>
                    <p>₹35,000</p>
                </div>
                <!-- Extra cards to enable scrolling -->
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Verdant Tussar</h3>
                    <p>₹16,500</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1595967783875-c371f35d8049?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Pearl White Cotton</h3>
                    <p>₹12,000</p>
                </div>
            </div>

            <!-- Right Arrow -->
            <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: 350, behavior: 'smooth'})" class="slider-btn right">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- ROW 2: NAVRATRI SPECIAL -->
    <div class="occasion-row">
        <!-- The Large Feature Card -->
        <div class="occ-feature-card">
            <img src="https://images.unsplash.com/photo-1610030469915-055106670868?auto=format&fit=crop&q=80" alt="Navratri" class="occ-feature-img">
            <div class="occ-overlay">
                <h2>Navratri Special</h2>
                <a href="{{ route('shop.index', ['filter' => 'navratri']) }}" class="occ-btn">SHOP NOW</a>
            </div>
        </div>
        
        <!-- The Slider Section -->
        <div class="slider-wrapper">
            <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: -350, behavior: 'smooth'})" class="slider-btn left">
                <i data-lucide="chevron-left"></i>
            </button>

            <div class="occ-slider-container">
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Crimson Bandhani</h3>
                    <p>₹45,500</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1595967783875-c371f35d8049?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Marigold Tissue</h3>
                    <p>₹28,000</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Midnight Silk</h3>
                    <p>₹32,200</p>
                </div>
                <!-- Extra cards to enable scrolling -->
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Golden Zari Weave</h3>
                    <p>₹21,500</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1544441893-675973eebb39?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Sunset Organza</h3>
                    <p>₹18,000</p>
                </div>
            </div>

            <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: 350, behavior: 'smooth'})" class="slider-btn right">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>

    <!-- ROW 3: BRIDAL & WEDDING -->
    <div class="occasion-row">
        <!-- The Large Feature Card -->
        <div class="occ-feature-card">
            <img src="https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&q=80" alt="Wedding" class="occ-feature-img">
            <div class="occ-overlay">
                <h2>Bridal & Wedding</h2>
                <a href="{{ route('shop.index', ['filter' => 'wedding']) }}" class="occ-btn">SHOP NOW</a>
            </div>
        </div>
        
        <!-- The Slider Section -->
        <div class="slider-wrapper">
            <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: -350, behavior: 'smooth'})" class="slider-btn left">
                <i data-lucide="chevron-left"></i>
            </button>

            <div class="occ-slider-container">
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Ruby Red Kanchipuram</h3>
                    <p>₹85,000</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1544441893-675973eebb39?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Gold Zari Katan</h3>
                    <p>₹62,000</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1528459801416-a9e53bbf4e17?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Rose Organza</h3>
                    <p>₹41,500</p>
                </div>
                <!-- Extra cards to enable scrolling -->
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Royal Maroon Banarasi</h3>
                    <p>₹95,000</p>
                </div>
                <div class="product-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1595967783875-c371f35d8049?auto=format&fit=crop&q=80" alt="Saree">
                        <button class="wishlist-btn"><i data-lucide="heart"></i></button>
                    </div>
                    <h3>Ivory Bridal Tissue</h3>
                    <p>₹55,000</p>
                </div>
            </div>

            <button onclick="this.parentElement.querySelector('.occ-slider-container').scrollBy({left: 350, behavior: 'smooth'})" class="slider-btn right">
                <i data-lucide="chevron-right"></i>
            </button>
        </div>
    </div>
</main>