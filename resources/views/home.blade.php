<x-layouts.app>

<!-- NAVBAR -->


<!-- HERO -->
<section class="hero">
    <div class="hero-slides">
        <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80');"></div>
        <div class="slide" style="background-image: url('https://static.wixstatic.com/media/ef7393_12cb531a01cb4dfcadc4b1b1ac5e599f~mv2.jpg');"></div>
        <div class="slide" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRFV6eMgrKiFEjW3xfhIuWOP58j5fwWw0tIpA&s');"></div>
    </div>

    <div class="hero-content">
        <p class="subtitle">SPRING SUMMER 2026</p>
        <h1>The Heirloom Collection</h1>
        <a href="/shop">
            <button class="btn-primary">DISCOVER</button>
        </a>
    </div>
</section>

<!-- BEST SELLERS -->
<section class="content-section bg-neutral">
    <div class="section-header">
        <div>
            <p class="subtitle">TIMELESS FAVORITES</p>
            <h2>Best Sellers</h2>
        </div>
        <a href="/shop" class="view-all">EXPLORE ALL</a>
    </div>

    <div class="product-grid">
        @foreach(\App\Models\Product::latest()->take(4)->get() as $product)
            <a href="/product/{{ $product->id }}">
                <div class="product-card">

                    <div class="img-wrapper">
                        <img 
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x500' }}" 
                            alt="{{ $product->name }}">
                    </div>

                    <h3>{{ $product->name }}</h3>
                    <p>₹{{ $product->price }}</p>

                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- LATEST COLLECTION -->
<section class="content-section bg-white">

    <div class="section-header">
        <div>
            <p class="subtitle">NEW ARRIVALS</p>
            <h2>Latest Collection</h2>
        </div>
        <a href="/shop" class="view-all">EXPLORE ALL</a>
    </div>

    <div class="product-grid">

        @foreach(\App\Models\Product::latest()->skip(4)->take(4)->get() as $product)
            <a href="/product/{{ $product->id }}">
                <div class="product-card">

                    <div class="img-wrapper">
                        <img 
                            src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/400x500' }}" 
                            alt="{{ $product->name }}">
                    </div>

                    <h3>{{ $product->name }}</h3>
                    <p>₹{{ $product->price }}</p>

                </div>
            </a>
        @endforeach

    </div>

</section>

<!-- FABRICS -->
<section class="fabrics-section">
    <div class="section-header">
        <h2>Fabrics</h2>
    </div>

    <div class="fabrics-grid">
        <div class="fab-img" style="background-image: url('https://images.unsplash.com/photo-1610030469983-98e550d6193c');">
            <button class="label">BANARASI</button>
        </div>

        <div class="fab-sidebar">
            <div class="fab-img" style="background-image: url('https://images.unsplash.com/photo-1603251579431-8041402bdeda');">
                <button class="label">KANCHIPURAM</button>
            </div>

            <div class="fab-bottom-row">
                <div class="fab-img" style="background-image: url('https://images.unsplash.com/photo-1593030761757-71fae45fa0e7');">
                    <button class="label">CHANDERI</button>
                </div>

                <div class="fab-img" style="background-image: url('https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf');">
                    <button class="label">ORGANZA</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HERITAGE -->
<section class="heritage-crafted">
    <div class="heritage-container">

        <div class="heritage-text">
            <p class="subtitle">OUR LEGACY</p>
            <h2>Crafted by Tradition,<br>Designed for Legacy</h2>

            <p class="heritage-description">
                Since 1994, ALMAARI has preserved the artistry of Indian handlooms.
            </p>

            <div class="heritage-cta">
                <button class="btn-heritage">OUR STORY</button>
                <span class="gold-text">EST. 1994</span>
            </div>
        </div>

        <div class="heritage-visual">
            <div class="main-image-frame">
                <img src="https://images.unsplash.com/photo-1594736797933-d0401ba2fe65" class="heritage-main-img">
            </div>

            <div class="overlap-image-frame">
                <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c" class="heritage-sub-img">
            </div>
        </div>

    </div>
</section>

<!-- CONTACT -->
<section class="contact-section">
    <div class="contact-container">

        <div class="contact-grid">

            <div class="contact-info">
                <p class="subtitle">GET IN TOUCH</p>
                <h2>Contact Us</h2>

                <div class="contact-details">
                    <div class="detail-item">
                        <h4>ADDRESS</h4>
                        <p>Surat, Gujarat</p>
                    </div>

                    <div class="detail-item">
                        <h4>PHONE</h4>
                        <p>+91 9876543210</p>
                    </div>

                    <div class="detail-item">
                        <h4>EMAIL</h4>
                        <p>support@almaari.com</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-card">
                <form>
                    <input type="text" placeholder="Your Name">
                    <input type="email" placeholder="Email">
                    <textarea placeholder="Message"></textarea>
                    <button class="btn-primary">Send Message</button>
                </form>
            </div>

        </div>

    </div>
</section>

</x-layouts.app>