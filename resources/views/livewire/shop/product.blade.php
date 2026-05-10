<main class="product-main">
    <div class="product-container flex flex-col md:flex-row gap-10 max-w-7xl mx-auto px-4">
        <div class="product-gallery">
            <div class="thumbnails">
                @if(is_array($product->images))
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img) }}" 
                             class="thumb {{ $activeImage === $img ? 'active' : '' }}" 
                             wire:click="changeImage('{{ $img }}')" 
                             style="cursor: pointer;">
                    @endforeach
                @endif
            </div>
            <div class="main-display relative">
                @if($activeImage)
                    <img src="{{ asset('storage/' . $activeImage) }}" id="expandedImg" wire:loading.class="opacity-50" class="transition-opacity duration-200">
                @else
                    <img src="https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&q=80" id="expandedImg">
                @endif
            </div>
        </div>

        <div class="product-info flex-1 flex flex-col gap-1"> 
            <h1 class=" leading-tight">{{ $product->name }}</h1>
            
            <p class="price">Rs. {{ number_format($product->current_price, 2) }}</p>
            @if($product->original_price)
                <p class="text-sm text-gray-400 line-through">Rs. {{ number_format($product->original_price, 2) }}</p>
            @endif
            
            <p class="tax-tag">Inclusive of all taxes.</p>

            @if($similarProducts->count() > 0)
                <div class="similar-products">
                    <h4>Similar Products</h4>
                    <div class="similar-grid">
                        @foreach($similarProducts as $simProduct)
                            @php
                                $simImg = is_array($simProduct->images) && count($simProduct->images) > 0 
                                    ? asset('storage/' . $simProduct->images[0]) 
                                    : 'https://images.unsplash.com/photo-1610030469668-93510ec67d9e?auto=format&fit=crop&w=100';
                            @endphp
                            <a href="{{ route('shop.product', $simProduct->id) }}" class="sim-item block hover:opacity-80 transition-opacity">
                                <img src="{{ $simImg }}" alt="{{ $simProduct->name }}" title="{{ $simProduct->name }}">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-1">
                @if($product->stock > 0)
                    <span class="stock-status text-green-600 font-bold block italic text-sm">In Stock ({{ $product->stock }} left)</span>
                @else
                    <span class="stock-status text-red-500 font-bold block italic text-sm">(Out of stock)</span>
                @endif
            </div>

            <div class="purchase-controls mt-auto">
                <div class="quantity-box mb-1">
                    <label style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Quantity</label>
                    <div class="qty-selector flex items-center gap-2 mt-1">
                        <button wire:click="decrementQty" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">-</button>
                        <span id="quantity" class="font-bold">{{ $quantity }}</span>
                        <button wire:click="incrementQty" class="px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">+</button>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mb-10 mt-6">
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button class="flex-1 bg-white border-2 border-[#800020] text-[#800020] font-bold py-3.5 px-6 rounded-xl hover:bg-[#800020] hover:text-white transition-colors shadow-sm disabled:opacity-50" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            ADD TO CART
                        </button>
                        
                        <button class="flex-1 bg-[#800020] text-white font-bold py-3.5 px-6 rounded-xl hover:bg-[#5D4037] transition-colors shadow-md disabled:opacity-50" {{ $product->stock < 1 ? 'disabled' : '' }}>
                            BUY IT NOW
                        </button>
                    </div>
                    
                    <a href="https://wa.me{{ $settings->whatsapp_number ?? '919876543210' }}?text=Hello!%20I%20am%20interested%20in%20buying%20{{ $quantity }}x%20{{ urlencode($product->name) }}." 
                    target="_blank" 
                    class="mt-3 flex w-full items-center justify-center gap-2 rounded-sm bg-[#25D366] px-6 py-4 text-[13px] font-bold uppercase tracking-widest text-white transition hover:bg-[#20ba5a] shadow-sm">
                        
                        <!-- WhatsApp Icon -->
                        <svg xmlns="http://w3.org" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>
                        </svg>
                        
                        Inquire on WhatsApp
                    </a>

                </div>

                <div class="space-y-3">
                    <details class="group bg-white border border-gray-200 rounded-xl" open>
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-gray-900 select-none">
                            <span>Product Description</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="text-gray-600 p-4 border-t prose prose-sm max-w-none">
                            {!! $product->description ?? 'No description available.' !!}
                        </div>
                    </details>

                    <details class="group bg-white border border-gray-200 rounded-xl">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-gray-900 select-none">
                            <span>Specification & Dimension</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="text-gray-600 p-4 border-t prose prose-sm max-w-none">
                            {!! $product->specifications ?? 'No specifications available.' !!}
                        </div>
                    </details>

                    <details class="group bg-white border border-gray-200 rounded-xl">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none p-4 text-gray-900 select-none">
                            <span>Care & Maintenance</span>
                            <span class="transition group-open:rotate-180">
                                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                            </span>
                        </summary>
                        <div class="text-gray-600 p-4 border-t prose prose-sm max-w-none">
                            {!! $product->care_instructions ?? 'Dry clean recommended.' !!}
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</main>