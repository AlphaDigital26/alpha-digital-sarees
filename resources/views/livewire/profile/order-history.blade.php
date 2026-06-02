<div class="bg-transparent font-sans pb-12">
    <h2 class="text-lg font-bold text-secondary m-0 uppercase tracking-wide font-serif mb-6 border-b border-outline_variant/50 pb-4">
        Order History
    </h2>
    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                @php
                    $status = strtolower($order->status);
                    $isTerminal = in_array($status, ['canceled', 'cancelled', 'failed', 'refund_requested', 'refund_approved', 'refund_rejected', 'refunded', 'returned']);
                    $isDelivered = ($status === 'delivered');
                    
                    $terminalMessage = '';
                    if (in_array($status, ['canceled', 'cancelled'])) {
                        if (strtolower($order->cancelled_by_role) === 'admin') {
                            $terminalMessage = 'This order was cancelled by Alpha Digital Support.';
                        } else {
                            $terminalMessage = 'Your order has been successfully cancelled.';
                        }
                    } elseif ($status === 'failed') {
                        $terminalMessage = 'Payment failed or order could not be processed.';
                    } elseif ($status === 'refund_requested') {
                        $terminalMessage = 'Your refund request is currently under review.';
                    } elseif ($status === 'refund_approved') {
                        $terminalMessage = 'Refund approved. The amount will be credited soon.';
                    } elseif ($status === 'refund_rejected') {
                        $terminalMessage = 'Your refund request could not be approved.';
                    } elseif ($status === 'refunded') {
                        $terminalMessage = 'Your refund has been successfully processed.';
                    } elseif ($status === 'returned') {
                        $terminalMessage = 'Item has been returned successfully.';
                    }
                @endphp

                <a href="{{ route('profile.orders.details', $order->id) }}" wire:navigate class="block bg-white border border-[#E5E0DA] rounded-lg overflow-hidden group no-underline text-inherit hover:shadow-md transition cursor-pointer">
                    
                    {{-- Order Header Section --}}
                    <div class="px-6 py-4 flex flex-wrap justify-between items-center text-sm gap-4 border-b border-[#E5E0DA] border-dashed">
                        <div>
                            <span class="text-gray-500 text-xs tracking-wide">Order ID:</span>
                            <p class="font-bold text-[#1b1c1a] m-0 text-sm">{{ $order->order_number }}</p>
                        </div>

                        <div class="text-right">
                            <span class="text-gray-500 text-xs tracking-wide">{{ $order->created_at->format('D, j M') }}</span>
                        </div>
                    </div>
                    {{-- Main Order Content Card Wrapper --}}
                    <div class="p-6">
                        @php
                            $expected = $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->startOfDay() : $order->created_at->addDays(7)->startOfDay();
                            $delivered = $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->startOfDay() : $order->updated_at->startOfDay();
                            $deliveryStatus = 'On Time';
                            $deliveryColor = 'text-[#2E7D32] bg-[#E8F5E9]';
                            if ($isDelivered) {
                                if ($delivered->lt($expected)) {
                                    $deliveryStatus = 'Early Delivery';
                                } elseif ($delivered->gt($expected)) {
                                    $deliveryStatus = 'Late Delivery';
                                    $deliveryColor = 'text-[#C62828] bg-[#FFEBEE]';
                                }
                            }
                            
                            $pillColor = 'bg-[#E8F5E9] text-[#2E7D32]'; // Default Green
                            $pillIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>';
                            
                            if (in_array($status, ['processing', 'shipped', 'refund_requested'])) {
                                $pillColor = 'bg-[#FFF3E0] text-[#E65100]'; // Orange
                                if ($status === 'shipped') {
                                    $pillIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-2.1a1 1 0 00-.29-.71l-3-3A1 1 0 0016 7z" /></svg>';
                                } else {
                                    $pillIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>';
                                }
                            } elseif (in_array($status, ['canceled', 'cancelled', 'failed', 'refund_rejected'])) {
                                $pillColor = 'bg-[#FFEBEE] text-[#C62828]'; // Red
                                $pillIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>';
                            }
                            
                            $statusText = strtolower($order->status) === 'new' ? 'Confirmed' : ucfirst(str_replace('_', ' ', $order->status));
                        @endphp
                        
                        {{-- Status Display --}}
                        <div class="mb-4">
                            <span class="inline-flex items-center gap-1 {{ $pillColor }} px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                                {!! $pillIcon !!}
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <div class="mb-5 flex items-center justify-between">
                            @if($isTerminal)
                                <p class="text-sm font-medium text-gray-600 m-0">{{ $terminalMessage }}</p>
                            @elseif($isDelivered)
                                <div>
                                    <p class="text-sm font-medium text-[#1b1c1a] m-0">Delivered on {{ $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('D, j M') : $order->updated_at->format('D, j M') }}</p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 rounded {{ $deliveryColor }} tracking-wider uppercase">{{ $deliveryStatus }}</span>
                            @else
                                <p class="text-sm font-bold text-[#2E7D32] m-0">Arriving by {{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('D, j M') : $order->created_at->addDays(7)->format('D, j M') }}</p>
                            @endif
                        </div>

                        {{-- Item-Centric Link Grid Block --}}
                        <div class="space-y-0 border border-[#E5E0DA] rounded-lg">
                            @foreach($order->items as $item)
                                @php 
                                    $product = $item->product; 
                                    $img = $product && is_array($product->images) && count($product->images) > 0 
                                        ? asset('storage/' . $product->images[0]) 
                                        : 'https://via.placeholder.com/100x140';
                                @endphp
                                <div class="block {{ !$loop->last ? 'border-b border-[#E5E0DA]' : '' }} px-4 py-4">
                                    <div class="flex items-center justify-between gap-6">
                                        <div class="flex gap-4 items-center">
                                            <div class="w-12 h-16 bg-[#F4F0EB] rounded overflow-hidden flex-shrink-0 border border-[#E5E0DA]">
                                                <img src="{{ $img }}" class="w-full h-full object-cover object-top">
                                            </div>
                                            <div>
                                                <h4 class="text-sm text-[#1b1c1a] m-0 line-clamp-1">{{ $product ? $product->name : 'Premium Heirloom Saree' }}</h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                                    <span>{{ $product && $product->color ? ucfirst($product->color->name) : 'Standard' }}</span>
                                                    <span>&bull;</span>
                                                    <span>Qty: {{ $item->quantity }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-[#E5E0DA] rounded-lg p-16 text-center shadow-sm">
            <h2 class="text-2xl font-bold text-[#1b1c1a] mb-3 font-serif">No Orders Found</h2>
            <p class="text-gray-500 max-w-sm mx-auto mb-8 font-sans">Discover our modern curated collections and heirloom pieces to populate your list.</p>
            <a href="{{ route('shop.index') }}" class="px-8 py-3 bg-[#800020] text-white rounded-sm text-xs font-bold uppercase tracking-widest hover:bg-[#570013] transition no-underline">Browse Gallery</a>
        </div>
    @endif
</div>