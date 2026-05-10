<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-grid">

            <div class="footer-brand">
                <div class="logo">ALPHA DIGITAL</div>

                <p>
                    {{ $settings->footer_text ?? 'Curating the finest Indian handlooms since 1994. Every weave tells a story of heritage and timeless grace.' }}
                </p>
            </div>

            <div class="footer-links">
                <h4>SHOP</h4>

                <a href="{{ route('shop.index') }}">All Sarees</a>
                <a href="#">Best Sellers</a>
                <a href="{{ route('shop.new-arrival') }}">New Arrivals</a>
                <a href="#">Fabrics</a>
            </div>

            <div class="footer-links">
                <h4>COMPANY</h4>

                <a href="#">Our Story</a>
                <a href="#">Artisans</a>
                <a href="#">Sustainability</a>
                <a href="#">Contact Us</a>
            </div>

            <div class="footer-newsletter">
                <h4 class="text-2xl font-bold tracking-widest mb-6">
                {{ $settings->footer_brand_heading ?? 'NEWSLETTER' }}</h4>
                <p>{{ $settings->footer_newsletter_text ?? 'Join the Almaari circle for exclusive previews.' }}</p>

                <div class="newsletter-input">
                    <input type="email" placeholder="Email Address">
                    <button>JOIN</button>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $settings->footer_copyright_company ?? 'ALPHA DIGITAL PVT. LTD.' }}</p>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Shipping & Returns</a>
            </div>
        </div>
    </div>
</footer>