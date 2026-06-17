<div class="bg-transparent font-sans pb-16 text-[#1b1c1a]">
    
    {{-- Header with Back Navigation --}}
    <h2 class="text-lg font-bold text-secondary m-0 uppercase tracking-wide font-serif mb-6 border-b border-outline_variant/50 pb-4 flex items-center gap-3">
        <a href="{{ route('profile.orders') }}" wire:navigate class="text-tertiary hover:text-primary transition flex items-center mt-0.5" title="Back to Orders">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        Order Details
    </h2>

    @php
        $status = strtolower($order->status);
        $isTerminal = in_array($status, ['canceled', 'cancelled', 'failed', 'refund_requested', 'refund_approved', 'refund_rejected', 'refunded', 'returned']);
        $isDelivered = ($status === 'delivered');
        
        $terminalMessage = '';
        if (in_array($status, ['canceled', 'cancelled'])) {
            $terminalMessage = 'Your order has been successfully cancelled.';
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
        
        $primaryItem = $order->items->first();
        $otherItems = $order->items->slice(1);
    @endphp

    <div class="space-y-6">
        
        {{-- ITEM DETAILS BOX --}}
        <div class="bg-white border border-[#E5E0DA] rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
            <div class="px-6 py-4 border-b border-[#E5E0DA]">
                <span class="text-gray-500 text-xs tracking-wide">Order ID:</span>
                <span class="font-bold text-[#1b1c1a] ml-1 text-sm">{{ $order->order_number }}</span>
            </div>
            
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
                {{-- Status Header & Timeline --}}
                @if(in_array($status, ['failed', 'refund_requested', 'refund_approved', 'refund_rejected', 'refunded', 'returned']))
                    <div class="mb-6">
                        <div class="mb-4">
                            <span class="inline-flex items-center gap-1 {{ $pillColor }} px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider">
                                {!! $pillIcon !!}
                                {{ $statusText }}
                            </span>
                        </div>
                        <p class="text-sm font-medium text-gray-600 m-0">{{ $terminalMessage }}</p>
                    </div>
                @else
                    {{-- Arriving By / Delivered Header --}}
                    @if(!in_array($status, ['canceled', 'cancelled']))
                        <div class="mb-6 flex items-center justify-between">
                            @if($isDelivered)
                                <div>
                                    <p class="text-lg font-bold text-[#2E7D32] m-0">Delivered on {{ $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at)->format('D, d M') : $order->updated_at->format('D, d M') }}</p>
                                    <div class="flex items-center gap-2 mt-2">
                                        <span class="text-xs text-gray-500">Return window closed on {{ \Carbon\Carbon::parse($order->delivered_at ?? $order->updated_at)->addDays(7)->format('d M') }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 rounded {{ $deliveryColor }} tracking-wider uppercase">{{ $deliveryStatus }}</span>
                            @else
                                <p class="text-lg font-bold text-[#2E7D32] m-0">Arriving By {{ $order->expected_delivery_date ? \Carbon\Carbon::parse($order->expected_delivery_date)->format('D, d M') : $order->created_at->addDays(7)->format('D, d M') }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Horizontal Tracking Timeline --}}
                    <div class="mt-8 mb-8 relative px-2">
                        @php
                            $isCancelled = in_array($status, ['canceled', 'cancelled']);
                            $prevStatus = 'new';
                            if ($isCancelled) {
                                $history = \App\Models\OrderStatusHistory::where('order_id', $order->id)->whereIn('new_status', ['canceled', 'cancelled'])->latest()->first();
                                if ($history) $prevStatus = strtolower($history->previous_status);
                            }
                        @endphp

                        @if($isCancelled)
                            @php
                                $histories = \App\Models\OrderStatusHistory::where('order_id', $order->id)->get()->keyBy('new_status');
                                $processingDate = $histories->has('processing') ? $histories['processing']->created_at : null;
                                $shippedDate = $order->shipping_date ? \Carbon\Carbon::parse($order->shipping_date) : ($histories->has('shipped') ? $histories['shipped']->created_at : null);

                                $totalSteps = 2; // Confirmed + Cancelled
                                if ($prevStatus === 'processing') $totalSteps = 3;
                                if ($prevStatus === 'shipped') $totalSteps = 4;
                                $lineInset = (100 / ($totalSteps * 2)) . '%';
                            @endphp
                            {{-- Background Line --}}
                            <div class="absolute top-4 h-[2px] bg-[#E5E0DA] z-0" style="left: {{ $lineInset }}; right: {{ $lineInset }};"></div>
                            
                            {{-- Active Line --}}
                            <div class="absolute top-4 h-[2px] bg-[#C62828] z-0 transition-all duration-500" style="left: {{ $lineInset }}; width: {{ 100 - (100/$totalSteps) }}%;"></div>

                            <div class="flex justify-between relative z-10">
                                {{-- Step 1: Confirmed --}}
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#1b1c1a] mt-2 mb-0">Confirmed</p>
                                    <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $order->created_at->format('d M, h:i A') }}</p>
                                </div>

                                {{-- Step 2: Processing (if applicable) --}}
                                @if(in_array($prevStatus, ['processing', 'shipped', 'delivered']))
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#1b1c1a] mt-2 mb-0">Processing</p>
                                    @if($processingDate)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $processingDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>
                                @endif

                                {{-- Step 3: Shipped (if applicable) --}}
                                @if(in_array($prevStatus, ['shipped', 'delivered']))
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-2.1a1 1 0 00-.29-.71l-3-3A1 1 0 0016 7z" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#1b1c1a] mt-2 mb-0">Shipped</p>
                                    @if($shippedDate)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $shippedDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>
                                @endif

                                {{-- Final Step: Cancelled --}}
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#C62828] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#C62828] mt-2 mb-0">Cancelled</p>
                                    @if($order->cancelled_at)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($order->cancelled_at)->format('d M, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @elseif(in_array($status, ['refund_requested', 'refund_approved', 'refund_rejected', 'refunded']))
                            {{-- REFUND TIMELINE --}}
                            @php
                                $progressWidth = '0%';
                                if (in_array($status, ['refund_approved', 'refund_rejected'])) $progressWidth = '66.66%';
                                if ($status === 'refunded') $progressWidth = '100%';
                                if ($status === 'refund_requested') $progressWidth = '33.33%';
                            @endphp
                            <div class="absolute top-4 left-[12.5%] right-[12.5%] h-[2px] bg-[#E5E0DA] z-0">
                                <div class="absolute top-0 left-0 h-full bg-[#2E7D32] transition-all duration-500" style="width: {{ $progressWidth }}; {{ $status === 'refund_rejected' ? 'background-color: #C62828;' : '' }}"></div>
                            </div>

                            <div class="flex justify-between relative z-10">
                                {{-- Step 1: Delivered --}}
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#1b1c1a] mt-2 mb-0">Delivered</p>
                                    @if($order->delivered_at)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($order->delivered_at)->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 2: Refund Requested --}}
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#E65100] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#1b1c1a] mt-2 mb-0">Requested</p>
                                    @if($order->refund_requested_at)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($order->refund_requested_at)->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 3: Approved/Rejected --}}
                                @php
                                    $isDecisionMade = in_array($status, ['refund_approved', 'refund_rejected', 'refunded']);
                                    $decisionColor = 'bg-[#F5F5F5] text-gray-300';
                                    $decisionTextColor = 'text-gray-400';
                                    $decisionText = 'Reviewed';
                                    $decisionIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                                    if ($isDecisionMade) {
                                        if ($status === 'refund_rejected') {
                                            $decisionColor = 'bg-[#C62828] text-white';
                                            $decisionTextColor = 'text-[#C62828]';
                                            $decisionText = 'Rejected';
                                            $decisionIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>';
                                        } else {
                                            $decisionColor = 'bg-[#2E7D32] text-white';
                                            $decisionTextColor = 'text-[#1b1c1a]';
                                            $decisionText = 'Approved';
                                        }
                                    }
                                @endphp
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $decisionColor }} shadow-[0_0_0_4px_white]">
                                        {!! $decisionIcon !!}
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold {{ $decisionTextColor }} mt-2 mb-0">{{ $decisionText }}</p>
                                    @if($isDecisionMade && ($order->refund_approved_at || $order->refund_rejected_at))
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($order->refund_approved_at ?? $order->refund_rejected_at)->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 4: Refunded --}}
                                @if($status !== 'refund_rejected')
                                    @php
                                        $isRefunded = ($status === 'refunded');
                                        $refundedColor = $isRefunded ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]' : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                        $refundedTextColor = $isRefunded ? 'text-[#1b1c1a]' : 'text-gray-400';
                                    @endphp
                                    <div class="flex flex-col items-center flex-1">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $refundedColor }} shadow-[0_0_0_4px_white]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                        </div>
                                        <p class="text-[10px] sm:text-xs font-bold {{ $refundedTextColor }} mt-2 mb-0">Refunded</p>
                                        @if($isRefunded && $order->refund_processed_at)
                                            <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($order->refund_processed_at)->format('d M, h:i A') }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        @else
                            {{-- Standard Timeline --}}
                            @php
                                $histories = \App\Models\OrderStatusHistory::where('order_id', $order->id)->get()->keyBy('new_status');
                                $processingDate = $histories->has('processing') ? $histories['processing']->created_at : null;
                                $shippedDate = $order->shipping_date ? \Carbon\Carbon::parse($order->shipping_date) : ($histories->has('shipped') ? $histories['shipped']->created_at : null);
                                $deliveredDate = $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at) : ($histories->has('delivered') ? $histories['delivered']->created_at : null);
                                
                                $progressWidth = '0%';
                                if (in_array($status, ['processing'])) $progressWidth = '33.33%';
                                if (in_array($status, ['shipped'])) $progressWidth = '66.66%';
                                if ($isDelivered) $progressWidth = '100%';
                            @endphp
                            <div class="absolute top-4 left-[12.5%] right-[12.5%] h-[2px] bg-[#E5E0DA] z-0">
                                <div class="absolute top-0 left-0 h-full bg-[#2E7D32] transition-all duration-500" style="width: {{ $progressWidth }}"></div>
                            </div>

                            <div class="flex justify-between relative z-10">
                                {{-- Step 1: Confirmed --}}
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold text-[#1b1c1a] mt-2 mb-0">Confirmed</p>
                                    <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $order->created_at->format('d M, h:i A') }}</p>
                                </div>

                                {{-- Step 2: Processing --}}
                                @php
                                    $isProcessingOrMore = in_array($status, ['processing', 'shipped', 'delivered']);
                                    $procColor = $isProcessingOrMore ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]' : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                    $procTextColor = $isProcessingOrMore ? 'text-[#1b1c1a]' : 'text-gray-400';
                                @endphp
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $procColor }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold {{ $procTextColor }} mt-2 mb-0">Processing</p>
                                    @if($isProcessingOrMore && $processingDate)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $processingDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 3: Shipped --}}
                                @php
                                    $isShippedOrMore = in_array($status, ['shipped', 'delivered']);
                                    $shippedColor = $isShippedOrMore ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]' : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                    $shippedTextColor = $isShippedOrMore ? 'text-[#1b1c1a]' : 'text-gray-400';
                                @endphp
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $shippedColor }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" /><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7h-3v7h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-2.1a1 1 0 00-.29-.71l-3-3A1 1 0 0016 7z" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold {{ $shippedTextColor }} mt-2 mb-0">Shipped</p>
                                    @if($isShippedOrMore && $shippedDate)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $shippedDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>

                                {{-- Step 4: Delivered --}}
                                @php
                                    $deliveredColor = $isDelivered ? 'bg-[#2E7D32] text-white shadow-[0_0_0_4px_white]' : 'bg-[#F5F5F5] text-gray-300 shadow-[0_0_0_4px_white]';
                                    $deliveredTextColor = $isDelivered ? 'text-[#1b1c1a]' : 'text-gray-400';
                                @endphp
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $deliveredColor }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    <p class="text-[10px] sm:text-xs font-bold {{ $deliveredTextColor }} mt-2 mb-0">Delivered</p>
                                    @if($isDelivered && $deliveredDate)
                                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-0.5">{{ $deliveredDate->format('d M, h:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Product List Grid --}}
                <div class="space-y-0 border border-[#E5E0DA] rounded-lg">
                    @foreach($order->items as $item)
                        @php 
                            $product = $item->product; 
                            $img = $product && is_array($product->images) && count($product->images) > 0 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://via.placeholder.com/100x140';
                            $productUrl = $product ? route('shop.product', $product->slug ?? $product->id) : '#';
                        @endphp
                        <div class="block group transition text-inherit {{ !$loop->last ? 'border-b border-[#E5E0DA]' : '' }} px-4 py-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <a href="{{ $productUrl }}" wire:navigate class="flex gap-4 items-center no-underline hover:opacity-80">
                                    <div class="w-12 h-16 bg-[#F4F0EB] rounded overflow-hidden flex-shrink-0 border border-[#E5E0DA]">
                                        <img src="{{ $img }}" class="w-full h-full object-cover object-top">
                                    </div>
                                    <div>
                                        <h4 class="text-sm text-[#1b1c1a] m-0 group-hover:text-[#800020] transition-colors duration-200 line-clamp-1">{{ $product ? $product->name : 'Premium Heirloom Saree' }}</h4>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                            <span>{{ $product && $product->color ? ucfirst($product->color->name) : 'Standard' }}</span>
                                            <span>&bull;</span>
                                            <span>Qty: {{ $item->quantity }}</span>
                                        </div>
                                    </div>
                                </a>
                                <div class="flex items-center justify-end">
                                    @if($isDelivered && $product)
                                        @php
                                            $hasReviewed = \App\Models\Review::where('customer_id', auth('customer')->id())
                                                ->where('product_id', $product->id)->exists();
                                        @endphp
                                        @if(!$hasReviewed)
                                            <button wire:click.stop="openReviewModal({{ $product->id }})" class="text-xs font-bold text-[#800020] hover:text-[#5D4037] transition uppercase tracking-widest bg-transparent border-none cursor-pointer flex items-center gap-1 p-0 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                                                Rate Product
                                            </button>
                                        @else
                                            <button wire:click.stop="openViewReviewModal({{ $product->id }})" class="text-xs font-bold text-green-600 hover:text-green-700 transition uppercase tracking-widest bg-transparent border-none cursor-pointer flex items-center gap-1 p-0 m-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                View Review
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ $productUrl }}" wire:navigate class="text-gray-400 hover:text-[#800020]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            @if(!$isTerminal)
                <div class="px-6 py-4 border-t border-[#E5E0DA] bg-[#FAFAFA] flex items-center justify-between">
                    <div>
                        @if($isDelivered)
                            @php
                                $deliveredAt = $order->delivered_at ? \Carbon\Carbon::parse($order->delivered_at) : $order->updated_at;
                                $canRefund = now()->diffInDays($deliveredAt) <= 7;
                            @endphp
                            <div class="flex flex-col items-start gap-1">
                                @if($canRefund)
                                    <button wire:click="openRefundModal" class="text-xs font-bold text-[#800020] hover:text-[#570013] transition uppercase tracking-widest bg-transparent border-none cursor-pointer flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                                        Refund / Return
                                    </button>
                                    <span class="text-[10px] text-gray-500 font-medium">Note: Refunds must be requested within 7 days of delivery.</span>
                                @else
                                    <button disabled class="text-xs font-bold text-gray-400 uppercase tracking-widest bg-transparent border-none cursor-not-allowed flex items-center gap-1" title="Refund period has expired">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                                        Refund / Return
                                    </button>
                                    <span class="text-[10px] text-[#C62828] font-medium">The 7-day refund window has expired.</span>
                                @endif
                            </div>
                        @elseif(in_array($status, ['new', 'processing']))
                            <div class="flex flex-col items-start gap-1">
                                <button wire:click="openCancelModal" class="text-xs font-bold text-red-600 hover:text-red-800 transition uppercase tracking-widest bg-transparent border-none cursor-pointer flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    Cancel Order
                                </button>
                                <span class="text-[10px] text-gray-500 font-medium">Note: You can cancel your order anytime before it is shipped.</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        {{-- Kept empty to balance flexbox if needed, or you can remove entirely if you align left --}}
                    </div>
                </div>
            @endif
        </div>

        @if(in_array($status, ['canceled', 'cancelled']))
            {{-- CANCELLATION DETAILS BOX --}}
            <div class="bg-white border border-[#E5E0DA] rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.02)] p-6">
                <h3 class="font-bold text-base text-[#C62828] mb-4 m-0">Cancellation Details</h3>
                <div class="bg-[#FFEBEE] border border-[#ffcdd2] rounded p-4 text-sm text-[#C62828] leading-relaxed">
                    <p class="font-bold mb-1">Reason for Cancellation:</p>
                    <p class="m-0 mb-3">{{ $order->cancellation_reason ?? 'No reason provided.' }}</p>
                    
                    
                    
                    <div class="flex justify-between items-center text-xs opacity-80 pt-2 border-t border-[#ef9a9a]">
                        <span>Cancelled By: <strong>{{ strtolower($order->cancelled_by_role) === 'admin' ? 'Alpha Digital Support' : 'You (Customer)' }}</strong></span>
                        <span>On {{ $order->cancelled_at ? \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                </div>
                
                @if($order->refund_required)
                <div class="mt-4 bg-[#FAFAFA] border border-[#E5E0DA] rounded p-4 text-sm text-[#5D4037]">
                    <p class="font-bold text-[#1b1c1a] m-0 mb-1">Refund Status</p>
                    <p class="m-0 text-gray-600">Your refund is currently being processed. It typically takes 5-7 business days to reflect in your original payment method.</p>
                </div>
                @endif
            </div>
        @endif

        {{-- REFUND DETAILS BOX --}}
        @if(in_array($status, ['refund_requested', 'refund_approved', 'refund_rejected', 'refunded']))
            <div class="bg-white border border-[#E5E0DA] rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.02)] p-6">
                <h3 class="font-bold text-base text-[#d97706] mb-4 m-0 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                    Refund Details
                </h3>
                <div class="bg-[#FFF8E1] border border-[#ffecb3] rounded p-4 text-sm text-[#F57F17] leading-relaxed">
                    <p class="font-bold mb-1">Reason for Refund Request:</p>
                    <p class="m-0 mb-3 text-[#5D4037]">{{ $order->refund_reason ?? 'No reason provided.' }}</p>
                    
                    @if(!empty($order->refund_evidence) && is_array($order->refund_evidence))
                    <p class="font-bold mb-1">Supporting Evidence:</p>
                    <div class="flex gap-2 flex-wrap mb-3">
                        @foreach($order->refund_evidence as $evidence)
                            <a href="{{ Storage::url($evidence) }}" target="_blank" class="inline-flex items-center gap-1 text-[#F57F17] font-bold text-xs bg-white px-3 py-1.5 border border-[#ffecb3] rounded no-underline hover:bg-[#ffecb3] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                View Attachment {{ $loop->iteration }}
                            </a>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center text-xs opacity-80 pt-2 border-t border-[#ffe082] text-[#F57F17]">
                        <span>Requested By: <strong>You (Customer)</strong></span>
                        <span>On {{ $order->refund_requested_at ? \Carbon\Carbon::parse($order->refund_requested_at)->format('d M Y, h:i A') : 'N/A' }}</span>
                    </div>
                </div>

                @if($status === 'refund_rejected')
                    <div class="mt-4 bg-[#FFEBEE] border border-[#ffcdd2] rounded p-4 text-sm text-[#C62828]">
                        <p class="font-bold m-0 mb-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Refund Rejected
                        </p>
                        <p class="m-0 mb-2">Your refund request was reviewed but unfortunately rejected.</p>
                        <p class="m-0 text-xs font-bold uppercase tracking-wider">Reason provided by Admin:</p>
                        <p class="m-0 italic">{{ $order->refund_rejection_reason ?? 'No specific reason provided.' }}</p>
                    </div>
                @elseif($status === 'refund_approved' || $status === 'refunded')
                    <div class="mt-4 bg-[#E8F5E9] border border-[#c8e6c9] rounded p-4 text-sm text-[#2E7D32]">
                        <p class="font-bold m-0 mb-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Refund Approved
                        </p>
                        @if($status === 'refunded')
                            <p class="m-0">Your refund has been successfully processed on {{ $order->refund_processed_at ? \Carbon\Carbon::parse($order->refund_processed_at)->format('d M Y, h:i A') : 'N/A' }}. It should reflect in your original payment method shortly.</p>
                        @else
                            <p class="m-0">Your refund request has been approved and is currently being processed. It typically takes 5-7 business days to reflect in your original payment method.</p>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- DELIVERY DETAILS --}}
        <div class="bg-white border border-[#E5E0DA] rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.02)] p-6">
            <h3 class="font-bold text-base text-[#1b1c1a] mb-4 m-0">Delivery details</h3>
            @php $address = $order->customer?->addresses()->first(); @endphp
            <div class="bg-[#FAFAFA] border border-[#E5E0DA] rounded p-4 text-sm text-[#5D4037] leading-relaxed">
                <p class="font-bold text-[#1b1c1a] mb-1">{{ auth('customer')->user()->name ?? 'Customer' }}</p>
                @if($address)
                    <p class="m-0">{{ $address->address_1 }}@if($address->address_2), {{ $address->address_2 }}@endif</p>
                    <p class="m-0">{{ $address->city }}, {{ $address->province }} - {{ $address->postal_code }}</p>
                    <p class="mt-3 flex items-center gap-2"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg> (+91) {{ $address->phone }}</p>
                @else
                    <p class="italic text-gray-400">Address details unavailable.</p>
                @endif
            </div>
        </div>

        {{-- ORDER PRICE SUMMARY --}}
        <div class="bg-white border border-[#E5E0DA] rounded-lg shadow-[0_2px_10px_rgba(0,0,0,0.02)] overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center cursor-pointer border-b border-[#E5E0DA]">
                <h3 class="font-bold text-base text-[#1b1c1a] m-0">
                    Order Price
                </h3>
            </div>
            
            @php
                $totalItems = $order->items->sum('quantity');
                $originalPriceTotal = 0;
                $subtotalCalc = 0;
                
                foreach($order->items as $item) {
                    $subtotalCalc += ($item->price * $item->quantity);
                    $prodOrig = $item->product ? $item->product->original_price : 0;
                    $origPrice = $prodOrig > 0 ? $prodOrig : $item->price;
                    $originalPriceTotal += ($origPrice * $item->quantity);
                }
                
                $shippingCalc = $order->total_amount - $subtotalCalc;
                $shippingCalc = $shippingCalc > 0 ? $shippingCalc : 0;
                $discount = $originalPriceTotal - $subtotalCalc;
            @endphp

            <div class="p-6">
                <div class="space-y-4 text-[15px] text-gray-600 pb-5 border-b border-dashed border-[#E5E0DA]">
                    <div class="flex justify-between">
                        <span>Price ({{ $totalItems }} item{{ $totalItems > 1 ? 's' : '' }})</span>
                        <span>₹{{ number_format($originalPriceTotal, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Discount</span>
                        <span class="text-[#2E7D32]">- ₹{{ number_format($discount, 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="flex items-center gap-1" x-data="{ showTooltip: false }">
                            Shipping 
                            <div class="relative flex items-center justify-center">
                                <svg @mouseenter="showTooltip = true" @mouseleave="showTooltip = false" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 cursor-pointer hover:text-[#800020] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                
                                {{-- Tooltip --}}
                                <div x-show="showTooltip" 
                                     x-transition.opacity.duration.200ms
                                     class="absolute bottom-full left-[-12px] mb-2 w-64 bg-white border border-[#E5E0DA] shadow-lg rounded p-4 z-50 text-left"
                                     style="display: none; cursor: default;">
                                    <h4 class="font-bold text-[#1b1c1a] m-0 mb-2 text-sm">Shipping Policy</h4>
                                    <p class="text-xs text-gray-500 m-0 leading-relaxed">
                                        We offer complimentary shipping on all orders above Rs. 10,000. For orders below this amount, a standard shipping fee of Rs. 150 applies.
                                    </p>
                                    {{-- Tooltip Arrow --}}
                                    <div class="absolute top-full left-[12px] -mt-[1px] border-8 border-transparent border-t-white"></div>
                                    <div class="absolute top-full left-[12px] mt-[1px] border-8 border-transparent border-t-[#E5E0DA] -z-10"></div>
                                </div>
                            </div>
                        </span>
                        <span>{{ $shippingCalc == 0 ? 'Free' : '₹'.number_format($shippingCalc, 0) }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center py-5">
                    <span class="font-bold text-[18px] text-[#1b1c1a]">Total Amount</span>
                    <span class="font-bold text-[18px] text-[#1b1c1a]">₹{{ number_format($order->total_amount, 0) }}</span>
                </div>
                
                @if($discount > 0)
                <div class="bg-[#F0FDF4] text-[#16A34A] text-[15px] font-medium p-3 rounded-md flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                    You'll save ₹{{ number_format($discount, 0) }} on this order!
                </div>
                @endif
                
                <div class="flex justify-between text-sm pt-4 border-t border-[#E5E0DA]">
                    <span class="text-gray-500">Payment mode</span>
                    <span class="font-bold text-[#1b1c1a] uppercase">{{ $order->payment_status === 'paid' ? 'Online (Razorpay)' : 'COD' }}</span>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-[#E5E0DA] bg-[#FAFAFA] flex justify-between items-center text-sm rounded-b-lg">
                <span class="text-gray-600">Get invoice for this shipment</span>
                <a href="{{ route('profile.orders.invoice', $order->id) }}" class="text-[#800020] font-bold no-underline hover:underline">Download invoice</a>
            </div>
    </div>
</div>

{{-- CANCEL ORDER MODAL --}}
@if($cancelModalOpen)
<div x-data x-init="document.body.style.overflow = 'hidden'; return () => document.body.style.overflow = ''" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden border border-[#E5E0DA]">
        <div class="px-6 py-4 flex justify-between items-center bg-[#FAFAFA] border-b border-[#E5E0DA]">
            <h3 class="text-lg font-bold m-0 text-[#1b1c1a] flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Cancel Order
            </h3>
            <button wire:click="$set('cancelModalOpen', false)" class="text-gray-400 hover:text-gray-600 transition bg-transparent border-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <form wire:submit.prevent="cancelOrder">
            <div class="p-6">
                <p class="text-sm text-[#5D4037] m-0 mb-4 leading-relaxed">
                    Are you sure you want to cancel this order? This action cannot be undone. 
                    @if(strtolower($order->payment_status) === 'paid')
                        Since you have already paid, a refund will be initiated automatically to your original payment method.
                    @endif
                </p>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-[#1b1c1a] mb-2">Reason for Cancellation <span class="text-red-500">*</span></label>
                    <select wire:model.live="cancellation_reason" class="w-full border border-[#E5E0DA] rounded-md px-3 py-2 text-sm focus:outline-none focus:border-[#800020] transition bg-white">
                        <option value="">Select a reason...</option>
                        <option value="Found a better price elsewhere">Found a better price elsewhere</option>
                        <option value="Changed my mind">Changed my mind</option>
                        <option value="Order placed by mistake">Order placed by mistake</option>
                        <option value="Expected delivery date is too late">Expected delivery date is too late</option>
                        <option value="Other">Other (Please specify)</option>
                    </select>
                    @error('cancellation_reason') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($cancellation_reason === 'Other')
                <div class="mb-4">
                    <label class="block text-sm font-bold text-[#1b1c1a] mb-2">Specific Reason <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="custom_cancellation_reason" rows="2" class="w-full border border-[#E5E0DA] rounded-md px-3 py-2 text-sm focus:outline-none focus:border-[#800020] transition bg-white resize-none" placeholder="Briefly describe your reason..."></textarea>
                    @error('custom_cancellation_reason') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" wire:click="$set('cancelModalOpen', false)" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer bg-white border border-[#E5E0DA] text-[#1b1c1a] hover:bg-gray-50 transition">Keep Order</button>
                    <button type="submit" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer bg-red-600 border border-red-600 text-white hover:bg-red-700 transition">Confirm Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

{{-- REFUND REQUEST MODAL --}}
@if($refundModalOpen)
<div x-data x-init="document.body.style.overflow = 'hidden'; return () => document.body.style.overflow = ''" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden border border-[#E5E0DA]">
        <div class="px-6 py-4 flex justify-between items-center bg-[#FAFAFA] border-b border-[#E5E0DA]">
            <h3 class="text-lg font-bold m-0 text-[#1b1c1a] flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#d97706]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                Request Refund / Return
            </h3>
            <button wire:click="$set('refundModalOpen', false)" class="text-gray-400 hover:text-gray-600 transition bg-transparent border-none cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        
        <form wire:submit.prevent="submitRefundRequest">
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                <div class="bg-[#FFF8E1] border border-[#ffecb3] rounded-md p-3 mb-4 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#F57F17] mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-xs text-[#F57F17] m-0 leading-relaxed font-medium">
                        <strong>Policy:</strong> Refunds must be requested within 7 days of delivery. Original payment methods will be credited.
                        <a href="{{ route('policy.shipping') }}" target="_blank" class="text-[#d97706] hover:text-[#b45309] underline transition ml-1">Read our refund/return policy here</a>
                    </p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold text-[#1b1c1a] mb-2">Reason for Refund <span class="text-red-500">*</span></label>
                    <select wire:model.live="refund_reason" class="w-full border border-[#E5E0DA] rounded-md px-3 py-2 text-sm focus:outline-none focus:border-[#d97706] transition bg-white">
                        <option value="">Select a reason...</option>
                        <option value="Damaged Product">Damaged Product</option>
                        <option value="Wrong Product Received">Wrong Product Received</option>
                        <option value="Product Defect">Product Defect</option>
                        <option value="Quality Issue">Quality Issue</option>
                        <option value="Missing Item">Missing Item</option>
                        <option value="Delivery Delay">Delivery Delay</option>
                        <option value="Incorrect Product Description">Incorrect Product Description</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('refund_reason') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($refund_reason === 'Other')
                <div class="mb-4">
                    <label class="block text-sm font-bold text-[#1b1c1a] mb-2">Specific Reason <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="refund_custom_reason" rows="3" class="w-full border border-[#E5E0DA] rounded-md px-3 py-2 text-sm focus:outline-none focus:border-[#d97706] transition bg-white resize-none" placeholder="Please provide specific details about your refund request..."></textarea>
                    @error('refund_custom_reason') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-sm font-bold text-[#1b1c1a] mb-2">Supporting Evidence (Optional)</label>
                    <p class="text-xs text-gray-500 mb-2">Upload photos of the product, damage, or packaging to help us process your request faster.</p>
                    <div class="border border-[#E5E0DA] border-dashed rounded-md px-3 py-3 text-center bg-[#FAFAFA]">
                        <input type="file" wire:model="refund_evidence" multiple accept="image/*" class="text-sm w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#fef3c7] file:text-[#d97706] hover:file:bg-[#fde68a] transition cursor-pointer">
                        <div wire:loading wire:target="refund_evidence" class="text-xs text-[#d97706] mt-2 font-bold animate-pulse">Uploading files...</div>
                    </div>
                    @error('refund_evidence.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    
                    @if(count($refund_evidence) > 0)
                        <div class="mt-2 text-xs text-green-600 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            {{ count($refund_evidence) }} file(s) attached
                        </div>
                    @endif
                </div>
            </div>
            <div class="p-6 border-t border-[#E5E0DA] bg-[#FAFAFA] flex justify-end gap-3">
                <button type="button" wire:click="$set('refundModalOpen', false)" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer bg-white border border-[#E5E0DA] text-[#1b1c1a] hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer bg-[#d97706] border border-[#d97706] text-white hover:bg-[#b45309] transition">Submit Request</button>
            </div>
        </form>
    </div>
</div>
@endif

    {{-- REVIEW MODAL --}}
    @if($reviewModalOpen)
        <div x-data="{ _sy: 0 }" x-init="
            _sy = window.pageYOffset;
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + _sy + 'px';
            document.body.style.width = '100%';
            document.body.style.overflowY = 'scroll';
            return () => {
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                document.body.style.overflowY = '';
                window.scrollTo(0, _sy);
            }
        " class="fixed inset-0 z-[9999] w-screen flex items-center justify-center p-4 md:p-8">
            <div class="fixed inset-0 bg-[#2A211F] opacity-50" wire:click="$set('reviewModalOpen', false)"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl relative z-10 animate-fade-in-up m-auto flex flex-col max-h-[90vh]">
                
                {{-- HEADER (Fixed) --}}
                <div class="p-6 md:p-8 pb-4 md:pb-4 flex-shrink-0 relative border-b border-[#E5E0DA]">
                    @if($isEditingReview)
                        <button wire:click="goBackToViewReview" class="absolute top-6 left-6 text-gray-400 hover:text-[#800020] transition bg-transparent border-none cursor-pointer z-20" title="Back to View Review">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        </button>
                    @endif
                    <button wire:click="$set('reviewModalOpen', false)" class="absolute top-6 right-6 text-gray-400 hover:text-[#800020] transition bg-transparent border-none cursor-pointer z-20" title="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <div class="text-center">
                        <h2 class="text-2xl font-serif text-[#1b1c1a] m-0 mb-2">{{ $isEditingReview ? 'Edit Your Review' : 'Rate Product' }}</h2>
                        <p class="text-gray-500 text-sm m-0">Tell us what you think!</p>
                    </div>
                </div>

                <form wire:submit.prevent="submitReview" class="flex flex-col flex-grow overflow-hidden">
                    
                    {{-- BODY (Scrollable) --}}
                    <div class="p-6 md:p-8 py-6 overflow-y-auto flex-grow hide-modal-scroll space-y-6" style="scrollbar-width: none; -ms-overflow-style: none;">
                        <style>
                            .hide-modal-scroll::-webkit-scrollbar { display: none; }
                        </style>
                        
                        <div>
                            <label class="block text-[13px] font-bold text-gray-700 uppercase tracking-wider mb-2 text-center">Rating</label>
                            <div class="flex gap-1 justify-center" x-data="{ rating: @entangle('reviewRating').live, hoverRating: 0 }">
                                <template x-for="i in 5" :key="i">
                                    <button type="button" 
                                            @click="rating = i" 
                                            @mouseenter="hoverRating = i" 
                                            @mouseleave="hoverRating = 0"
                                            class="focus:outline-none transition-colors duration-150 bg-transparent border-none cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" 
                                             class="w-10 h-10" 
                                             :class="(hoverRating >= i || (!hoverRating && rating >= i)) ? 'text-yellow-500 fill-current' : 'text-gray-300 fill-current'" 
                                             viewBox="0 0 24 24" stroke="none">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                            @error('reviewRating') <span class="text-red-500 text-xs block text-center mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="reviewComment" class="block text-sm font-bold text-[#1b1c1a] mb-2">Your Review (Optional)</label>
                            <textarea wire:model="reviewComment" id="reviewComment" rows="4" class="w-full px-4 py-3 bg-[#FAFAFA] border border-[#E5E0DA] rounded-sm focus:outline-none focus:border-[#800020] focus:ring-1 focus:ring-[#800020] transition-colors resize-none text-[14px]" placeholder="Write your experience..."></textarea>
                            @error('reviewComment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#1b1c1a] mb-2">Upload Photos (Optional)</label>
                            <div class="border border-[#E5E0DA] border-dashed rounded-md px-3 py-3 text-center bg-[#FAFAFA]">
                                <input type="file" wire:key="review-photos-{{ $uploadIteration }}" wire:model="newPhotos" multiple accept="image/*" class="text-sm w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#F5F0EB] file:text-[#800020] hover:file:bg-[#E5E0DA] transition cursor-pointer">
                                <div wire:loading wire:target="newPhotos" class="text-xs text-[#800020] mt-2 font-bold animate-pulse">Uploading files...</div>
                            </div>
                            @error('newPhotos.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                            
                            @if(count($existingPhotos) > 0 || count($reviewPhotos) > 0)
                                <div class="mt-3 flex gap-2 flex-wrap">
                                    {{-- Existing Photos --}}
                                    @foreach($existingPhotos as $index => $photo)
                                        <div class="w-16 h-16 rounded overflow-hidden border border-[#E5E0DA] relative group">
                                            <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                                            <button type="button" wire:click="removeExistingPhoto({{ $index }})" class="absolute top-0 right-0 bg-red-500 text-white p-0.5 rounded-bl opacity-0 group-hover:opacity-100 transition" title="Remove image">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    @endforeach

                                    {{-- New Uploads --}}
                                    @foreach($reviewPhotos as $index => $photo)
                                        <div class="w-16 h-16 rounded overflow-hidden border border-[#E5E0DA] relative group">
                                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                            <button type="button" wire:click="removeNewPhoto({{ $index }})" class="absolute top-0 right-0 bg-red-500 text-white p-0.5 rounded-bl opacity-0 group-hover:opacity-100 transition" title="Remove image">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- FOOTER (Fixed) --}}
                    <div class="p-6 md:p-8 pt-4 md:pt-4 flex-shrink-0 border-t border-[#E5E0DA]">
                        <div class="flex gap-4">
                            <button type="button" wire:click="$set('reviewModalOpen', false)" class="flex-1 bg-white border border-[#E5E0DA] text-gray-600 font-bold py-3 px-4 rounded hover:bg-gray-50 transition cursor-pointer text-sm tracking-wider uppercase">
                                Cancel
                            </button>
                            <button type="submit" class="flex-1 bg-[#800020] border border-[#800020] text-white font-bold py-3 px-4 rounded hover:bg-[#5D4037] transition shadow-md cursor-pointer text-sm tracking-wider uppercase">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- VIEW REVIEW MODAL --}}
    @if($viewReviewModalOpen && $viewingReview)
        <div x-data="{ _sy: 0 }" x-init="
            _sy = window.pageYOffset;
            document.body.style.position = 'fixed';
            document.body.style.top = '-' + _sy + 'px';
            document.body.style.width = '100%';
            document.body.style.overflowY = 'scroll';
            return () => {
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.width = '';
                document.body.style.overflowY = '';
                window.scrollTo(0, _sy);
            }
        " class="fixed inset-0 z-[9999] w-screen flex items-center justify-center p-4 md:p-8">
            <div class="fixed inset-0 bg-[#2A211F] opacity-50" wire:click="$set('viewReviewModalOpen', false)"></div>
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl relative z-10 animate-fade-in-up m-auto flex flex-col max-h-[90vh]">
                
                {{-- HEADER (Fixed) --}}
                <div class="p-6 md:p-8 pb-4 md:pb-4 flex-shrink-0 relative border-b border-[#E5E0DA]">
                    <button wire:click="$set('viewReviewModalOpen', false)" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition bg-transparent border-none cursor-pointer z-20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>

                    <h2 class="text-xl font-serif text-[#1b1c1a] m-0 mb-2">Your Review</h2>
                    <div class="flex gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ $i <= $viewingReview->rating ? 'text-yellow-500 fill-current' : 'text-gray-300 fill-current' }}" viewBox="0 0 24 24" stroke="none">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                        @endfor
                    </div>
                </div>

                {{-- BODY (Scrollable) --}}
                <div class="p-6 md:p-8 py-6 overflow-y-auto flex-grow hide-modal-scroll" style="scrollbar-width: none; -ms-overflow-style: none;">
                    <style>
                        .hide-modal-scroll::-webkit-scrollbar { display: none; }
                    </style>
                    
                    @if($viewingReview->comment)
                        <div class="mb-4">
                            <p class="text-gray-600 text-sm leading-relaxed break-words m-0" style="font-family: 'Manrope', sans-serif;">
                                "{{ $viewingReview->comment }}"
                            </p>
                        </div>
                    @endif
                    
                    @if(is_array($viewingReview->photos) && count($viewingReview->photos) > 0)
                        <div class="mb-4" x-data="{ localImageModalOpen: false, localModalImageSrc: '' }" x-init="$watch('localImageModalOpen', val => document.body.style.overflow = val ? 'hidden' : '')">
                            <span class="block text-[13px] font-bold text-gray-700 uppercase tracking-wider mb-2">Attached Photos</span>
                            <div class="flex gap-2 flex-wrap">
                                @foreach($viewingReview->photos as $photo)
                                    <a href="#" @click.prevent="localModalImageSrc = '{{ asset('storage/' . $photo) }}'; localImageModalOpen = true" class="block w-20 h-20 rounded overflow-hidden border border-[#E5E0DA] hover:opacity-80 transition">
                                        <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                                    </a>
                                @endforeach
                            </div>
                            
                            {{-- Review Image Lightbox Modal --}}
                            <template x-teleport="body">
                                <div x-show="localImageModalOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="display: none;">
                                    <div class="absolute inset-0 bg-black opacity-80" @click="localImageModalOpen = false"></div>
                                    <div class="relative z-10 w-full max-w-4xl max-h-[90vh] flex flex-col items-center justify-center">
                                        <button @click="localImageModalOpen = false" class="absolute -top-10 right-0 text-white hover:text-gray-300 transition bg-transparent border-none cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                        <img :src="localModalImageSrc" class="max-w-full max-h-[85vh] object-contain rounded shadow-2xl">
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endif
                    
                    @if($viewingReview->admin_reply)
                        <div class="mt-4 bg-[#F5F0EB] p-4 rounded-sm border-l-4 border-[#800020]">
                            <span class="font-bold text-[#800020] text-sm block mb-1">Response from Alpha Digital</span>
                            <p class="text-gray-700 text-sm leading-relaxed break-words m-0" style="font-family: 'Manrope', sans-serif;">
                                {{ $viewingReview->admin_reply }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- FOOTER (Fixed) --}}
                <div class="p-6 md:p-8 pt-4 md:pt-4 flex-shrink-0 border-t border-[#E5E0DA]">
                    <div class="flex gap-4">
                        <button type="button" wire:click="editReview({{ $viewingReview->product_id }})" class="flex-1 bg-white border border-[#E5E0DA] text-gray-600 font-bold py-3 px-4 rounded hover:bg-gray-50 transition cursor-pointer text-sm tracking-wider uppercase">
                            Edit Review
                        </button>
                        <button type="button" wire:click="$set('viewReviewModalOpen', false)" class="flex-1 bg-[#800020] border border-[#800020] text-white font-bold py-3 px-4 rounded hover:bg-[#5D4037] transition shadow-md cursor-pointer text-sm tracking-wider uppercase">
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>