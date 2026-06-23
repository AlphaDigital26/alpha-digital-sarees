<main>
    <section class="about-hero" style="background-image: linear-gradient(to bottom, rgba(253,251,247,0) 60%, rgba(253,251,247,1) 100%), url('{{ $story->main_image ? asset('storage/' . $story->main_image) : asset('images/hero-img.webp') }}');">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <!-- <span class="label" data-aos="fade-up" data-aos-delay="100">OUR STORY</span> -->
            <h1 data-aos="fade-up" data-aos-delay="300">{!! nl2br(e($story->main_heading)) !!}</h1>
        </div>
    </section>

    <section class="intro-section">
        <div class="container" data-aos="fade-up" data-aos-delay="200">
            @if($story->para_1)
            <p class="drop-cap-text">
                <span class="drop-cap">{{ substr($story->para_1, 0, 1) }}</span>{!! nl2br(e(substr($story->para_1, 1))) !!}
            </p>
            @endif
        </div>
    </section>

    <section id="artisans" class="craftsmanship-section">
        <div class="container craft-grid">
            <div class="craft-images">
                @if($story->control_image_1)
                <img src="{{ asset('storage/' . $story->control_image_1) }}" class="img-base" data-aos="fade-right" data-aos-delay="100">
                @else
                <img src="{{ asset('images/craftsmanship1.webp') }}" class="img-base" data-aos="fade-right" data-aos-delay="100">
                @endif

                @if($story->control_image_2)
                <img src="{{ asset('storage/' . $story->control_image_2) }}" class="img-overlay-frame" data-aos="fade-up" data-aos-delay="300">
                @else
                <img src="{{ asset('images/craftsmanship2.webp') }}" class="img-overlay-frame" data-aos="fade-up" data-aos-delay="300">
                @endif
            </div>
            <div class="craft-text" data-aos="fade-left" data-aos-delay="200">
                <p class="subtitle mb-4 sm:mb-6" style="color: #800020;">THE ART OF DIGITAL PRINTING</p>
                <h2>{{ $story->heading_2 }}</h2>
                <p>{!! nl2br(e($story->para_2)) !!}</p>
            </div>
        </div>
    </section>

    <section id="sustainability" class="journey-section">
        <div class="container">
            <div class="journey-header" data-aos="fade-up">
                <h2>{{ $story->heading_3 }}</h2>
                <div class="journey-description-text" data-aos="fade-up" data-aos-delay="100">{!! $story->text_3 !!}</div>
            </div>
            <div class="journey-grid">
                @if($story->journey_img_1)
                    <img src="{{ asset('storage/' . $story->journey_img_1) }}" alt="Collection 1" data-aos="zoom-in" data-aos-delay="100">
                @endif
                @if($story->journey_img_2)
                    <img src="{{ asset('storage/' . $story->journey_img_2) }}" alt="Collection 2" data-aos="zoom-in" data-aos-delay="200">
                @endif
                @if($story->journey_img_3)
                    <img src="{{ asset('storage/' . $story->journey_img_3) }}" alt="Collection 3" data-aos="zoom-in" data-aos-delay="300">
                @endif
                @if($story->journey_img_4)
                    <img src="{{ asset('storage/' . $story->journey_img_4) }}" alt="Collection 4" data-aos="zoom-in" data-aos-delay="400">
                @endif
            </div>
        </div>
    </section>
</main>