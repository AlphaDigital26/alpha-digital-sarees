
<main class="occasion-container">
    <div class="page-header">
        <p class="subtitle">CURATED COLLECTIONS</p>
        <h1>Shop by Occasion</h1>
    </div>

    @php
        // A list of distinct placeholder images for the large feature cards
        $featureImages = [
            'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1610030469915-055106670868?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&q=80',
            'https://images.unsplash.com/photo-1544441893-675973eebb39?auto=format&fit=crop&q=80'
        ];
    @endphp

    @forelse($occasions as $index => $occasion)
        @php
            // Get products for this specific occasion
            $occProducts = $productsByOccasion->get($occasion->name, collect());
        @endphp

        @if($occProducts->count() > 0)
            <x-editorial-slider 
                :title="$occasion->name" 
                :image="$featureImages[$index % count($featureImages)]" 
                :products="$occProducts" 
                :shopLink="route('shop.index', ['occasion' => $occasion->name])"
            />
        @endif
    @empty
        <div style="text-align: center; padding: 4rem 0;">
            <p style="color: #666; font-style: italic;">No occasion collections available at the moment.</p>
        </div>
    @endforelse

</main>