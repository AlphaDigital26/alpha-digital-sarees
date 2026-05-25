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
                
                <p>{{ $settings->footer_description ?? 'Founded on the principles of preserving traditional Indian handlooms, we bring you curated collections that tell a story of artisanal mastery and timeless elegance.' }}</p>
                
                <div class="footer-social-simple">
                    @if(!empty($settings->instagram_link)) <a href="{{ $settings->instagram_link }}" target="_blank"><svg ...>...</svg></a> @endif
                    @if(!empty($settings->facebook_link)) <a href="{{ $settings->facebook_link }}" target="_blank"><svg ...>...</svg></a> @endif
                    @if(!empty($settings->twitter_link)) <a href="{{ $settings->twitter_link }}" target="_blank"><svg ...>...</svg></a> @endif
                    @if(!empty($settings->youtube_link)) <a href="{{ $settings->youtube_link }}" target="_blank"><svg ...>...</svg></a> @endif
                </div>
            </div>

            <div class="footer-links">
                <h4>SHOP</h4>
                <a href="{{ $settings->shop_all_link ?? route('shop.index') }}">All Sarees</a>
                <a href="#">Best Sellers</a>
                <a href="{{ route('shop.new-arrival') }}">New Arrivals</a>
            </div>

            <div class="footer-links">
                <h4>USER POLICY</h4>
                <a href="{{ $settings->policy_privacy_link ?? '#' }}">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Shipping & Returns</a>
            </div>

            <div class="footer-links">
                <h4>COMPANY</h4>
                <a href="{{ $settings->company_story_link ?? '#' }}">Our Story</a>
                <a href="#">Artisans</a>
                <a href="#">Contact Us</a>
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