<footer class="main-footer">
    <div class="footer-container">
        <div class="footer-grid">

            {{-- 1. UPDATED BRAND SECTION --}}
            <div class="footer-brand">
                <div class="logo">ALPHA DIGITAL</div>
                <p>
                    {{ $settings->footer_text ?? 'Founded on the principles of preserving traditional Indian handlooms, ALMAARI brings you curated handlooms that tell a story of artisanal mastery and timeless elegance.' }}
                </p>
                
                {{-- Social Icons: Instagram, Facebook, WhatsApp --}}
                <div class="footer-social-simple">
                    <a href="#" title="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="#" title="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" title="WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </a>
                </div>
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
                <h4>{{ $settings->footer_brand_heading ?? 'NEWSLETTER' }}</h4>
                <p>{{ $settings->footer_newsletter_text ?? 'Sign up to our newsletter to receive exclusive offers.' }}</p>

                <div class="newsletter-form">
                    <div class="newsletter-input-wrapper">
                        <input type="email" placeholder="E-mail">
                    </div>
                    <button class="newsletter-submit-btn">SUBSCRIBE</button>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $settings->footer_copyright_company ?? 'ALPHA DIGITAL PVT. LTD.' }}</p>
            <div class="footer-legal">
                <a href="#">Track Order</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Shipping & Returns</a>
            </div>
        </div>
    </div>
</footer>