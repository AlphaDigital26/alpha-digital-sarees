<x-layouts.app title="Frequently Asked Questions">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pt-[140px] md:pt-[160px] font-sans text-on_surface min-h-[60vh]">
        <h1 class="text-4xl font-bold font-serif text-secondary mb-12 text-center uppercase tracking-wider">Frequently Asked Questions</h1>
        
        <div class="space-y-6">
            @if(is_array($policy->faqs) && count($policy->faqs) > 0)
                @foreach($policy->faqs as $faq)
                    <div class="border border-outline_variant/50 rounded-sm p-6 bg-surface_lowest">
                        <h3 class="font-bold font-serif text-lg text-secondary mb-2">{{ $faq['question'] }}</h3>
                        <p class="text-[15px] text-tertiary leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                @endforeach
            @else
                <p>FAQs will appear here.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
