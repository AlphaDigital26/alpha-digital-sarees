<div class="bg-transparent font-sans">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('profile.orders') }}" class="text-tertiary hover:text-primary transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-secondary m-0 font-serif">Track Order #{{ $order->order_number }}</h1>
    </div>

    <div class="bg-white border border-[#E5E0DA] rounded-sm p-8 shadow-sm">
        <div class="mb-8 border-b border-[#E5E0DA] pb-6">
            <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1" style="font-family: 'Manrope', sans-serif;">Estimated Delivery</p>
            <p class="text-xl font-bold text-[#800020] font-serif">
                {{ $order->created_at->addDays(7)->format('M d, Y') }}
            </p>
        </div>

        <div class="relative max-w-2xl mx-auto py-8">
            {{-- Status line --}}
            <div class="absolute left-6 top-8 bottom-8 w-0.5 bg-[#E5E0DA]"></div>

            @php
                $statuses = [
                    'new' => 'Order Placed',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered'
                ];

                // Simple logic to determine active steps
                $currentStatus = strtolower($order->status);
                
                if ($currentStatus === 'refunded') {
                    $statuses = [
                        'new' => 'Order Placed',
                        'refunded' => 'Order Refunded'
                    ];
                }

                $statusKeys = array_keys($statuses);
                $currentIndex = array_search($currentStatus, $statusKeys);
                
                if ($currentIndex === false && $currentStatus === 'pending') {
                    $currentIndex = 0; // fallback
                }
            @endphp

            <div class="space-y-12 relative">
                @foreach($statuses as $key => $label)
                    @php
                        $stepIndex = $loop->index;
                        $isCompleted = $stepIndex <= $currentIndex;
                        $isActive = $stepIndex === $currentIndex;
                    @endphp
                    
                    <div class="flex items-start gap-6 relative">
                        <div class="relative z-10 flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center border-4 border-white shadow-sm {{ $isCompleted ? 'bg-[#800020] text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($isCompleted)
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            @else
                                <div class="w-3 h-3 rounded-full bg-white opacity-50"></div>
                            @endif
                        </div>
                        
                        <div class="pt-2">
                            <h3 class="text-lg font-bold {{ $isCompleted ? 'text-secondary' : 'text-gray-400' }}" style="font-family: 'Noto Serif', serif;">
                                {{ $label }}
                            </h3>
                            <p class="text-sm {{ $isCompleted ? 'text-tertiary' : 'text-gray-400' }}" style="font-family: 'Manrope', sans-serif;">
                                @if($key === 'new' && $isCompleted)
                                    We received your order securely.
                                @elseif($key === 'processing' && $isCompleted)
                                    Your heirloom is being prepared.
                                @elseif($key === 'shipped' && $isCompleted)
                                    On its way to you.
                                @elseif($key === 'delivered' && $isCompleted)
                                    Successfully delivered.
                                @elseif($key === 'refunded' && $isCompleted)
                                    Your refund has been processed.
                                @else
                                    Pending update...
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
