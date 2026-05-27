@php
    $record = $getRecord();
    $customerName = $record->customer->name ?? 'Guest';
    $initials = collect(explode(' ', $customerName))->map(fn($part) => substr($part, 0, 1))->take(2)->join('');
    
    // Status colors mapping for inline styles
    $statusStyle = match (strtolower($record->status)) {
        'new' => 'color: #800020; background-color: rgba(128,0,32,0.1); border-color: rgba(128,0,32,0.2);',
        'processing' => 'color: #A68A64; background-color: rgba(166,138,100,0.1); border-color: rgba(166,138,100,0.2);',
        'shipped' => 'color: #4338ca; background-color: #eef2ff; border-color: rgba(79,70,229,0.2);',
        'delivered' => 'color: #15803d; background-color: #f0fdf4; border-color: rgba(22,163,74,0.2);',
        'refunded' => 'color: #b91c1c; background-color: #fef2f2; border-color: rgba(220,38,38,0.2);',
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
        display: flex; flex-direction: column; width: 100%; box-sizing: border-box; font-family: 'Manrope', sans-serif; height: 100%; padding: 4px;
    }
    .oc-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .oc-avatar-container { display: flex; align-items: center; gap: 16px; }
    .oc-avatar { width: 56px; height: 56px; border-radius: 50%; border: 1px solid #E5E0DA; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; font-size: 20px; background-color: #800020; box-shadow: 0 1px 2px rgba(0,0,0,0.05); flex-shrink: 0; }
    .oc-title { font-weight: bold; color: #1b1c1a; font-size: 18px; line-height: 1.2; margin: 0; font-family: 'Noto Serif', serif; }
    .oc-subtitle { font-size: 14px; color: #706663; font-weight: 500; margin-top: 4px; margin-bottom: 0; letter-spacing: 0.02em; }
    .oc-status-container { display: flex; flex-direction: column; align-items: flex-end; gap: 6px; }
    .oc-status-badge { display: inline-flex; align-items: center; border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid; }
    .oc-payment-status { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; }
    .oc-date-section { border-top: 1px solid #E5E0DA; border-bottom: 1px solid #E5E0DA; padding: 10px 0; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #706663; font-weight: 500; }
    .oc-date-left { display: flex; align-items: center; gap: 6px; }
    .oc-table-header { display: grid; grid-template-columns: 6fr 2fr 4fr; gap: 8px; padding-bottom: 8px; margin-bottom: 8px; border-bottom: 1px solid #E5E0DA; font-size: 10px; text-transform: uppercase; font-weight: bold; color: #A68A64; letter-spacing: 0.1em; }
    .oc-table-row { display: grid; grid-template-columns: 6fr 2fr 4fr; gap: 8px; font-size: 12px; align-items: center; margin-bottom: 8px; }
    .oc-col-1 { color: #1b1c1a; font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .oc-col-2 { text-align: center; color: #706663; font-weight: bold; }
    .oc-col-3 { text-align: right; color: #1b1c1a; font-weight: 500; }
    .oc-items-list { flex-grow: 1; display: flex; flex-direction: column; margin-bottom: 20px; }
    .oc-summary { display: flex; flex-direction: column; gap: 8px; border-top: 1px solid #E5E0DA; padding-top: 16px; padding-bottom: 16px; margin-bottom: 16px; }
    .oc-summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #706663; font-weight: 500; }
    .oc-total-row { display: flex; justify-content: space-between; align-items: flex-end; padding-bottom: 16px; border-bottom: 1px solid #E5E0DA; margin-bottom: 16px; }
    .oc-total-label { font-size: 14px; font-weight: bold; color: #1b1c1a; text-transform: uppercase; letter-spacing: 0.05em; }
    .oc-total-value { font-size: 20px; font-weight: bold; color: #800020; font-family: 'Noto Serif', serif; margin: 0; line-height: 1; }
    .oc-actions { display: flex; justify-content: flex-end; margin-top: auto; }
    .oc-btn { border: 1px solid #800020; color: #800020; background: transparent; border-radius: 4px; padding: 8px 24px; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.1em; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; white-space: nowrap; }
    .oc-btn:hover { background: #800020; color: white; }
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
                {{ ucfirst($record->status) }}
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
