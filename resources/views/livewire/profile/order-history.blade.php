<div class="bg-transparent font-sans">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1b1c1a] m-0 font-sans tracking-tight">Your Orders</h1>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-8">
            @foreach($orders as $order)
                <div class="bg-white border border-[#F2F0ED] rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
                    {{-- Card Header --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-5 border-b border-[#F2F0ED] bg-[#FAFAFA] text-[13px]">
                        <div class="text-center border-r border-[#E5E0DA]">
                            <p class="text-gray-400 mb-1">Order Number</p>
                            <a href="{{ route('profile.orders.details', $order->id) }}" wire:navigate class="font-bold text-[#1b1c1a] hover:text-[#800020] transition-colors">#{{ $order->order_number }}</a>
                        </div>
                        <div class="text-center md:border-r border-[#E5E0DA]">
                            <p class="text-gray-400 mb-1">Order Date</p>
                            <p class="font-bold text-[#1b1c1a]">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-center border-r border-[#E5E0DA]">
                            <p class="text-gray-400 mb-1">Delivery Date</p>
                            <p class="font-bold text-[#1b1c1a]">{{ $order->created_at->addDays(7)->format('M d, Y') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-400 mb-1">Ship To</p>
                            <p class="font-bold text-[#1b1c1a]">{{ auth('customer')->user()->name ?? 'Customer' }}</p>
                        </div>
                    </div>

                    {{-- Card Body (Items) --}}
                    <div class="p-6">
                        @if($order->items && $order->items->count() > 0)
                            <div class="space-y-6">
                                @foreach($order->items as $item)
                                    @php 
                                        $product = $item->product; 
                                        $img = $product && is_array($product->images) && count($product->images) > 0 
                                            ? asset('storage/' . $product->images[0]) 
                                            : 'https://images.unsplash.com/photo-1610030469613-22878897539f?auto=format&fit=crop&q=80';
                                    @endphp
                                    <div class="flex flex-col sm:flex-row gap-5 pb-6 {{ !$loop->last ? 'border-b border-[#F2F0ED]' : '' }}">
                                        {{-- Image --}}
                                        <div class="w-24 h-24 sm:w-28 sm:h-28 flex-shrink-0 bg-[#F4F0EB] rounded-md overflow-hidden">
                                            <img src="{{ $img }}" alt="{{ $product ? $product->name : 'Product' }}" class="w-full h-full object-cover object-top">
                                        </div>
                                        
                                        {{-- Details --}}
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div class="flex justify-between items-start gap-4">
                                                <h3 class="text-[15px] font-medium text-[#1b1c1a]">
                                                    {{ $product ? $product->name : 'Unknown Product' }}
                                                </h3>
                                                <p class="font-bold text-[#1b1c1a] whitespace-nowrap">Rs. {{ number_format($item->price) }}</p>
                                            </div>
                                            
                                            <div class="mt-2 space-y-1">
                                                <p class="text-[13px] text-gray-500">Qty : <span class="font-medium text-[#1b1c1a]">{{ $item->quantity }}</span></p>
                                            </div>
                                            
                                            <div class="mt-4 flex gap-4">
                                                @if(strtolower($order->status) === 'delivered')
                                                    <a href="{{ route('shop.product', $product->id) }}#reviews" class="text-[13px] font-bold text-[#6366f1] hover:text-indigo-700 transition flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                        Rate Now
                                                    </a>
                                                @else
                                                    <span class="text-[13px] font-bold text-gray-400 flex items-center gap-1 cursor-not-allowed" title="Available after delivery">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                        Rate Now
                                                    </span>
                                                @endif
                                                <a href="{{ route('profile.orders.track', $order->id) }}" wire:navigate class="text-[13px] font-bold text-[#800020] hover:text-[#570013] transition flex items-center gap-1">
                                                    Track Order
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm italic py-2">Item details are not available for this legacy order.</p>
                            <a href="{{ route('profile.orders.track', $order->id) }}" wire:navigate class="text-[13px] font-bold text-[#800020] hover:text-[#570013] transition flex items-center gap-1 mt-3">
                                Track Order
                            </a>
                        @endif
                    </div>

                    {{-- Card Footer --}}
                    <div class="p-5 border-t border-[#F2F0ED] flex flex-wrap justify-between items-center gap-4">
                        <p class="text-[14px] text-gray-500">Total Amount : <span class="font-bold text-[#1b1c1a] text-base ml-1">Rs. {{ number_format($order->total_amount) }}</span></p>
                        <a href="{{ route('profile.orders.invoice', $order->id) }}" class="text-[13px] font-bold text-[#6366f1] hover:text-indigo-700 transition flex items-center gap-2" style="text-decoration: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                            Download Invoice
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-surface_lowest border border-outline_variant/50 rounded-sm p-16 text-center shadow-sm">
            <div class="w-20 h-20 bg-surface rounded-full flex items-center justify-center mx-auto mb-6 text-primary">
                <i data-lucide="package-open" class="w-10 h-10"></i>
            </div>
            <h2 class="text-xl font-bold text-secondary mb-3 font-serif">No Orders Yet</h2>
            <p class="text-tertiary max-w-md mx-auto mb-8">
                You haven't placed any orders yet. Discover our latest curated collections and heirloom pieces to start your journey with Alpha Digital.
            </p>
            <a href="{{ route('shop.index') }}" class="btn-primary rounded-sm py-3.5 px-10 text-sm inline-block no-underline">
                Start Shopping
            </a>
        </div>
    @endif
</div>
