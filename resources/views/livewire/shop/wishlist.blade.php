<main class="pt-[100px] pb-16 bg-[#fbf9f5] min-h-screen">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">

        {{-- Alpha Digital Header --}}
        <div class="mb-10 border-b border-[#E5E0DA] pb-6 flex justify-between items-end">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-[#1b1c1a] tracking-tight leading-none mb-2" style="font-family: 'Noto Serif', serif;">
                    Your Wishlist
                </h1>
                <p class="text-gray-500 text-xs uppercase tracking-[0.15em] font-bold" style="font-family: 'Manrope', sans-serif;">
                    Curated by you at Alpha Digital
                </p>
            </div>
            <div class="text-sm font-bold text-gray-500">
                {{ count($this->wishlistItems) }} {{ count($this->wishlistItems) === 1 ? 'Item' : 'Items' }}
            </div>
        </div>


        @if($this->wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($this->wishlistItems as $product)
                    @php 
                        $img = is_array($product->images) && count($product->images) > 0 
                            ? asset('storage/' . $product->images[0]) 
                            : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                    @endphp
                    
                    <div class="product-card group relative flex flex-col bg-white border border-[#E5E0DA] shadow-sm rounded-sm overflow-hidden" wire:key="wishlist-{{ $product->id }}">
                        
                        <div class="relative w-full aspect-[4/5] bg-[#F4F0EB] overflow-hidden">
                            {{-- Image Link --}}
                            <a href="{{ route('shop.product', $product->id) }}" wire:navigate class="block w-full h-full">
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700">
                            </a>
                            
                            {{-- FIXED: Prevent default click bubbling --}}
                            <button wire:click.prevent="removeItem({{ $product->id }})" class="absolute top-3 right-3 bg-white/80 backdrop-blur-md p-2 rounded-full text-gray-500 hover:text-red-600 transition-colors shadow-sm z-10" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                        
                        {{-- Details --}}
                        <div class="p-5 flex flex-col flex-grow">
                            <a href="{{ route('shop.product', $product->id) }}" wire:navigate>
                                <h3 class="text-lg font-bold text-[#1b1c1a] hover:text-[#800020] transition-colors line-clamp-1" style="font-family: 'Noto Serif', serif;">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="text-xs text-gray-500 mt-1 mb-4 uppercase tracking-widest font-bold">
                                {{ $product->fabric->name ?? 'Premium Fabric' }}
                            </p>
                            
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-lg font-bold text-[#800020]">
                                    Rs. {{ number_format($product->current_price) }}
                                </span>
                            </div>

                            <button wire:click="moveToCart({{ $product->id }})" class="w-full mt-5 bg-white border-2 border-[#800020] text-[#800020] hover:bg-[#800020] hover:text-white transition-colors py-3 text-xs font-bold uppercase tracking-[0.15em] rounded-sm">
                                Move to Bag
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <p class="text-gray-500 mb-8 text-lg" style="font-family: 'Manrope', sans-serif;">Your wishlist is currently empty.</p>
                <a href="{{ route('shop.index') }}" wire:navigate class="inline-block bg-[#800020] text-white px-8 py-3.5 font-bold uppercase tracking-[0.15em] text-xs hover:bg-[#570013] transition-colors shadow-md rounded-sm">
                    Explore Alpha Digital
                </a>
            </div>
        @endif
    </div>
</main>