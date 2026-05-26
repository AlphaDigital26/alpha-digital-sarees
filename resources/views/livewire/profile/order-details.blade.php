<div class="bg-transparent font-sans">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('profile.orders') }}" class="text-tertiary hover:text-primary transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-secondary m-0 font-serif">Order Details</h1>
    </div>

    <div class="bg-white border border-[#E5E0DA] rounded-sm shadow-sm overflow-hidden mb-6">
        <div class="p-6 md:p-8 border-b border-[#E5E0DA] bg-[#F9F8F6]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1" style="font-family: 'Manrope', sans-serif;">Order Number</p>
                    <p class="text-xl font-bold text-secondary font-serif">#{{ $order->order_number }}</p>
                </div>
                <div class="text-left md:text-right">
                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1" style="font-family: 'Manrope', sans-serif;">Date Placed</p>
                    <p class="text-lg font-bold text-secondary font-serif">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <h2 class="text-lg font-bold text-secondary mb-6 font-serif border-b border-[#E5E0DA] pb-2">Items Ordered</h2>
            
            @if($order->items->count() > 0)
                <div class="space-y-6">
                    @foreach($order->items as $item)
                        @php 
                            $product = $item->product; 
                            $img = $product && is_array($product->images) && count($product->images) > 0 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                        @endphp
                        
                        <div class="flex flex-col sm:flex-row gap-6 pb-6 {{ !$loop->last ? 'border-b border-[#E5E0DA]' : '' }}">
                            <div class="w-full sm:w-24 sm:h-32 flex-shrink-0 bg-[#F4F0EB] overflow-hidden rounded-sm block">
                                <img src="{{ $img }}" alt="{{ $product ? $product->name : 'Product' }}" class="w-full h-full object-cover object-top">
                            </div>
                            <div class="flex-1 flex flex-col justify-center">
                                <h3 class="text-lg font-bold text-secondary mb-1 font-serif">
                                    {{ $product ? $product->name : 'Unknown Product' }}
                                </h3>
                                <p class="text-sm text-gray-500 mb-2" style="font-family: 'Manrope', sans-serif;">Qty: {{ $item->quantity }}</p>
                                <p class="text-md font-bold text-[#800020] mt-auto">Rs. {{ number_format($item->price) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm italic py-4" style="font-family: 'Manrope', sans-serif;">Item details are not available for this legacy order.</p>
            @endif
        </div>
        
        <div class="bg-[#F9F8F6] p-6 md:p-8 border-t border-[#E5E0DA]">
            <h2 class="text-lg font-bold text-secondary mb-4 font-serif">Order Summary</h2>
            <div class="space-y-3 text-sm text-gray-600 max-w-sm" style="font-family: 'Manrope', sans-serif;">
                <div class="flex justify-between">
                    <span>Payment Status</span>
                    <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-gray-800' }}">{{ $order->payment_status ?? 'Paid' }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rs. {{ number_format($order->total_amount) }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-[#E5E0DA] text-base font-bold text-[#800020]">
                    <span>Total</span>
                    <span>Rs. {{ number_format($order->total_amount) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
