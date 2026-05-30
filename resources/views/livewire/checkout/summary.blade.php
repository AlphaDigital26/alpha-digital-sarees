<!-- <div>
    {{-- The Master doesn't talk, he acts. --}}
</div> -->

<div class="max-w-5xl mx-auto px-5 pt-[80px] pb-12 min-h-screen font-sans">
    
    <x-checkout-progress step="3" />

    <div class="mb-4 text-left">
        <h1 class="text-3xl font-bold text-[#2A211F] tracking-tight leading-none mb-2" style="font-family: 'Noto Serif', serif;">Final Review</h1>
        <p class="text-gray-500 text-xs uppercase tracking-[0.15em] font-bold" style="font-family: 'Manrope', sans-serif;">Secure your collection</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-12">
        
        {{-- Left side details --}}
        <div class="md:col-span-7 space-y-8">
            <div class="bg-white p-8 rounded shadow-sm border border-[#E5E0DA]">
                <div class="flex items-center justify-between border-b border-[#E5E0DA] pb-4 mb-6">
                    <h3 class="font-bold text-xl text-[#2A211F]" style="font-family: 'Noto Serif', serif;">Shipping To</h3>
                    <a href="{{ route('checkout.address') }}" class="text-[#800020] text-xs uppercase tracking-widest font-bold hover:text-[#5D4037] transition-colors" style="text-decoration: none;">Edit</a>
                </div>
                <p class="font-bold text-[#1b1c1a] text-lg mb-2">{{ $address->first_name }} {{ $address->last_name }}</p>
                <div class="text-gray-600 text-sm leading-relaxed">
                    @if($address->company)<p>{{ $address->company }}</p>@endif
                    <p>{{ $address->address_1 }}</p>
                    @if($address->address_2)<p>{{ $address->address_2 }}</p>@endif
                    <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    <p>{{ $address->country }}</p>
                    @if($address->phone)<p class="mt-2 text-[#1b1c1a] font-medium">T: {{ $address->phone }}</p>@endif
                </div>
            </div>

            <div class="bg-white p-8 rounded shadow-sm border border-[#E5E0DA]">
                <h3 class="font-bold text-xl border-b border-[#E5E0DA] pb-4 mb-6 text-[#2A211F]" style="font-family: 'Noto Serif', serif;">Your Selection</h3>
                <div class="space-y-6">
                    @foreach($cartDetails['items'] as $item)
                        <div class="flex items-start gap-6 border-b border-[#E5E0DA] pb-6 last:border-0 last:pb-0">
                            <div class="w-20 h-28 bg-[#F4F0EB] flex-shrink-0">
                                <img src="{{ asset('storage/' . ($item['product']->images[0] ?? '')) }}" class="w-full h-full object-cover object-top">
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-[#1b1c1a] mb-1" style="font-family: 'Noto Serif', serif;">{{ $item['product']->name }}</p>
                                <p class="text-xs text-gray-500 mb-3 uppercase tracking-widest">Qty: {{ $item['qty'] }}</p>
                                <p class="font-bold text-[#800020]">Rs. {{ number_format($item['product']->current_price * $item['qty']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right side payment --}}
        <div class="md:col-span-5">
            <div class="bg-white p-8 rounded shadow-sm border border-[#E5E0DA]">
                <h3 class="font-bold text-2xl pb-4 mb-6 text-[#001f3f] border-b border-[#E5E0DA]" style="font-family: 'Noto Serif', serif;">Order Summary</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-[15px] text-gray-500">
                        <span>Price ({{ $cartDetails['total_items'] }} {{ Str::plural('item', $cartDetails['total_items']) }})</span>
                        <span>₹{{ number_format($cartDetails['original_price_total']) }}</span>
                    </div>
                    <div class="flex justify-between text-[15px] text-gray-500">
                        <span>Discount</span>
                        <span class="text-[#008f5d]">- ₹{{ number_format($cartDetails['discount']) }}</span>
                    </div>
                    <div class="flex justify-between text-[15px] text-gray-500 items-center">
                        <span class="flex items-center gap-1">
                            Shipping 
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                        </span>
                        <span>{{ $cartDetails['shipping'] == 0 ? 'Free' : '₹'.number_format($cartDetails['shipping']) }}</span>
                    </div>
                </div>
                
                <div class="border-t border-dashed border-gray-300 my-4"></div>

                <div class="flex justify-between py-2 text-lg font-bold text-[#1b1c1a]" style="font-family: 'Noto Serif', serif;">
                    <span>Total Amount</span>
                    <span>₹{{ number_format($cartDetails['total']) }}</span>
                </div>

                <div class="mt-6">
                    <button wire:click="payWithRazorpay" wire:loading.attr="disabled" class="w-full bg-black hover:bg-gray-800 text-white py-4 font-bold text-xs uppercase tracking-[0.2em] transition-colors flex items-center justify-center gap-3 disabled:opacity-75 disabled:cursor-wait">
                        <span wire:loading.remove wire:target="payWithRazorpay" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            Pay Securely
                        </span>
                        <span wire:loading wire:target="payWithRazorpay" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Connecting to Razorpay...
                        </span>
                    </button>
                    
                    <div class="mt-6 flex flex-col items-center justify-center gap-2 text-gray-400">
                        <div class="flex gap-2">
                            {{-- Dummy payment icons --}}
                            <svg viewBox="0 0 38 24" class="w-8 h-auto" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="pi-visa"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"></path><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"></path><path d="M28.3 10.1l-1.4 8.7h-2.7l1.4-8.7h2.7zM24.7 10.1l-2.6 5.8-2-5.8h-2.8l3.4 8.7h2.8l4-8.7h-2.8zM11.6 10.1L9 16.5l-.3-1.4c-.6-2.1-1.9-3.7-4-4.5l2.6 8.2h3L14.7 10.1h-3.1zM18.8 15.6c-.3 2.1-3 2.2-3.1.8-.1-1.6 2.7-1.9 2.7-3-.1-.1-1.1-.1-1.6.4l-.5-1.5c.8-.5 2.1-.8 3.3-.8 2.2 0 3 1.3 3 2.8 0 3-3.6 3.2-3.6 4.3 0 .7.8.8 1.5.5l.5 1.5c-1 0-2.4.3-2.2-5zM11.5 10.1L9.6 11c-.5-.7-1.2-1-2.4-1-1.9 0-3.3 1-3.3 2.5 0 1.3 1.2 2 2.7 2 .8 0 1.4-.2 1.8-.4l-.2-1h-1.6V12h3.5v6.8H11.5v-8.7z" fill="#1434CB"></path></svg>
                            <svg viewBox="0 0 38 24" class="w-8 h-auto" xmlns="http://www.w3.org/2000/svg" role="img" aria-labelledby="pi-master"><path opacity=".07" d="M35 0H3C1.3 0 0 1.3 0 3v18c0 1.7 1.4 3 3 3h32c1.7 0 3-1.3 3-3V3c0-1.7-1.4-3-3-3z"></path><path fill="#fff" d="M35 1c1.1 0 2 .9 2 2v18c0 1.1-.9 2-2 2H3c-1.1 0-2-.9-2-2V3c0-1.1.9-2 2-2h32"></path><circle fill="#EB001B" cx="15" cy="12" r="7"></circle><circle fill="#F79E1B" cx="23" cy="12" r="7"></circle><path fill="#FF5F00" d="M22 12c0-2.4-1.2-4.5-3-5.7-1.8 1.3-3 3.4-3 5.7s1.2 4.5 3 5.7c1.8-1.2 3-3.3 3-5.7z"></path></svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-widest font-bold">Encrypted 256-bit Secure Checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('razorpay-checkout', (event) => {
                let data = event[0]; 
                var options = {
                    "key": data.key, "amount": data.amount, "currency": "INR", "name": data.name, "order_id": data.order_id,
                    "handler": function (response) {
                        @this.call('verifyPayment', response.razorpay_payment_id, response.razorpay_order_id, response.razorpay_signature);
                    },
                    "prefill": { "name": data.prefill.name, "email": data.prefill.email, "contact": data.prefill.contact },
                    "theme": { "color": "#800020" },
                    "modal": {
                        "ondismiss": function() {
                            // Triggers failure logic instead of emptying cart
                            @this.call('paymentFailed');
                        }
                    }
                };
                var rzp = new Razorpay(options);
                rzp.on('payment.failed', function (response){
                    @this.call('paymentFailed'); // Triggers failure logic if payment fails
                });
                rzp.open();
            });
        });
    </script>

    {{-- Payment Failure Modal --}}
    @if($showFailureModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 transition-opacity">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden relative">
            
            {{-- Close Button --}}
            <button wire:click="closeFailureModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="p-8 pb-6">
                {{-- Error Icon --}}
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-6">
                    <span class="text-red-500 text-2xl font-bold font-serif leading-none">!</span>
                </div>

                {{-- Title --}}
                <h2 class="text-xl font-bold text-[#2A211F] mb-3 font-sans">
                    Transaction of ₹{{ number_format($cartDetails['total']) }} Failed
                </h2>
                
                {{-- Payment Mode --}}
                <p class="text-gray-600 text-sm mb-4 font-sans">
                    Payment Mode: Online (Razorpay)
                </p>

                {{-- Info Text --}}
                <p class="text-gray-600 text-sm leading-relaxed font-sans mb-2">
                    Payment Failed - In case of any amount deduction, the refund will be initiated within 48 hours.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex border-t border-gray-200 p-6 gap-4">
                <a href="{{ route('shop.index') }}" wire:navigate class="flex-1 bg-white text-[#2A211F] border border-gray-300 rounded font-semibold py-3 text-sm hover:bg-gray-50 transition-colors text-center flex items-center justify-center" style="text-decoration: none;">
                    Continue Shopping
                </a>
                <button wire:click="payWithRazorpay" class="flex-1 bg-[#800020] text-white border border-[#800020] rounded font-semibold py-3 text-sm hover:bg-[#5D4037] hover:border-[#5D4037] transition-colors">
                    Retry
                </button>
            </div>
            
        </div>
    </div>
    @endif
</div>
