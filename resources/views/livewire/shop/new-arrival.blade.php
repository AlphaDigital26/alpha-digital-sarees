<main class="arrival-container" style="padding-top: 1.5rem;">
    
    <div class="arrival-header" style="margin-bottom: 2rem;">
        <p class="subtitle">DISCOVER THE LATEST ARRIVALS</p>
        <h1 style="margin-top: 0.5rem;">Just Introduced</h1>
        <p class="description">Explore the newest additions to the Alpha Digital collection. Our latest sarees bring together timeless elegance, exceptional quality, and the perfect drape for every occasion.</p>
    </div>

    <x-toast-notification />



    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; border-bottom: 1px solid #eaeaea; padding-bottom: 1rem; gap: 1rem;">
        
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            
            <div x-data="{ open: false, selected: @entangle('selectedFabric').live }" class="relative w-40 sm:w-48 font-sans">
                <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-white border border-[#e5e7eb] text-[#555] py-[0.6rem] pl-4 pr-10 text-[0.85rem] text-left rounded-md shadow-sm focus:outline-none focus:border-[#800020] focus:ring-1 focus:ring-[#800020] flex justify-between items-center transition-all duration-300 hover:border-[#d1d5db]">
                    <span class="truncate">
                        <template x-if="selected == '' || selected == null"><span>All Fabrics</span></template>
                        @foreach($fabrics as $fabric)
                            <template x-if="selected == '{{ $fabric->id }}'"><span>{{ $fabric->name }}</span></template>
                        @endforeach
                    </span>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-[100] mt-1 w-full bg-white rounded-md shadow-lg border border-gray-100 max-h-60 overflow-auto py-1 scrollbar-hide">
                    <div @click="selected = ''; open = false" class="cursor-pointer select-none py-2 px-4 text-[0.85rem] text-gray-700 hover:bg-[#fff0f2] hover:text-[#800020] transition-colors" :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '' || selected == null }">
                        All Fabrics
                    </div>
                    @foreach($fabrics as $fabric)
                        <div @click="selected = '{{ $fabric->id }}'; open = false" class="cursor-pointer select-none py-2 px-4 text-[0.85rem] text-gray-700 hover:bg-[#fff0f2] hover:text-[#800020] transition-colors" :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '{{ $fabric->id }}' }">
                            {{ $fabric->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false, selected: @entangle('selectedColor').live }" class="relative w-40 sm:w-48 font-sans">
                <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-white border border-[#e5e7eb] text-[#555] py-[0.6rem] pl-4 pr-10 text-[0.85rem] text-left rounded-md shadow-sm focus:outline-none focus:border-[#800020] focus:ring-1 focus:ring-[#800020] flex justify-between items-center transition-all duration-300 hover:border-[#d1d5db]">
                    <span class="truncate">
                        <template x-if="selected == '' || selected == null"><span>All Colors</span></template>
                        @foreach($colors as $color)
                            <template x-if="selected == '{{ $color->id }}'"><span>{{ $color->name }}</span></template>
                        @endforeach
                    </span>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-[100] mt-1 w-full bg-white rounded-md shadow-lg border border-gray-100 max-h-60 overflow-auto py-1 scrollbar-hide">
                    <div @click="selected = ''; open = false" class="cursor-pointer select-none py-2 px-4 text-[0.85rem] text-gray-700 hover:bg-[#fff0f2] hover:text-[#800020] transition-colors" :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '' || selected == null }">
                        All Colors
                    </div>
                    @foreach($colors as $color)
                        <div @click="selected = '{{ $color->id }}'; open = false" class="cursor-pointer select-none py-2 px-4 text-[0.85rem] text-gray-700 hover:bg-[#fff0f2] hover:text-[#800020] transition-colors" :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '{{ $color->id }}' }">
                            {{ $color->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div x-data="{ open: false, selected: @entangle('selectedPattern').live }" class="relative w-40 sm:w-48 font-sans">
                <button @click="open = !open" @click.away="open = false" type="button" class="w-full bg-white border border-[#e5e7eb] text-[#555] py-[0.6rem] pl-4 pr-10 text-[0.85rem] text-left rounded-md shadow-sm focus:outline-none focus:border-[#800020] focus:ring-1 focus:ring-[#800020] flex justify-between items-center transition-all duration-300 hover:border-[#d1d5db]">
                    <span class="truncate">
                        <template x-if="selected == '' || selected == null"><span>All Patterns</span></template>
                        @foreach($patterns as $pattern)
                            <template x-if="selected == '{{ $pattern->id }}'"><span>{{ $pattern->name }}</span></template>
                        @endforeach
                    </span>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-[100] mt-1 w-full bg-white rounded-md shadow-lg border border-gray-100 max-h-60 overflow-auto py-1 scrollbar-hide">
                    <div @click="selected = ''; open = false" class="cursor-pointer select-none py-2 px-4 text-[0.85rem] text-gray-700 hover:bg-[#fff0f2] hover:text-[#800020] transition-colors" :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '' || selected == null }">
                        All Patterns
                    </div>
                    @foreach($patterns as $pattern)
                        <div @click="selected = '{{ $pattern->id }}'; open = false" class="cursor-pointer select-none py-2 px-4 text-[0.85rem] text-gray-700 hover:bg-[#fff0f2] hover:text-[#800020] transition-colors" :class="{ 'bg-[#fff0f2] text-[#800020] font-semibold': selected == '{{ $pattern->id }}' }">
                            {{ $pattern->name }}
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div>
            <select wire:model.live="sort" class="premium-select min-w-[150px]">
                <option value="latest">Sort by: Latest</option>
                <option value="price_asc">Price: Low to High</option>
                <option value="price_desc">Price: High to Low</option>
            </select>
        </div>
    </div>

    <div class="arrival-grid grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-6">
        @forelse($products as $product)
            <x-product-card :product="$product" :showWishlist="true" :isNewArrival="true" />
        @empty
            <p style="grid-column: 1 / -1; text-align: center; padding: 4rem 0; color: #666; font-style: italic;">
                No new arrivals match these filters.
            </p>
        @endforelse
    </div>

    <div class="load-more flex justify-center mt-16 mb-20">
        @if ($products->hasMorePages())
            <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="px-10 py-4 border border-[#800020] text-[#800020] bg-white hover:bg-[#800020] hover:text-white transition-colors duration-300 uppercase tracking-[2px] font-bold text-[0.8rem] min-w-[250px]">
                <span wire:loading.remove wire:target="loadMore">Discover More</span>
                <span wire:loading wire:target="loadMore">Loading...</span>
            </button>
        @else
            <button disabled
                    class="px-10 py-4 border border-[#e5e5e5] text-[#999] bg-[#f9f9f9] uppercase tracking-[2px] font-bold text-[0.8rem] min-w-[250px] cursor-not-allowed">
                You've Viewed All
            </button>
        @endif
    </div>
</main>