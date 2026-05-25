<main>
    <section class="about-hero">
        <div class="hero-content">
            <span class="label">{{ $story->hero_label }}</span>
            <h1>{!! nl2br(e($story->hero_title)) !!}</h1>
        </div>
    </section>

    <section class="intro-section">
        <div class="container">
            <p class="drop-cap-text">
                <span class="drop-cap">{{ substr($story->intro_text, 0, 1) }}</span>{{ substr($story->intro_text, 1) }}
            </p>
        </div>
    </section>

    <section class="craftsmanship-section">
        <div class="container craft-grid">
            <div class="craft-images">
                <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" class="img-base">
                <img src="https://images.unsplash.com/photo-1505330622279-bf7d7fc918f4?auto=format&fit=crop&q=80" class="img-overlay-frame">
            </div>
            <div class="craft-text">
                <span class="label">{{ $story->craft_label }}</span>
                <h2>{{ $story->craft_title }}</h2>
                <p>{{ $story->craft_p1 }}</p>
                <p>{{ $story->craft_p2 }}</p>
            </div>
        </div>
    </section>

    <section class="journey-section">
        <div class="container">
            <div class="journey-header">
                <h2>{{ $story->journey_title }}</h2>
                <p>{{ $story->journey_description }}</p>
            </div>
            <div class="journey-grid">
    <div class="grid-left">
        @if($story->journey_img_1)
            <img src="{{ asset('storage/' . $story->journey_img_1) }}" alt="Collection 1">
        @endif
        @if($story->journey_img_2)
            <img src="{{ asset('storage/' . $story->journey_img_2) }}" alt="Collection 2">
        @endif
    </div>
    <div class="grid-right">
        @if($story->journey_img_3)
            <img src="{{ asset('storage/' . $story->journey_img_3) }}" alt="Collection 3">
        @endif
        @if($story->journey_img_4)
            <img src="{{ asset('storage/' . $story->journey_img_4) }}" alt="Collection 4">
        @endif
    </div>
</div>
        </div>
    </section>
</main>