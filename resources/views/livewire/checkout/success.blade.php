<div class="min-h-screen bg-[#fbf9f5] flex items-center justify-center py-10 px-4 mt-20 font-sans">
    <div class="bg-white max-w-2xl w-full rounded shadow-sm border border-[#E5E0DA] overflow-hidden">
        
        {{-- Top Header --}}
        <div class="bg-[#800020] text-center py-6 border-b border-[#570013]">
            <h1 class="text-2xl font-bold text-white tracking-[0.2em] uppercase m-0 font-serif">Order Placed</h1>
        </div>

        {{-- Success Message & ID --}}
        <div class="p-6 bg-[#FAFAFA] border-b border-[#E5E0DA] flex items-center gap-4">
            <div class="bg-green-600 rounded-full h-8 w-8 flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-[#1b1c1a] m-0">Thank you for shopping with us!</h2>
                <p class="text-sm text-gray-500 font-medium m-0 mt-0.5">ID: {{ $order->order_number }}</p>
            </div>
        </div>

        {{-- Delivery Estimate & Track --}}
        <div class="p-4 px-6 border-b border-[#E5E0DA] flex justify-between items-center bg-white">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#6366f1]"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                <p class="text-sm font-bold text-[#1b1c1a] m-0">Estimated Delivery by {{ now()->addDays(7)->format('l, jS M') }}</p>
            </div>
            <a href="{{ route('profile.orders.details', $order->id) }}" class="text-[#800020] text-xs font-bold uppercase tracking-wider flex items-center gap-1 hover:text-[#5D4037] transition" style="text-decoration: none;">
                Track Order <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            </a>
        </div>

        {{-- Ordered Items --}}
        <div class="bg-white">
            @php
                $subtotal = 0;
            @endphp
            @if($order->items && $order->items->count() > 0)
                @foreach($order->items as $item)
                    @php 
                        $subtotal += ($item->price * $item->quantity);
                        $product = $item->product; 
                        $img = $product && is_array($product->images) && count($product->images) > 0 
                            ? asset('storage/' . $product->images[0]) 
                            : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                    @endphp
                    <div class="p-6 border-b border-[#E5E0DA] flex gap-4">
                        <div class="w-20 h-24 flex-shrink-0 bg-[#F4F0EB] border border-gray-100 rounded overflow-hidden">
                            <img src="{{ $img }}" alt="{{ $product ? $product->name : 'Product' }}" class="w-full h-full object-cover object-top">
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-[#1b1c1a] mb-1 line-clamp-2 leading-snug">{{ $product ? $product->name : 'Unknown Product' }}</h3>
                            <p class="text-sm font-bold text-[#1b1c1a] mb-2">Rs. {{ number_format($item->price) }}</p>
                            <div class="flex items-center gap-4 text-xs text-gray-500 font-medium">
                                <span>Size: Free Size</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>Qty: {{ $item->quantity }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
            
            @php
                $shipping = ($subtotal > 10000 || $subtotal == 0) ? 0 : 150;
            @endphp
            <div class="p-4 px-6 border-b border-[#E5E0DA] flex justify-between items-center text-xs font-medium text-gray-600 bg-[#FAFAFA]">
                <span>Sold by: ALPHA DIGITAL</span>
                @if($shipping == 0)
                    <span class="text-green-600 font-bold tracking-wide uppercase">Free Delivery</span>
                @else
                    <span class="font-bold">Delivery Charge: Rs. {{ number_format($shipping) }}</span>
                @endif
            </div>
        </div>
        
        {{-- Download Invoice --}}
        <div class="p-4 px-6 border-b border-[#E5E0DA] bg-white flex justify-end">
             <a href="{{ route('profile.orders.invoice', $order->id) }}" class="text-[#800020] text-xs font-bold uppercase tracking-wider hover:text-[#5D4037] transition flex items-center gap-2 cursor-pointer bg-transparent border-none" style="text-decoration: none;">
                 <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                 Download Invoice
             </a>
        </div>

        {{-- Delivery Address Details --}}
        <div class="p-6 bg-white border-b border-[#E5E0DA]">
            <div class="flex items-center gap-2 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#800020]"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide m-0">Delivery Address</h3>
            </div>
            
            @if($address)
                <p class="text-sm font-bold text-[#1b1c1a] mb-2">{{ $address->first_name }} {{ $address->last_name }} <span class="font-normal text-gray-500 ml-2">{{ $address->phone }}</span></p>
                <div class="text-sm text-gray-600 leading-relaxed m-0">
                    @if($address->company)<p class="m-0">{{ $address->company }}</p>@endif
                    <p class="m-0">{{ $address->address_1 }}</p>
                    @if($address->address_2)<p class="m-0">{{ $address->address_2 }}</p>@endif
                    <p class="m-0">{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                    <p class="m-0">{{ $address->country }}</p>
                </div>
            @endif
        </div>

        {{-- Action Button --}}
        <a href="{{ route('shop.index') }}" wire:navigate class="block w-full bg-[#800020] hover:bg-[#5D4037] text-white py-4 text-center font-bold text-sm tracking-[0.15em] uppercase transition-colors outline-none cursor-pointer">
            Continue Shopping
        </a>
    </div>
</div>