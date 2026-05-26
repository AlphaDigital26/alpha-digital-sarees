<main>
    <section class="about-hero" @if($story->main_image) style="background-image: linear-gradient(to bottom, rgba(253,251,247,0) 60%, rgba(253,251,247,1) 100%), url('{{ asset('storage/' . $story->main_image) }}');" @endif>
        <div class="hero-content">
            <span class="label">OUR STORY</span>
            <h1>{!! nl2br(e($story->main_heading)) !!}</h1>
        </div>
    </section>

    <section class="intro-section">
        <div class="container">
            @if($story->para_1)
            <p class="drop-cap-text">
                <span class="drop-cap">{{ substr($story->para_1, 0, 1) }}</span>{!! nl2br(e(substr($story->para_1, 1))) !!}
            </p>
            @endif
        </div>
    </section>

    <section class="craftsmanship-section">
        <div class="container craft-grid">
            <div class="craft-images">
                @if($story->control_image_1)
                <img src="{{ asset('storage/' . $story->control_image_1) }}" class="img-base">
                @else
                <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" class="img-base">
                @endif

                @if($story->control_image_2)
                <img src="{{ asset('storage/' . $story->control_image_2) }}" class="img-overlay-frame">
                @else
                <img src="https://images.unsplash.com/photo-1505330622279-bf7d7fc918f4?auto=format&fit=crop&q=80" class="img-overlay-frame">
                @endif
            </div>
            <div class="craft-text">
                <span class="label">CRAFTSMANSHIP</span>
                <h2>{{ $story->heading_2 }}</h2>
                <p>{!! nl2br(e($story->para_2)) !!}</p>
            </div>
        </div>
    </section>

    <section class="journey-section">
        <div class="container">
            <div class="journey-header">
                <h2>{{ $story->heading_3 }}</h2>
                <div class="journey-description-text">{!! $story->text_3 !!}</div>
            </div>
            <div class="journey-grid">
                @if($story->journey_img_1)
                    <img src="{{ asset('storage/' . $story->journey_img_1) }}" alt="Collection 1">
                @endif
                @if($story->journey_img_2)
                    <img src="{{ asset('storage/' . $story->journey_img_2) }}" alt="Collection 2">
                @endif
                @if($story->journey_img_3)
                    <img src="{{ asset('storage/' . $story->journey_img_3) }}" alt="Collection 3">
                @endif
                @if($story->journey_img_4)
                    <img src="{{ asset('storage/' . $story->journey_img_4) }}" alt="Collection 4">
                @endif
            </div>
        </div>
    </section>
</main>