@php
    // Fetch settings once for the view
    $settings = \App\Models\Setting::first() ?? new \App\Models\Setting();
    $bgImage = !empty($settings->footer_background_image) ? asset('storage/' . $settings->footer_background_image) : null;
@endphp

<footer class="main-footer" @if($bgImage) style="background-image: linear-gradient(rgba(42, 33, 31, 0.92), rgba(42, 33, 31, 0.95)), url('{{ $bgImage }}'); background-size: cover; background-position: center; background-repeat: no-repeat;" @endif>
    <div class="footer-container">
        <div class="footer-grid">

            <div class="footer-brand">
                @if(!empty($settings->footer_image))
                    <img src="{{ asset('storage/' . $settings->footer_image) }}" alt="{{ $settings->footer_brand_heading ?? 'Brand Logo' }}" style="max-height: 60px; margin-bottom: 15px;">
                @else
                    <div class="logo">{{ $settings->footer_brand_heading ?? 'ALPHA DIGITAL BY ANKITA' }}</div>
                @endif
                
                <p>{{ $settings->footer_text ?? 'Founded on the principles of preserving traditional Indian handlooms, we bring you curated collections that tell a story of artisanal mastery and timeless elegance.' }}</p>
                
                <div class="footer-social-simple" style="display: flex; gap: 15px; margin-top: 15px;">
        @if(!empty($settings->instagram_link))
            <a href="{{ $settings->instagram_link }}" target="_blank" title="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>
            </a>
        @endif
        
        @if(!empty($settings->facebook_link))
            <a href="{{ $settings->facebook_link }}" target="_blank" title="Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
            </a>
        @endif

        @if(!empty($settings->twitter_link))
            <a href="{{ $settings->twitter_link }}" target="_blank" title="Twitter">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
            </a>
        @endif

        @if(!empty($settings->youtube_link))
            <a href="{{ $settings->youtube_link }}" target="_blank" title="YouTube">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33 2.78 2.78 0 0 0 1.94 2c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
            </a>
        @endif
    </div>
            </div>

            <div class="footer-links">
                <h4>SHOP</h4>
                <a href="{{ $settings->shop_all_link ?? route('shop.index') }}">All Sarees</a>
                <a href="#">Best Sellers</a>
                <a href="{{ route('shop.new-arrival') }}">New Arrivals</a>
                <a href="#">Fabrics</a>
                <a href="#">Ocassion</a>
            </div>

            <div class="footer-links">
                <h4>USER POLICY</h4>
                <a href="{{ route('policy.privacy') }}">Privacy Policy</a>
                <a href="{{ route('policy.terms') }}">Terms and Conditions</a>
                <a href="{{ route('policy.shipping') }}">Shipping Policy</a>
                <a href="{{ route('policy.refund') }}">Refund Policy</a>
                <a href="{{ route('policy.faqs') }}">FAQs</a>
            </div>

            <div class="footer-links">
                <h4>COMPANY</h4>
                <a href="#">Our Story</a>
                <a href="#">Artisans</a>
                <a href="#">Sustainability</a>
                <a href="{{ route('home') }}#contact">Contact Us</a>
            </div>

            <div class="footer-newsletter">
                <h4>NEWSLETTER</h4>
                <p>{{ $settings->footer_newsletter_text ?? 'Sign up to our newsletter to receive exclusive offers.' }}</p>
                <div class="newsletter-form">
                    <div class="newsletter-input-wrapper">
                        <input type="email" placeholder="E-mail">
                    </div>
                    <button class="newsletter-submit-btn">SUBSCRIBE</button>
                </div>
            </div>

        </div>

        <div class="footer-bottom flex justify-center w-full">
            <p class="text-center m-0">&copy; {{ date('Y') }} {{ $settings->footer_copyright_company ?? 'ALPHA DIGITAL PVT. LTD.' }}</p>
        </div>
    </div>
</footer>