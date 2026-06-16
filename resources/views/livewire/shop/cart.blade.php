<main class="pt-[80px] pb-4 bg-[#fbf9f5] min-h-screen">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">

        <x-checkout-progress step="1" />

        {{-- ELEGANT EDITORIAL HEADER --}}
        <div class="mb-4 text-left">
            <h1 class="text-3xl md:text-4xl font-bold text-[#1b1c1a] tracking-tight leading-none mb-2" style="font-family: 'Noto Serif', serif;">
                Your Collection
            </h1>
            <p class="text-gray-500 text-xs uppercase tracking-[0.15em] font-bold" style="font-family: 'Manrope', sans-serif;">
                Review your selected collection
            </p>
        </div>

        @if(count($this->cartData['items']) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
                
                {{-- Left Column: Cart Items --}}
                <div class="lg:col-span-8 border-t border-[#E5E0DA]">
                    @foreach($this->cartData['items'] as $id => $item)
                        @php 
                            $product = $item['product']; 
                            
                            $img = is_array($product->images) && count($product->images) > 0 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                        @endphp
                        
                        <div class="flex flex-col sm:flex-row gap-6 py-6 border-b border-[#E5E0DA] relative group items-stretch" wire:key="item-{{ $id }}">
                            
                            {{-- Product Image --}}
                            <a href="{{ route('shop.product', $product->id) }}" wire:navigate class="w-24 sm:w-28 flex-shrink-0 bg-[#F4F0EB] overflow-hidden shadow-sm block mt-1 relative">
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                            </a>
                            
                            {{-- Product Details --}}
                            <div class="flex flex-1 flex-col pr-8 justify-between">
                                <div>
                                    <a href="{{ route('shop.product', $product->id) }}" wire:navigate>
                                        <h3 class="text-lg sm:text-xl font-bold mb-1.5 text-[#800020] hover:text-[#570013] transition-colors leading-snug" style="font-family: 'Noto Serif', serif;">
                                            {{ $product->name }}
                                        </h3>
                                    </a>
                                    
                                    <p class="text-[13px] text-[#706663] mb-3 font-medium" style="font-family: 'Manrope', sans-serif;">
                                        Size: Free Size
                                    </p>
                                    
                                    {{-- Price --}}
                                    <div class="flex items-center gap-2.5 mb-4 font-bold" style="font-family: 'Manrope', sans-serif;">
                                        @if($product->original_price && $product->original_price > $product->current_price)
                                            <span class="text-green-600 text-[13px] flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-0.5"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                                                {{ round((($product->original_price - $product->current_price) / $product->original_price) * 100) }}%
                                            </span>
                                            <span class="text-gray-400 line-through text-sm">
                                                ₹{{ number_format($product->original_price * $item['qty']) }}
                                            </span>
                                        @endif
                                        <span class="text-base sm:text-lg text-[#1b1c1a]">
                                            ₹{{ number_format($product->current_price * $item['qty']) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-auto">
                                    {{-- Quantity Selector --}}
                                    <div class="inline-flex items-center border border-[#E5E0DA] bg-white h-9 shadow-sm rounded-sm">
                                        <button wire:click="decrementQty({{ $id }})" class="w-9 h-full flex items-center justify-center text-gray-500 hover:bg-[#F4F0EB] transition">-</button>
                                        <span class="w-10 text-center font-bold text-[13px] border-x border-[#E5E0DA]" style="font-family: 'Manrope', sans-serif;">{{ $item['qty'] }}</span>
                                        <button wire:click="incrementQty({{ $id }})" class="w-9 h-full flex items-center justify-center text-gray-500 hover:bg-[#F4F0EB] transition">+</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <button class="absolute top-6 right-0 text-gray-400 hover:text-red-700 transition-colors p-1" wire:click="removeItem({{ $id }})" title="Remove item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Right Column: Order Summary --}}
                <aside class="lg:col-span-4 bg-white p-8 border border-[#E5E0DA] shadow-sm rounded-sm h-fit">
                    <h2 class="text-2xl font-bold mb-6 pb-4 border-b border-[#E5E0DA] text-[#1b1c1a]" style="font-family: 'Noto Serif', serif;">
                        Order Summary
                    </h2>
                    
                    <div class="flex justify-between mb-3 text-[#706663] text-sm" style="font-family: 'Manrope', sans-serif;">
                        <span>Price ({{ $this->cartData['totalItems'] }} item{{ $this->cartData['totalItems'] > 1 ? 's' : '' }})</span>
                        <span>₹{{ number_format($this->cartData['totalOriginalPrice']) }}</span>
                    </div>
                    
                    @if($this->cartData['totalDiscount'] > 0)
                    <div class="flex justify-between mb-3 text-sm" style="font-family: 'Manrope', sans-serif;">
                        <span class="text-[#706663]">Discount</span>
                        <span class="text-green-600 font-medium">- ₹{{ number_format($this->cartData['totalDiscount']) }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between mb-4 text-[#706663] text-sm relative" style="font-family: 'Manrope', sans-serif;" x-data="{ tooltipOpen: false }">
                        <span class="flex items-center gap-1 cursor-pointer group" 
                              @mouseenter="tooltipOpen = true" 
                              @mouseleave="tooltipOpen = false"
                              @click="tooltipOpen = !tooltipOpen">
                            Shipping 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 hover:text-[#800020] transition-colors"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                            
                            <!-- Tooltip -->
                            <div x-show="tooltipOpen" x-cloak 
                                 x-transition.opacity.duration.200ms
                                 class="absolute left-0 bottom-full mb-2 w-64 p-3 bg-white border border-[#E5E0DA] shadow-lg rounded-sm text-xs text-[#706663] z-50">
                                <p class="font-bold text-[#1b1c1a] mb-1">Shipping Policy</p>
                                <p>We offer complimentary shipping on all orders above Rs. 10,000. For orders below this amount, a standard shipping fee of Rs. 150 applies.</p>
                                <!-- Arrow -->
                                <div class="absolute top-full left-16 -mt-[1px] w-3 h-3 bg-white border-b border-r border-[#E5E0DA] transform rotate-45"></div>
                            </div>
                        </span>
                        <span>{{ $this->cartData['shipping'] == 0 ? 'Complimentary' : '₹' . number_format($this->cartData['shipping']) }}</span>
                    </div>

                    <div class="border-t border-dashed border-[#E5E0DA] my-4"></div>
                    
                    <div class="flex justify-between text-base font-bold text-[#1b1c1a] mb-4" style="font-family: 'Noto Serif', serif;">
                        <span>Total Amount</span>
                        <span>₹{{ number_format($this->cartData['total']) }}</span>
                    </div>

                    @if($this->cartData['totalDiscount'] > 0)
                    <div class="bg-green-50 text-green-700 text-sm font-medium py-3 px-4 rounded mb-6 flex items-center justify-center gap-2" style="font-family: 'Manrope', sans-serif;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.41l9 9c.36.36.86.58 1.41.58s1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41s-.23-1.06-.59-1.41zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7zM11 13.5l-2-2 1.41-1.41L11 10.67l3.09-3.09L15.5 9l-4.5 4.5z"/></svg>
                        You'll save ₹{{ number_format($this->cartData['totalDiscount']) }} on this order!
                    </div>
                    @else
                    <div class="mb-4"></div>
                    @endif
                    
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 text-xs p-3 rounded mb-4 flex items-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="m-0 font-medium leading-snug" style="font-family: 'Manrope', sans-serif;">
                            <strong>Disclaimer:</strong> Payment feature is currently in test mode. No real transactions will be made.
                        </p>
                    </div>

                    <button wire:click="checkout" wire:loading.attr="disabled" class="w-full bg-[#800020] text-white py-4 font-bold uppercase tracking-[0.15em] text-xs hover:bg-[#570013] transition-colors shadow-md rounded-sm mb-4 disabled:opacity-75 disabled:cursor-wait flex justify-center items-center gap-2">
                        <span wire:loading.remove wire:target="checkout">Proceed to Checkout</span>
                        <span wire:loading wire:target="checkout">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Connecting securely...
                        </span>
                    </button>
                    

                    
                    <div class="text-center pt-6 border-t border-dashed border-[#E5E0DA]">
                        <p class="text-[0.65rem] uppercase tracking-[0.2em] font-bold text-[#A68A64]" style="font-family: 'Manrope', sans-serif;">
                            Heritage Craft Secure Global Payment
                        </p>
                    </div>
                </aside>

            </div>
        @else
            {{-- Empty State (Now safely outside the grid, perfectly centered) --}}
            <div class="flex flex-col items-center justify-center py-24 border-t border-[#E5E0DA] w-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-gray-500 mb-8 text-lg" style="font-family: 'Manrope', sans-serif;">Your collection is currently empty.</p>
                <a href="{{ route('shop.index') }}" wire:navigate class="inline-block bg-[#800020] text-white px-8 py-3.5 font-bold uppercase tracking-[0.15em] text-xs hover:bg-[#570013] transition-colors shadow-md rounded-sm">
                    Discover Sarees
                </a>
            </div>
        @endif

    </div>
</main>