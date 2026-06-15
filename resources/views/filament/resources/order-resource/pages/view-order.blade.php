<x-filament-panels::page>
@php
    $record = $this->record;
    $customer = $record->customer;
    $address = $customer?->addresses()->first();
    
    $items = $record->items;
    $subtotal = $items->sum(function($item) {
        return $item->price * $item->quantity;
    });
    $shipping = $record->total_amount - $subtotal;
    $shipping = $shipping > 0 ? $shipping : 0;
    
    $statusArray = ['new', 'processing', 'packed', 'shipped', 'delivered'];
    $currentStatusIndex = array_search(strtolower($record->status), $statusArray);
    $isCancelled = in_array(strtolower($record->status), ['canceled', 'cancelled']);

    if ($isCancelled) {
        // Calculate where the cancellation happened for accurate timeline rendering
        $history = \App\Models\OrderStatusHistory::where('order_id', $record->id)->whereIn('new_status', ['canceled', 'cancelled'])->latest()->first();
        $prev = $history ? strtolower($history->previous_status) : 'new';
        $timelineIndex = array_search($prev, $statusArray);
        if ($timelineIndex === false) $timelineIndex = 0;
    } else {
        $timelineIndex = $currentStatusIndex !== false ? $currentStatusIndex : 4; // Default to 4 if status is refund-related
    }
@endphp

<style>
    /* ALPHA DIGITAL BRAND COLORS INJECTED */
    .vo-wrapper { font-family: 'Manrope', sans-serif; color: #1b1c1a; }
    .vo-header {
        display: flex; justify-content: space-between; align-items: center;
        background: #ffffff; padding: 24px; border-radius: 8px; margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(87, 0, 19, 0.03);
    }
    .vo-order-title { font-size: 20px; font-weight: bold; margin: 0; color: #1b1c1a; font-family: 'Noto Serif', serif; }
    .vo-order-title span { color: #800020; }
    .vo-order-date { font-size: 13px; color: #A68A64; margin-top: 4px; font-weight: 500; }
    .vo-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; border-radius: 4px; font-size: 13px; font-weight: bold; cursor: pointer;
        transition: all 0.2s; text-transform: uppercase; letter-spacing: 0.05em; text-decoration: none;
    }
    .vo-btn:hover { opacity: 0.85; }
    
    .vo-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    
    .vo-card {
        background: #ffffff; border-radius: 8px; padding: 24px; margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(87, 0, 19, 0.03); border: 1px solid #f5f3ef;
    }
    .vo-card-title {
        font-size: 16px; font-weight: bold; color: #1b1c1a; margin-bottom: 20px;
        border-bottom: 1px solid #e0bfbf; padding-bottom: 12px; font-family: 'Noto Serif', serif;
    }
    
    /* Items Table */
    .vo-item-row {
        display: grid; grid-template-columns: 60px 1fr 100px 150px 100px; align-items: center; gap: 16px;
        padding: 16px 0; border-bottom: 1px solid #f5f3ef;
    }
    .vo-item-img { width: 60px; height: 60px; background: #f5f3ef; border-radius: 4px; object-fit: cover; }
    .vo-item-details h4 { margin: 0; font-size: 14px; color: #1b1c1a; font-weight: 600; font-family: 'Noto Serif', serif; }
    .vo-item-details p { margin: 4px 0 0; font-size: 12px; color: #A68A64; }
    .vo-item-weight { font-size: 13px; color: #5D4037; }
    .vo-item-price { font-size: 13px; color: #5D4037; }
    .vo-item-total { font-size: 14px; font-weight: bold; color: #1b1c1a; text-align: right; }
    
    /* Summary */
    .vo-summary-container { display: flex; justify-content: space-between; margin-top: 24px; }
    .vo-order-note { flex: 1; margin-right: 40px; font-size: 13px; color: #5D4037; line-height: 1.6; }
    .vo-order-note strong { color: #1b1c1a; display: block; margin-bottom: 8px; font-family: 'Noto Serif', serif; }
    .vo-summary-box { width: 300px; }
    .vo-summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: #5D4037; font-weight: 500; }
    .vo-summary-total {
        display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px;
        border-top: 1px solid #e0bfbf; font-size: 18px; font-weight: bold; color: #800020; font-family: 'Noto Serif', serif;
    }
    
    /* Customer Details */
    .vo-customer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .vo-customer-info { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #1b1c1a; margin-bottom: 16px; font-weight: 500; }
    .vo-customer-info svg { width: 18px; height: 18px; color: #A68A64; }
    .vo-address { font-size: 14px; color: #5D4037; line-height: 1.6; }
    
    /* Timeline */
    .vo-timeline { position: relative; padding-left: 24px; }
    .vo-timeline::before {
        content: ''; position: absolute; left: 24px; top: 24px; bottom: 24px; width: 1px; background: #e0bfbf; z-index: 0;
    }
    .vo-timeline-step { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 32px; position: relative; z-index: 1; }
    .vo-timeline-icon {
        width: 48px; height: 48px; border-radius: 50%; background: #ffffff; border: 1px solid #e0bfbf;
        display: flex; align-items: center; justify-content: center; color: #A68A64; margin-left: -24px;
    }
    .vo-timeline-icon.active { border-color: #800020; color: #800020; background: rgba(128,0,32,0.05); }
    .vo-timeline-content { flex: 1; padding-top: 4px; }
    .vo-timeline-title { font-size: 14px; font-weight: bold; color: #1b1c1a; display: flex; align-items: center; gap: 8px; }
    .vo-timeline-date { font-size: 12px; color: #A68A64; margin-top: 4px; }
    .vo-check-icon { color: #800020; width: 16px; height: 16px; }
    
    /* Admin Notes Scrollbar styling */
    .vo-notes-scroll::-webkit-scrollbar { width: 4px; }
    .vo-notes-scroll::-webkit-scrollbar-track { background: #fbf9f5; }
    .vo-notes-scroll::-webkit-scrollbar-thumb { background: #e0bfbf; border-radius: 4px; }
    
    @media (max-width: 1024px) { .vo-grid { grid-template-columns: 1fr; } }
    
    /* Dark Mode Overrides */
    .dark .vo-wrapper { color: #f3f4f6; }
    .dark .vo-header { background: #1f2937; box-shadow: none; border: 1px solid rgba(255,255,255,0.05); }
    .dark .vo-order-title { color: #f3f4f6; }
    .dark .vo-order-title span { color: #fca5a5; }
    .dark .vo-order-date { color: #d1d5db; }
    .dark .vo-card { background: #1f2937; border: 1px solid rgba(255,255,255,0.05); box-shadow: none; }
    .dark .vo-card-title { color: #f3f4f6; border-color: rgba(255,255,255,0.1); }
    .dark .vo-item-row { border-color: rgba(255,255,255,0.05); }
    .dark .vo-item-img { background: #374151; }
    .dark .vo-item-details h4 { color: #f3f4f6; }
    .dark .vo-item-details p { color: #9ca3af; }
    .dark .vo-item-weight { color: #9ca3af; }
    .dark .vo-item-price { color: #9ca3af; }
    .dark .vo-item-total { color: #f3f4f6; }
    .dark .vo-order-note { color: #d1d5db; }
    .dark .vo-order-note strong { color: #f3f4f6; }
    .dark .vo-summary-row { color: #d1d5db; }
    .dark .vo-summary-total { border-color: rgba(255,255,255,0.1); color: #fca5a5; }
    .dark .vo-customer-info { color: #f3f4f6; }
    .dark .vo-customer-info svg { color: #fbbf24; }
    .dark .vo-address { color: #d1d5db; }
    .dark .vo-timeline::before { background: rgba(255,255,255,0.1); }
    .dark .vo-timeline-icon { background: #1f2937; border-color: rgba(255,255,255,0.1); color: #9ca3af; }
    .dark .vo-timeline-icon.active { border-color: #fca5a5; color: #fca5a5; background: rgba(252, 165, 165, 0.1); }
    .dark .vo-timeline-title { color: #f3f4f6; }
    .dark .vo-timeline-date { color: #9ca3af; }
    .dark .vo-check-icon { color: #fca5a5; }
    .dark .vo-notes-scroll::-webkit-scrollbar-track { background: transparent; }
    .dark .vo-notes-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); }
    
    /* Button Overrides (important needed for inline styles) */
    .dark .vo-btn { border-color: rgba(255,255,255,0.2) !important; color: #f3f4f6 !important; background-color: transparent !important; }
    .dark .vo-btn:hover { background-color: rgba(255,255,255,0.1) !important; }
    
    /* Global Inline Color Overrides */
    .dark .vo-wrapper [style*="color: #1b1c1a"] { color: #f3f4f6 !important; }
    .dark .vo-wrapper [style*="background-color: #ffffff"] { background-color: #1f2937 !important; border-color: rgba(255,255,255,0.1) !important; color: #f3f4f6 !important; }
    
    /* Internal Admin Notes Overrides */
    .dark .vo-card[style*="background-color: #fbf9f5;"] { background-color: #1f2937 !important; }
    .dark .vo-card[style*="background-color: #fbf9f5;"] > div > h3 { color: #fca5a5 !important; }
    .dark .vo-card[style*="background-color: #fbf9f5;"] .vo-notes-scroll > div { background: #374151 !important; border-color: rgba(255,255,255,0.1) !important; color: #d1d5db !important; }
    .dark .vo-card[style*="background-color: #fbf9f5;"] .vo-notes-scroll > div strong { color: #fca5a5 !important; }
    .dark .vo-card[style*="background-color: #fbf9f5;"] textarea { background: #1f2937 !important; border-color: rgba(255,255,255,0.1) !important; color: #f3f4f6 !important; }
</style>

@php
    $badgeStyle = match (strtolower($record->status)) {
        'new' => 'color: #800020; background-color: rgba(128,0,32,0.1); border-color: rgba(128,0,32,0.2);',
        'processing' => 'color: #A68A64; background-color: rgba(166,138,100,0.1); border-color: rgba(166,138,100,0.2);',
        'packed' => 'color: #5D4037; background-color: rgba(93,64,55,0.1); border-color: rgba(93,64,55,0.2);',
        'shipped' => 'color: #4338ca; background-color: #eef2ff; border-color: rgba(79,70,229,0.2);',
        'delivered' => 'color: #15803d; background-color: #f0fdf4; border-color: rgba(22,163,74,0.2);',
        'cancelled', 'canceled' => 'color: #7f1d1d; background-color: #fef2f2; border-color: rgba(127,29,29,0.2);',
        'refund_requested' => 'color: #d97706; background-color: #fffbeb; border-color: rgba(217,119,6,0.2);',
        'refund_approved' => 'color: #2563eb; background-color: #eff6ff; border-color: rgba(37,99,235,0.2);',
        'refund_rejected' => 'color: #dc2626; background-color: #fef2f2; border-color: rgba(220,38,38,0.2);',
        'refunded' => 'color: #16a34a; background-color: #f0fdf4; border-color: rgba(22,163,74,0.2);',
        default => 'color: #4b5563; background-color: #f9fafb; border-color: rgba(107,114,128,0.1);',
    };
@endphp

<div class="vo-wrapper">
    
    {{-- Header --}}
    <div class="vo-header">
        <div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <h2 class="vo-order-title">Order <span>#{{ $record->order_number }}</span></h2>
                <span style="display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; text-transform: uppercase; border: 1px solid transparent; {{ $badgeStyle }}">
                    {{ str_replace('_', ' ', $record->status) }}
                </span>
            </div>
            <div class="vo-order-date">{{ $record->created_at->format('M d, Y \a\t h:i A') }}</div>
        </div>
        
        <div class="vo-header-actions" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; justify-content: flex-end;">
            
            {{-- PURE LIVEWIRE HTML WORKFLOW BUTTONS --}}
            @if(strtolower($record->status) === 'new')
                <button wire:click="updateOrderStatus('processing')" wire:confirm="Move order to Processing?" class="vo-btn" style="border: 1px solid #A68A64; color: #A68A64; background: transparent;">
                    Move to Processing
                </button>
                <button wire:click="openCancelModal" class="vo-btn" style="border: 1px solid #570013; color: #570013; background: transparent;">
                    Cancel Order
                </button>

            @elseif(strtolower($record->status) === 'processing')
                <button wire:click="updateOrderStatus('packed')" wire:confirm="Mark order as Packed?" class="vo-btn" style="border: 1px solid #5D4037; color: #5D4037; background: transparent;">
                    Mark as Packed
                </button>
                <button wire:click="openCancelModal" class="vo-btn" style="border: 1px solid #570013; color: #570013; background: transparent;">
                    Cancel Order
                </button>

            @elseif(strtolower($record->status) === 'packed')
                
                {{-- Shipment Details Button --}}
                <button wire:click="openShippingModal" class="vo-btn" style="border: 1px solid #A68A64; color: #A68A64; background: transparent;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Shipment Details
                </button>

                {{-- Mark as Shipped (Disabled until details exist) --}}
                @if(!empty($record->courier_partner) && !empty($record->tracking_number))
                    <button wire:click="updateOrderStatus('shipped')" wire:confirm="Mark order as Shipped?" class="vo-btn" style="background: #800020; color: #ffffff; border: 1px solid #800020;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        Mark as Shipped
                    </button>
                @else
                    <button disabled class="vo-btn" style="background: #f5f3ef; color: #A68A64; border: 1px solid #e0bfbf; cursor: not-allowed;" title="Please fill Shipment Details first">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                        Mark as Shipped
                    </button>
                @endif
                
                {{-- ADMIN RULE: Admin can cancel at Packed --}}
                <button wire:click="openCancelModal" class="vo-btn" style="border: 1px solid #570013; color: #570013; background: transparent;">
                    Cancel Order
                </button>

            @elseif(strtolower($record->status) === 'shipped')
                <button wire:click="updateOrderStatus('delivered')" wire:confirm="Mark order as Delivered?" class="vo-btn" style="background: #570013; color: #ffffff; border: 1px solid #570013;">
                    Mark as Delivered
                </button>
            @endif

            {{-- REFUND WORKFLOW BUTTONS --}}
            @if(strtolower($record->status) === 'refund_requested')
                <button wire:click="approveRefund" wire:confirm="Approve this refund request?" class="vo-btn" style="background: #059669; color: #ffffff; border: 1px solid #059669;">
                    Approve Refund
                </button>
                <button wire:click="openRejectRefundModal" class="vo-btn" style="background: transparent; color: #dc2626; border: 1px solid #dc2626;">
                    Reject Refund
                </button>
            @endif

            {{-- PROCESS REFUND AFTER CANCEL/APPROVE --}}
            @if(in_array(strtolower($record->status), ['refund_approved', 'cancelled', 'canceled']))
                @if($record->refund_required && strtolower($record->status) !== 'refunded')
                <button wire:click="processRefund" wire:confirm="Are you sure you want to process the refund? This will mark it as refunded." class="vo-btn" style="background: #570013; color: #ffffff; border: 1px solid #570013;">
                    Process Refund
                </button>
                @endif
            @endif

            {{-- INVENTORY RESTORATION (Manual Trigger) --}}
            @if(in_array(strtolower($record->status), ['refund_requested', 'refund_approved', 'refunded']) && !$record->stock_restored)
                <button wire:click="verifyReturnAndRestoreInventory" wire:confirm="Has the returned product been physically received and accepted? This will restore stock." class="vo-btn" style="background: transparent; color: #d97706; border: 1px solid #d97706;">
                    Verify Return & Restore Stock
                </button>
            @endif

            {{-- EDIT BUTTON --}}
            @if(!in_array(strtolower($record->status), ['canceled', 'cancelled', 'refunded']))
                @php $editUrl = \App\Filament\Resources\OrderResource::getUrl('edit', ['record' => $record]); @endphp
                <a href="{{ $editUrl }}" class="vo-btn" style="border: 1px solid #800020; color: #800020; background: transparent;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit
                </a>
            @endif

            {{-- Standard Print Invoice Action --}}
            <button class="vo-btn" style="border: 1px solid #e0bfbf; color: #1b1c1a; background: transparent;" onclick="window.print()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Print Invoice
            </button>

        </div>
    </div>

    <div class="vo-grid">
        
        {{-- Left Column --}}
        <div class="vo-main-col">
            
            {{-- Ordered Items --}}
            <div class="vo-card">
                <h3 class="vo-card-title">Ordered Items</h3>
                
                <div class="vo-items-list">
                    @foreach($items as $item)
                        @php
                            $product = $item->product;
                            $img = ($product && is_array($product->images) && count($product->images) > 0) 
                                ? asset('storage/' . $product->images[0]) 
                                : 'https://via.placeholder.com/60';
                        @endphp
                        <div class="vo-item-row">
                            <img src="{{ $img }}" alt="{{ $product->name ?? 'Product' }}" class="vo-item-img">
                            <div class="vo-item-details">
                                <h4>{{ $product->name ?? 'Unknown Product' }}</h4>
                                <p>SKU: {{ $product->id ?? 'N/A' }}</p>
                            </div>
                            <div class="vo-item-weight">
                                0.5 kg
                            </div>
                            <div class="vo-item-price">
                                ₹{{ number_format($item->price, 2) }} &times; {{ $item->quantity }}
                            </div>
                            <div class="vo-item-total">
                                ₹{{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="vo-summary-container">
                    
                    {{-- Customer Note Injection --}}
                    <div class="vo-order-note">
                        <strong>Order Note</strong>
                        @if($record->customer_note)
                            {!! nl2br(e($record->customer_note)) !!}
                        @else
                            <span style="font-style: italic; color: #A68A64;">No special instructions provided.</span>
                        @endif
                    </div>

                    <div class="vo-summary-box">
                        <div class="vo-summary-row">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="vo-summary-row">
                            <span>Shipping</span>
                            <span>₹{{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="vo-summary-row">
                            <span>Tax</span>
                            <span>₹0.00</span>
                        </div>
                        <div class="vo-summary-total">
                            <span>Total</span>
                            <span>₹{{ number_format($record->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Customer Details --}}
            <div class="vo-card">
                <h3 class="vo-card-title">Customer Details</h3>
                <div class="vo-customer-grid">
                    <div>
                        <div class="vo-customer-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            {{ $customer->name ?? 'Guest User' }}
                        </div>
                        <div class="vo-customer-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $customer->email ?? 'No email provided' }}
                        </div>
                        <div class="vo-customer-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            {{ $customer->phone ?? 'No phone provided' }}
                        </div>
                        <div class="vo-customer-info">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            Payment: {{ ucfirst($record->payment_status) }}
                        </div>
                    </div>
                    <div>
                        @if($address)
                            <div class="vo-address">
                                <strong>{{ $address->first_name }} {{ $address->last_name }}</strong><br>
                                {{ $address->address_1 }}<br>
                                @if($address->address_2) {{ $address->address_2 }}<br> @endif
                                {{ $address->city }}, {{ $address->province }}<br>
                                {{ $address->country }}, {{ $address->postal_code }}
                            </div>
                        @else
                            <div class="vo-address text-gray-400 italic">
                                No shipping address on file.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- Right Column --}}
        <div class="vo-side-col">
            
            {{-- Order History --}}
            <div class="vo-card">
                <h3 class="vo-card-title">Order History</h3>
                <div class="vo-timeline">
                    
                    {{-- Step 1: Placed --}}
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $timelineIndex >= 0 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Order Placed 
                                @if($timelineIndex >= 0) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                            <div class="vo-timeline-date">{{ $record->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    
                    {{-- Step 2: Processing --}}
                    @if(!$isCancelled || $timelineIndex >= 1)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $timelineIndex >= 1 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Processing 
                                @if($timelineIndex >= 1) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Step 3: Packed --}}
                    @if(!$isCancelled || $timelineIndex >= 2)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $timelineIndex >= 2 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Packed 
                                @if($timelineIndex >= 2) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Step 4: Shipped --}}
                    @if(!$isCancelled || $timelineIndex >= 3)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $timelineIndex >= 3 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Shipped 
                                @if($timelineIndex >= 3) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Step 5: Delivered --}}
                    @if(!$isCancelled || $timelineIndex >= 4)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $timelineIndex >= 4 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Delivered 
                                @if($timelineIndex >= 4) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Cancelled Step --}}
                    @if($isCancelled)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon active" style="border-color: #570013; color: #570013; background: rgba(87,0,19,0.05);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title" style="color: #570013;">
                                Cancelled 
                                <svg class="vo-check-icon" style="color: #570013;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            </div>
                            <div class="vo-timeline-date">{{ $record->cancelled_at ? \Carbon\Carbon::parse($record->cancelled_at)->format('d M Y, h:i A') : 'N/A' }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- REFUND TIMELINE STEPS --}}
                    @if($record->refund_requested_at)
                    <div class="vo-timeline-step mt-4">
                        <div class="vo-timeline-icon active" style="border-color: #d97706; color: #d97706; background: rgba(217,119,6,0.05);">R</div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title" style="color: #d97706;">Refund Requested</div>
                            <div class="vo-timeline-date">{{ \Carbon\Carbon::parse($record->refund_requested_at)->format('d M Y') }}</div>
                        </div>
                    </div>
                    @endif

                    @if($record->refund_approved_at)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon active" style="border-color: #059669; color: #059669; background: rgba(5,150,105,0.05);">
                            <svg class="vo-check-icon" style="color: #059669;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title" style="color: #059669;">Refund Approved</div>
                        </div>
                    </div>
                    @elseif($record->refund_rejected_at)
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon active" style="border-color: #dc2626; color: #dc2626; background: rgba(220,38,38,0.05);">X</div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title" style="color: #dc2626;">Refund Rejected</div>
                        </div>
                    </div>
                    @endif

                    @if($record->refund_processed_at || strtolower($record->status) === 'refunded')
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon active" style="border-color: #4f46e5; color: #4f46e5; background: rgba(79,70,229,0.05);">
                            <svg class="vo-check-icon" style="color: #4f46e5;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title" style="color: #4f46e5;">Refund Completed</div>
                            <div class="vo-timeline-date">{{ $record->refund_processed_at ? \Carbon\Carbon::parse($record->refund_processed_at)->format('d M Y') : '' }}</div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            
            {{-- Cancellation Details Card --}}
            @if($isCancelled)
            <div class="vo-card">
                <h3 class="vo-card-title" style="color: #570013; border-color: #e0bfbf;">Cancellation Details</h3>
                <div class="vo-summary-box" style="width: 100%;">
                    <div class="vo-summary-row">
                        <span>Cancelled By</span>
                        <span style="font-weight: bold; color: #1b1c1a;">
                            @if($record->cancelled_by_role === 'customer')
                                Customer
                            @else
                                {{ $record->cancelledBy->name ?? 'Admin' }}
                            @endif
                        </span>
                    </div>
                    <div class="vo-summary-row">
                        <span>Refund Required</span>
                        <span style="font-weight: bold; color: {{ $record->refund_required ? '#800020' : '#1b1c1a' }};">
                            {{ $record->refund_required ? 'Yes (Pending)' : 'No' }}
                        </span>
                    </div>
                    <div style="margin-top: 12px; font-size: 13px; color: #5D4037;">
                        <strong style="color: #1b1c1a; display: block; margin-bottom: 4px; font-family: 'Noto Serif', serif;">Reason for Cancellation:</strong>
                        {{ $record->cancellation_reason ?? 'No reason provided.' }}
                    </div>
                    
                    
                    <div class="vo-summary-row" style="margin-top: 12px;">
                        <span>Stock Restored?</span>
                        <span style="font-weight: bold; color: {{ $record->stock_restored ? '#059669' : '#d97706' }};">
                            {{ $record->stock_restored ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($record->stock_restored)
                        <div style="margin-top: 8px; font-size: 12px; color: #4b5563;">
                            Restored At: {{ $record->stock_restored_at ? \Carbon\Carbon::parse($record->stock_restored_at)->format('d M Y, h:i A') : 'N/A' }}<br>
                            Restored By: {{ $record->stockRestoredBy->name ?? ($record->cancelled_by_role === 'customer' ? 'Customer (Auto)' : 'Admin (Auto)') }}
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- REFUND DETAILS CARD --}}
            @if($record->refund_requested_at)
            <div class="vo-card">
                <h3 class="vo-card-title" style="color: #d97706; border-color: #e0bfbf;">Refund Details</h3>
                <div class="vo-summary-box" style="width: 100%;">
                    <div class="vo-summary-row">
                        <span>Refund Status</span>
                        <span style="font-weight: bold; color: #1b1c1a;">{{ strtoupper(str_replace('_', ' ', $record->status)) }}</span>
                    </div>
                    <div class="vo-summary-row" style="margin-top: 8px;">
                        <span>Request Date</span>
                        <span style="font-weight: bold; color: #1b1c1a;">{{ \Carbon\Carbon::parse($record->refund_requested_at)->format('d M Y, h:i A') }}</span>
                    </div>
                    <div style="margin-top: 12px; font-size: 13px; color: #5D4037;">
                        <strong style="color: #1b1c1a; display: block; margin-bottom: 4px;">Refund Reason:</strong>
                        {{ $record->refund_reason }}
                        @if($record->refund_reason === 'Other' && $record->refund_custom_reason)
                            - {{ $record->refund_custom_reason }}
                        @endif
                    </div>
                    
                    @if($record->refund_approved_at)
                        <div class="vo-summary-row" style="margin-top: 12px;">
                            <span>Approved By</span>
                            <span style="font-weight: bold; color: #059669;">{{ $record->refundApprovedBy->name ?? 'Admin' }}</span>
                        </div>
                        <div class="vo-summary-row" style="margin-top: 8px;">
                            <span>Approved At</span>
                            <span style="font-weight: bold; color: #1b1c1a;">{{ \Carbon\Carbon::parse($record->refund_approved_at)->format('d M Y, h:i A') }}</span>
                        </div>
                    @endif
                    
                    @if($record->refund_rejected_at)
                        <div class="vo-summary-row" style="margin-top: 12px;">
                            <span>Rejected By</span>
                            <span style="font-weight: bold; color: #dc2626;">{{ $record->refundRejectedBy->name ?? 'Admin' }}</span>
                        </div>
                        <div class="vo-summary-row" style="margin-top: 8px;">
                            <span>Rejected At</span>
                            <span style="font-weight: bold; color: #1b1c1a;">{{ \Carbon\Carbon::parse($record->refund_rejected_at)->format('d M Y, h:i A') }}</span>
                        </div>
                        <div style="font-size: 13px; color: #dc2626; margin-top: 4px;">
                            <strong style="display: block;">Rejection Reason:</strong>
                            {{ $record->refund_rejection_reason }}
                        </div>
                    @endif
                    
                    @if($record->refund_processed_at || strtolower($record->status) === 'refunded')
                        <div class="vo-summary-row" style="margin-top: 12px;">
                            <span>Processed By</span>
                            <span style="font-weight: bold; color: #4f46e5;">{{ $record->refundProcessedBy->name ?? 'Admin' }}</span>
                        </div>
                        <div class="vo-summary-row" style="margin-top: 8px;">
                            <span>Refund Date</span>
                            <span style="font-weight: bold; color: #1b1c1a;">{{ $record->refund_processed_at ? \Carbon\Carbon::parse($record->refund_processed_at)->format('d M Y, h:i A') : 'N/A' }}</span>
                        </div>
                    @endif
                    
                    <div class="vo-summary-row" style="margin-top: 12px;">
                        <span>Return Received?</span>
                        <span style="font-weight: bold; color: {{ $record->return_received_at ? '#059669' : '#d97706' }};">
                            {{ $record->return_received_at ? 'Yes' : 'No (Pending Return)' }}
                        </span>
                    </div>
                    @if($record->return_received_at)
                        <div style="margin-top: 8px; font-size: 12px; color: #4b5563;">
                            Received At: {{ \Carbon\Carbon::parse($record->return_received_at)->format('d M Y, h:i A') }}<br>
                            Verified By: {{ $record->returnVerifiedBy->name ?? 'Admin' }}
                        </div>
                    @endif

                    <div class="vo-summary-row" style="margin-top: 12px;">
                        <span>Stock Restored?</span>
                        <span style="font-weight: bold; color: {{ $record->stock_restored ? '#059669' : '#d97706' }};">
                            {{ $record->stock_restored ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($record->stock_restored && $record->stock_restored_at)
                        <div style="margin-top: 8px; font-size: 12px; color: #4b5563;">
                            Restored At: {{ \Carbon\Carbon::parse($record->stock_restored_at)->format('d M Y, h:i A') }}<br>
                            Restored By: {{ $record->stockRestoredBy->name ?? 'Admin' }}
                        </div>
                    @endif

                    @if($record->refund_evidence && is_array($record->refund_evidence) && count($record->refund_evidence) > 0)
                        <div style="margin-top: 16px;">
                            <strong style="color: #1b1c1a; display: block; margin-bottom: 8px;">Uploaded Evidence:</strong>
                            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                @foreach($record->refund_evidence as $ev)
                                    <a href="{{ Storage::url($ev) }}" target="_blank">
                                        <img src="{{ Storage::url($ev) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #e0bfbf;">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Shipping Details Card --}}
            @if($record->courier_partner || $record->tracking_number)
            <div class="vo-card">
                <h3 class="vo-card-title">Shipping Details</h3>
                <div class="vo-summary-box" style="width: 100%;">
                    @if($record->courier_partner)
                    <div class="vo-summary-row">
                        <span>Courier Partner</span>
                        <span style="font-weight: bold; color: #1b1c1a;">{{ $record->courier_partner }}</span>
                    </div>
                    @endif
                    @if($record->tracking_number)
                    <div class="vo-summary-row">
                        <span>Tracking Number</span>
                        <span style="font-weight: bold; color: #1b1c1a;">{{ $record->tracking_number }}</span>
                    </div>
                    @endif
                    @if($record->shipping_date)
                    <div class="vo-summary-row">
                        <span>Shipped On</span>
                        <span style="font-weight: bold; color: #1b1c1a;">{{ \Carbon\Carbon::parse($record->shipping_date)->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($record->expected_delivery_date)
                    <div class="vo-summary-row">
                        <span>Expected Delivery</span>
                        <span style="font-weight: bold; color: #1b1c1a;">{{ \Carbon\Carbon::parse($record->expected_delivery_date)->format('d M Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            {{-- Internal Admin Notes Card (WITH EDIT & DELETE) --}}
            <div class="vo-card" style="background-color: #fbf9f5;">
                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e0bfbf; padding-bottom: 12px; margin-bottom: 20px;">
                    <h3 style="font-size: 16px; font-weight: bold; color: #800020; margin: 0; font-family: 'Noto Serif', serif; display: flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Internal Admin Notes
                    </h3>
                </div>

                {{-- Notes List (Scrollable) --}}
                <div class="vo-notes-scroll" style="margin-bottom: 16px; max-height: 280px; overflow-y: auto; padding-right: 8px;">
                    @forelse($record->notes as $note)
                        <div style="background: #ffffff; border: 1px solid #e0bfbf; border-radius: 6px; padding: 12px; margin-bottom: 12px;">
                            
                            @if($editingNoteId === $note->id)
                                {{-- EDITING STATE --}}
                                <textarea wire:model="editNoteContent" rows="3" class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a; width: 100%; box-sizing: border-box; resize: vertical; font-family: 'Manrope', sans-serif;"></textarea>
                                @error('editNoteContent') <span style="color: #ef4444; font-size: 11px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                                <div style="display: flex; justify-content: flex-end; gap: 8px; margin-top: 8px;">
                                    <button wire:click="cancelEditingNote" class="vo-btn" style="background: transparent; color: #5D4037; border: 1px solid #e0bfbf; padding: 4px 12px; font-size: 11px;">Cancel</button>
                                    <button wire:click="saveEditedNote" class="vo-btn" style="background: #800020; color: #ffffff; border: 1px solid #800020; padding: 4px 12px; font-size: 11px;">Save Changes</button>
                                </div>
                            @else
                                {{-- DISPLAY STATE --}}
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 12px; color: #A68A64; align-items: flex-start;">
                                    <div>
                                        <strong style="color: #800020;">{{ $note->user->name ?? 'Admin' }}</strong>
                                        <span style="margin-left: 4px;">{{ $note->created_at->format('M d, Y \a\t g:i A') }}</span>
                                        @if($note->created_at != $note->updated_at)
                                            <span style="font-style: italic; font-size: 10px; margin-left: 4px;">(Edited)</span>
                                        @endif
                                    </div>
                                    
                                    <div style="display: flex; gap: 8px;">
                                        <button wire:click="startEditingNote({{ $note->id }})" style="background: transparent; border: none; cursor: pointer; color: #A68A64; padding: 0;" title="Edit Note">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </button>
                                        <button wire:click="deleteNote({{ $note->id }})" wire:confirm="Are you sure you want to completely delete this internal note?" style="background: transparent; border: none; cursor: pointer; color: #570013; padding: 0;" title="Delete Note">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>
                                <div style="font-size: 13px; color: #1b1c1a; line-height: 1.6; white-space: pre-wrap;">{{ $note->note }}</div>
                            @endif
                        </div>
                    @empty
                        <div style="font-size: 13px; color: #A68A64; font-style: italic; text-align: center; padding: 12px 0;">
                            No internal notes yet.
                        </div>
                    @endforelse
                </div>

                {{-- Add Note Form --}}
                <div style="border-top: 1px solid #e0bfbf; padding-top: 16px;">
                    <textarea wire:model.defer="new_note" rows="3" placeholder="Type an internal note here..." class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a; width: 100%; box-sizing: border-box; resize: vertical; font-family: 'Manrope', sans-serif;"></textarea>
                    @error('new_note') <span style="color: #ef4444; font-size: 11px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
                    
                    <div style="text-align: right; margin-top: 12px;">
                        <button wire:click="addNote" class="vo-btn" style="background: #ffffff; color: #800020; border: 1px solid #800020;">
                            Add Note
                        </button>
                    </div>
                </div>
            </div>

        </div>
        
    </div>

    {{-- NATIVE LIVEWIRE CANCELLATION MODAL --}}
    @if($cancelModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(27, 28, 26, 0.7);">
        <div class="rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden" style="font-family: 'Manrope', sans-serif; background-color: #fbf9f5; border: 1px solid #e0bfbf;">
            
            <div class="px-6 py-4 flex justify-between items-center" style="background-color: #f5f3ef; border-bottom: 1px solid #e0bfbf;">
                <h3 class="text-lg font-bold m-0" style="color: #1b1c1a; font-family: 'Noto Serif', serif;">Cancel Order</h3>
                <button wire:click="$set('cancelModalOpen', false)" style="color: #A68A64; background: transparent; border: none; cursor: pointer; transition: 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1" style="color: #5D4037;">Reason for Cancellation *</label>
                    <select wire:model.live="cancellation_reason" class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a;">
                        <option value="">Select a reason...</option>
                        <option value="Customer Request">Customer Request</option>
                        <option value="Out Of Stock">Out Of Stock</option>
                        <option value="Damaged Product">Damaged Product</option>
                        <option value="Inventory Mismatch">Inventory Mismatch</option>
                        <option value="Address Issue">Address Issue</option>
                        <option value="Duplicate Order">Duplicate Order</option>
                        <option value="Payment Issue">Payment Issue</option>
                        <option value="Admin Decision">Admin Decision</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('cancellation_reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                @if($cancellation_reason === 'Other')
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1" style="color: #5D4037;">Specify Reason *</label>
                    <textarea wire:model.defer="custom_cancellation_reason" rows="3" placeholder="Please provide details..." class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a; resize: vertical;"></textarea>
                    @error('custom_cancellation_reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
            
            <div class="px-6 py-4 flex justify-end gap-3" style="background-color: #f5f3ef; border-top: 1px solid #e0bfbf;">
                <button wire:click="$set('cancelModalOpen', false)" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer" style="background-color: transparent; border: 1px solid #e0bfbf; color: #1b1c1a;">Close</button>
                <button wire:click="confirmCancellation" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer" style="background-color: #570013; border: 1px solid #570013; color: #ffffff;">Confirm Cancellation</button>
            </div>
            
        </div>
    </div>
    @endif

    {{-- REJECT REFUND MODAL --}}
    @if($rejectRefundModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(27, 28, 26, 0.7);">
        <div class="rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden" style="background-color: #fbf9f5; border: 1px solid #e0bfbf; font-family: 'Manrope', sans-serif;">
            <div class="px-6 py-4 flex justify-between items-center" style="background-color: #f5f3ef; border-bottom: 1px solid #e0bfbf;">
                <h3 class="text-lg font-bold m-0" style="color: #dc2626; font-family: 'Noto Serif', serif;">Reject Refund Request</h3>
            </div>
            <div class="p-6">
                <label class="block text-sm font-bold mb-1" style="color: #5D4037;">Rejection Reason *</label>
                <textarea wire:model.defer="refund_rejection_reason" rows="3" placeholder="Provide a detailed reason for rejecting this refund..." class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a; resize: vertical;"></textarea>
                @error('refund_rejection_reason') <span style="color: #ef4444; font-size: 11px; display: block; margin-top: 4px;">{{ $message }}</span> @enderror
            </div>
            <div class="px-6 py-4 flex justify-end gap-3" style="background-color: #f5f3ef; border-top: 1px solid #e0bfbf;">
                <button wire:click="$set('rejectRefundModalOpen', false)" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer" style="border: 1px solid #e0bfbf;">Cancel</button>
                <button wire:click="confirmRejectRefund" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer" style="background-color: #dc2626; color: #ffffff; border: 1px solid #dc2626;">Reject Refund</button>
            </div>
        </div>
    </div>
    @endif

    {{-- NATIVE LIVEWIRE SHIPPING MODAL WITH BRAND COLORS --}}
    @if($shippingModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background-color: rgba(27, 28, 26, 0.7);">
        <div class="rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden" style="font-family: 'Manrope', sans-serif; background-color: #fbf9f5; border: 1px solid #e0bfbf;">
            
            <div class="px-6 py-4 flex justify-between items-center" style="background-color: #f5f3ef; border-bottom: 1px solid #e0bfbf;">
                <h3 class="text-lg font-bold m-0" style="color: #1b1c1a; font-family: 'Noto Serif', serif;">Shipment Details</h3>
                <button wire:click="$set('shippingModalOpen', false)" style="color: #A68A64; background: transparent; border: none; cursor: pointer; transition: 0.2s;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1" style="color: #5D4037;">Courier Partner *</label>
                    <input type="text" wire:model.defer="courier_partner" placeholder="e.g. BlueDart, FedEx" class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a;">
                    @error('courier_partner') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-1" style="color: #5D4037;">Tracking Number *</label>
                    <input type="text" wire:model.defer="tracking_number" placeholder="Enter tracking number" class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a;">
                    @error('tracking_number') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="mb-2">
                    <label class="block text-sm font-bold mb-1" style="color: #5D4037;">Expected Delivery Date</label>
                    <input type="date" wire:model.defer="expected_delivery_date" class="w-full rounded-md px-3 py-2 text-sm focus:outline-none" style="border: 1px solid #e0bfbf; background-color: #ffffff; color: #1b1c1a;">
                    @error('expected_delivery_date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="px-6 py-4 flex justify-end gap-3" style="background-color: #f5f3ef; border-top: 1px solid #e0bfbf;">
                <button wire:click="$set('shippingModalOpen', false)" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer" style="background-color: transparent; border: 1px solid #e0bfbf; color: #1b1c1a;">Cancel</button>
                <button wire:click="saveShippingDetails" class="px-4 py-2 rounded-md text-sm font-bold cursor-pointer" style="background-color: #800020; border: 1px solid #800020; color: #ffffff;">Save Details</button>
            </div>
            
        </div>
    </div>
    @endif

</div>
</x-filament-panels::page>