<main class="pt-[100px] pb-16 bg-[#fbf9f5] min-h-screen">
    <div class="max-w-7xl mx-auto px-5 lg:px-8">

        {{-- ELEGANT EDITORIAL HEADER --}}
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-[#1b1c1a] tracking-tight leading-none mb-2" style="font-family: 'Noto Serif', serif;">
                Your Shopping Bag
            </h1>
            <p class="text-gray-500 text-xs uppercase tracking-[0.15em] font-bold" style="font-family: 'Manrope', sans-serif;">
                Review your selected heirlooms
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
                        
                        <div class="flex flex-col sm:flex-row gap-6 py-8 border-b border-[#E5E0DA] relative group" wire:key="item-{{ $id }}">
                            
                            {{-- Product Image --}}
                            <a href="{{ route('shop.product', $product->id) }}" wire:navigate class="w-full sm:w-32 sm:h-44 md:w-36 md:h-48 flex-shrink-0 bg-[#F4F0EB] overflow-hidden shadow-sm block">
                                <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                            </a>
                            
                            {{-- Product Details --}}
                            <div class="flex flex-1 flex-col justify-center mt-2 sm:mt-0 pr-10 sm:pr-12">
                                <a href="{{ route('shop.product', $product->id) }}" wire:navigate>
                                    <h3 class="text-xl font-bold mb-1 text-[#1b1c1a] hover:text-[#800020] transition-colors" style="font-family: 'Noto Serif', serif;">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                                
                                <p class="text-sm text-gray-500 mb-6 font-medium" style="font-family: 'Manrope', sans-serif;">
                                    Handwoven | {{ $product->fabric->name ?? 'Silk' }}
                                </p>
                                
                                <div class="flex flex-wrap items-center gap-6 mt-auto">
                                    {{-- Quantity Selector --}}
                                    <div class="flex items-center border border-[#E5E0DA] bg-white h-10 shadow-sm rounded-sm">
                                        <button wire:click="decrementQty({{ $id }})" class="w-10 h-full flex items-center justify-center text-gray-600 hover:bg-[#F4F0EB] transition">-</button>
                                        <span class="w-10 text-center font-bold text-sm border-x border-[#E5E0DA]" style="font-family: 'Manrope', sans-serif;">{{ $item['qty'] }}</span>
                                        <button wire:click="incrementQty({{ $id }})" class="w-10 h-full flex items-center justify-center text-gray-600 hover:bg-[#F4F0EB] transition">+</button>
                                    </div>
                                    
                                    {{-- Price --}}
                                    <span class="text-lg font-bold text-[#800020]">
                                        Rs. {{ number_format($product->current_price * $item['qty']) }}
                                    </span>
                                </div>
                            </div>

                            {{-- Remove Button --}}
                            <button class="absolute top-8 right-2 sm:right-0 text-gray-400 hover:text-red-700 transition-colors p-2" wire:click="removeItem({{ $id }})" title="Remove item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Right Column: Order Summary --}}
                <aside class="lg:col-span-4 bg-white p-8 border border-[#E5E0DA] shadow-sm sticky top-32 rounded-sm h-fit">
                    <h2 class="text-2xl font-bold mb-6 pb-4 border-b border-[#E5E0DA] text-[#1b1c1a]" style="font-family: 'Noto Serif', serif;">
                        Order Summary
                    </h2>
                    
                    <div class="flex justify-between mb-4 text-[#706663] text-sm font-medium" style="font-family: 'Manrope', sans-serif;">
                        <span>Subtotal</span>
                        <span>Rs. {{ number_format($this->cartData['subtotal']) }}</span>
                    </div>
                    
                    <div class="flex justify-between mb-4 text-[#706663] text-sm font-medium" style="font-family: 'Manrope', sans-serif;">
                        <span class="flex items-center gap-1">Shipping 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </span>
                        <span>{{ $this->cartData['shipping'] == 0 ? 'Complimentary' : 'Rs. ' . number_format($this->cartData['shipping']) }}</span>
                    </div>
                    
                    <div class="flex justify-between mt-6 pt-6 border-t border-[#E5E0DA] text-xl font-bold text-[#800020] mb-8" style="font-family: 'Noto Serif', serif;">
                        <span>Total</span>
                        <span>Rs. {{ number_format($this->cartData['total']) }}</span>
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
                <p class="text-gray-500 mb-8 text-lg" style="font-family: 'Manrope', sans-serif;">Your heirloom bag is currently empty.</p>
                <a href="{{ route('shop.index') }}" wire:navigate class="inline-block bg-[#800020] text-white px-8 py-3.5 font-bold uppercase tracking-[0.15em] text-xs hover:bg-[#570013] transition-colors shadow-md rounded-sm">
                    Discover Sarees
                </a>
            </div>
        @endif

    </div>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('razorpay-checkout', (event) => {
            // Livewire 3 wraps dispatched array data in index 0
            let data = event[0]; 

            var options = {
                "key": data.key,
                "amount": data.amount,
                "currency": "INR",
                "name": data.name,
                "description": data.description,
                "order_id": data.order_id,
                "handler": function (response) {
                    // Call the PHP verifyPayment method on success
                    @this.verifyPayment(
                        response.razorpay_payment_id,
                        response.razorpay_order_id,
                        response.razorpay_signature
                    );
                },
                "prefill": {
                    "name": data.prefill.name,
                    "email": data.prefill.email,
                    "contact": data.prefill.contact
                },
                "theme": {
                    "color": "#800020" // Matches your Alpha Digital primary color
                },
                "modal": {
                    "ondismiss": function() {
                        alert('Payment was cancelled');
                    }
                }
            };
            
            var rzp = new Razorpay(options);
            rzp.open();
        });
    });
</script>
</main>