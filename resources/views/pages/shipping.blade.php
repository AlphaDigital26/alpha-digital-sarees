<x-layouts.app title="Shipping & Returns">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pt-[140px] md:pt-[160px] font-sans text-on_surface min-h-[60vh]">
        <h1 class="text-4xl font-bold font-serif text-secondary mb-12 text-center uppercase tracking-wider">Shipping & Returns</h1>
        
        <div class="policy-content">
            {!! $policy->shipping_returns ?? '<p>Shipping and returns policy will appear here.</p>' !!}
        </div>
    </div>
</x-layouts.app>
