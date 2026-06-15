@php
    $record = $getRecord();
    $customerName = $record->customer->name ?? 'Guest';
    $initials = collect(explode(' ', $customerName))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
    
    // Status colors mapping for inline styles
    $statusStyle = match (strtolower($record->status)) {
        'new' => 'color: #800020; background-color: rgba(128,0,32,0.1); border-color: rgba(128,0,32,0.2);',
        'processing' => 'color: #A68A64; background-color: rgba(166,138,100,0.1); border-color: rgba(166,138,100,0.2);',
        'packed' => 'color: #5D4037; background-color: rgba(93,64,55,0.1); border-color: rgba(93,64,55,0.2);',
        'shipped' => 'color: #4338ca; background-color: #eef2ff; border-color: rgba(79,70,229,0.2);',
        'delivered' => 'color: #15803d; background-color: #f0fdf4; border-color: rgba(22,163,74,0.2);',
        'cancelled', 'canceled' => 'color: #7f1d1d; background-color: #fef2f2; border-color: rgba(127,29,29,0.2);',
        'refund_requested' => 'color: #d97706; background-color: #fffbeb; border-color: rgba(217,119,6,0.2);', // Yellow
        'refund_approved' => 'color: #2563eb; background-color: #eff6ff; border-color: rgba(37,99,235,0.2);', // Blue
        'refund_rejected' => 'color: #dc2626; background-color: #fef2f2; border-color: rgba(220,38,38,0.2);', // Red
        'refunded' => 'color: #16a34a; background-color: #f0fdf4; border-color: rgba(22,163,74,0.2);', // Green
        default => 'color: #4b5563; background-color: #f9fafb; border-color: rgba(107,114,128,0.1);',
    };

    $paymentStatusStyle = match (strtolower($record->payment_status)) {
        'paid' => 'color: #16a34a;',
        'pending' => 'color: #A68A64;',
        'failed' => 'color: #dc2626;',
        default => 'color: #6b7280;',
    };
    
    // Calculate subtotal and shipping
    $items = $record->items;
    $subtotal = $items->sum(function($item) {
        return $item->price * $item->quantity;
    });
    $shipping = $record->total_amount - $subtotal;
    // ensure shipping is not negative due to floating point or discounts
    $shipping = $shipping > 0 ? $shipping : 0;
    
    $viewUrl = \App\Filament\Resources\OrderResource::getUrl('view', ['record' => $record]);
@endphp

<style>
    .oc-card {
        display: flex; flex-direction: column; width: 100%; box-sizing: border-box; font-family: 'Manrope', sans-serif; height: 100%; padding: 12px;
        background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.08), 0 4px 10px rgba(0,0,0,0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .oc-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.12), 0 5px 15px rgba(0,0,0,0.06);
    }
    .oc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .oc-avatar-container { display: flex; align-items: center; gap: 12px; }
    .oc-avatar { width: 44px; height: 44px; border-radius: 50%; border: 1px solid #E5E0DA; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 16px; background-color: #800020; box-shadow: 0 1px 2px rgba(0,0,0,0.05); flex-shrink: 0; }
    .oc-title { font-weight: bold; color: #1b1c1a; font-size: 15px; line-height: 1.2; margin: 0; font-family: 'Noto Serif', serif; }
    .oc-subtitle { font-size: 12px; color: #706663; font-weight: 500; margin-top: 2px; margin-bottom: 0; letter-spacing: 0.02em; }
    .oc-status-container { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .oc-status-badge { display: inline-flex; align-items: center; border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid; }
    .oc-payment-status { font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; }
    .oc-date-section { border-top: 1px solid #E5E0DA; border-bottom: 1px solid #E5E0DA; padding: 6px 0; margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #706663; font-weight: 500; }
    .oc-date-left { display: flex; align-items: center; gap: 4px; }
    .oc-table-header { display: grid; grid-template-columns: 6fr 2fr 4fr; gap: 6px; padding-bottom: 6px; margin-bottom: 6px; border-bottom: 1px solid #E5E0DA; font-size: 9px; text-transform: uppercase; font-weight: bold; color: #A68A64; letter-spacing: 0.1em; }
    .oc-table-row { display: grid; grid-template-columns: 6fr 2fr 4fr; gap: 6px; font-size: 11px; align-items: center; margin-bottom: 6px; }
    .oc-col-1 { color: #1b1c1a; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .oc-col-2 { text-align: center; color: #706663; font-weight: bold; }
    .oc-col-3 { text-align: right; color: #1b1c1a; font-weight: 500; }
    .oc-items-list { height: 50px; overflow-y: auto; display: flex; flex-direction: column; margin-bottom: 12px; padding-right: 4px; }
    .oc-items-list::-webkit-scrollbar { width: 4px; }
    .oc-items-list::-webkit-scrollbar-track { background: transparent; }
    .oc-items-list::-webkit-scrollbar-thumb { background: rgba(128, 0, 32, 0.2); border-radius: 4px; }
    .dark .oc-items-list::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); }
    .oc-summary { display: flex; flex-direction: column; gap: 4px; border-top: 1px solid #E5E0DA; padding-top: 12px; padding-bottom: 12px; margin-bottom: 12px; }
    .oc-summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #706663; font-weight: 500; }
    .oc-total-row { display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 12px; border-bottom: 1px solid #E5E0DA; margin-bottom: 12px; }
    .oc-total-label { font-size: 12px; font-weight: bold; color: #1b1c1a; text-transform: uppercase; letter-spacing: 0.05em; }
    .oc-total-value { font-size: 16px; font-weight: bold; color: #800020; font-family: 'Noto Serif', serif; margin: 0; line-height: 1; }
    .oc-actions { display: flex; justify-content: flex-end; margin-top: auto; }
    .oc-btn { border: 1px solid #800020; color: #800020; background: transparent; border-radius: 4px; padding: 6px 16px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; white-space: nowrap; }
    .oc-btn:hover { background: #800020; color: white; }
    
    /* Dark Mode Styles */
    .dark .oc-card { background-color: transparent; border-color: transparent; box-shadow: none; padding: 4px; }
    .dark .oc-card:hover { box-shadow: none; transform: translateY(-2px); }
    .dark .oc-title { color: #f3f4f6; }
    .dark .oc-subtitle { color: #9ca3af; }
    .dark .oc-date-section { border-color: rgba(255,255,255,0.1); color: #9ca3af; }
    .dark .oc-table-header { border-color: rgba(255,255,255,0.1); color: #fbbf24; }
    .dark .oc-col-1, .dark .oc-col-3 { color: #e5e7eb; }
    .dark .oc-col-2 { color: #9ca3af; }
    .dark .oc-summary { border-color: rgba(255,255,255,0.1); }
    .dark .oc-summary-row { color: #9ca3af; }
    .dark .oc-total-row { border-color: rgba(255,255,255,0.1); }
    .dark .oc-total-label { color: #e5e7eb; }
    .dark .oc-total-value { color: #fca5a5; }
    .dark .oc-btn { border-color: #fca5a5; color: #fca5a5; }
    .dark .oc-btn:hover { background: #fca5a5; color: #111827; }
</style>

<div class="oc-card">
    
    {{-- Header --}}
    <div class="oc-header">
        <div class="oc-avatar-container">
            <div class="oc-avatar">
                {{ strtoupper($initials) }}
            </div>
            <div>
                <h3 class="oc-title">
                    {{ $customerName }}
                </h3>
                <p class="oc-subtitle">
                    {{ $record->order_number }}
                </p>
            </div>
        </div>
        <div class="oc-status-container">
            <span class="oc-status-badge" style="{{ $statusStyle }}">
                {{ ucwords(str_replace('_', ' ', $record->status)) }}
            </span>
            <span class="oc-payment-status" style="{{ $paymentStatusStyle }}">
                {{ strtolower($record->payment_status) }}
            </span>
        </div>
    </div>
    
    {{-- Date Section --}}
    <div class="oc-date-section">
        <div class="oc-date-left">
            <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px; color: #A68A64;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            {{ $record->created_at->format('M d, Y') }}
        </div>
        <span>{{ $record->created_at->format('h:i A') }}</span>
    </div>
    
    {{-- Mini Table Header --}}
    <div class="oc-table-header">
        <div>Items</div>
        <div style="text-align: center;">Qty</div>
        <div style="text-align: right;">Price</div>
    </div>
    
    {{-- Items List --}}
    <div class="oc-items-list">
        @foreach($items as $item)
        <div class="oc-table-row">
            <div class="oc-col-1" title="{{ $item->product->name ?? 'Unknown' }}">
                {{ $item->product->name ?? 'Unknown Product' }}
            </div>
            <div class="oc-col-2">
                {{ $item->quantity }}
            </div>
            <div class="oc-col-3">
                ₹{{ number_format($item->price, 0) }}
            </div>
        </div>
        @endforeach
    </div>
    
    {{-- Summary --}}
    <div class="oc-summary">
        <div class="oc-summary-row">
            <span>Subtotal</span>
            <span>₹{{ number_format($subtotal, 0) }}</span>
        </div>
        <div class="oc-summary-row">
            <span>Shipping</span>
            <span>₹{{ number_format($shipping, 0) }}</span>
        </div>
    </div>
    
    <div class="oc-total-row">
        <span class="oc-total-label">Total</span>
        <span class="oc-total-value">₹{{ number_format($record->total_amount, 0) }}</span>
    </div>
    
    {{-- Actions --}}
    <div class="oc-actions">
        <a href="{{ $viewUrl }}" class="oc-btn">
            See details
        </a>
    </div>
    
</div>