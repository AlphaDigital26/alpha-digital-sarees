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
    
    $statusArray = ['new', 'processing', 'shipped', 'delivered'];
    $currentStatusIndex = array_search(strtolower($record->status), $statusArray);
    if ($currentStatusIndex === false) {
        if (strtolower($record->status) == 'refunded') {
            $currentStatusIndex = -1; // Special case
        } else {
            $currentStatusIndex = 0; // Default to new
        }
    }
@endphp

<style>
    .vo-wrapper {
        font-family: 'Manrope', sans-serif;
        color: #2D3748;
    }
    .vo-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 24px;
        border-radius: 8px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .vo-order-title { font-size: 20px; font-weight: bold; margin: 0; color: #1A202C; }
    .vo-order-title span { color: #3182CE; }
    .vo-order-date { font-size: 13px; color: #718096; margin-top: 4px; }
    .vo-header-actions { display: flex; gap: 12px; }
    .vo-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: bold; cursor: pointer;
        border: none;
    }
    .vo-btn-primary { background: #3182CE; color: white; }
    .vo-btn-primary:hover { background: #2B6CB0; }
    
    .vo-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    
    .vo-card {
        background: white;
        border-radius: 8px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }
    .vo-card-title {
        font-size: 16px; font-weight: bold; color: #1A202C; margin-bottom: 20px;
        border-bottom: 1px solid #E2E8F0; padding-bottom: 12px;
    }
    
    /* Items Table */
    .vo-item-row {
        display: grid;
        grid-template-columns: 60px 1fr 100px 150px 100px;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #E2E8F0;
    }
    .vo-item-img {
        width: 60px; height: 60px; background: #F7FAFC; border-radius: 8px; object-fit: cover;
    }
    .vo-item-details h4 { margin: 0; font-size: 14px; color: #2D3748; font-weight: 600; }
    .vo-item-details p { margin: 4px 0 0; font-size: 12px; color: #A0AEC0; }
    .vo-item-weight { font-size: 13px; color: #718096; }
    .vo-item-price { font-size: 13px; color: #4A5568; }
    .vo-item-total { font-size: 14px; font-weight: bold; color: #2D3748; text-align: right; }
    
    /* Summary */
    .vo-summary-container {
        display: flex;
        justify-content: space-between;
        margin-top: 24px;
    }
    .vo-order-note {
        flex: 1; margin-right: 40px; font-size: 13px; color: #718096;
    }
    .vo-order-note strong { color: #2D3748; display: block; margin-bottom: 8px; }
    .vo-summary-box { width: 300px; }
    .vo-summary-row {
        display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; color: #4A5568;
    }
    .vo-summary-total {
        display: flex; justify-content: space-between; margin-top: 16px; padding-top: 16px; border-top: 1px solid #E2E8F0; font-size: 16px; font-weight: bold; color: #1A202C;
    }
    
    /* Customer Details */
    .vo-customer-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    .vo-customer-info { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #4A5568; margin-bottom: 16px; }
    .vo-customer-info svg { width: 18px; height: 18px; color: #A0AEC0; }
    .vo-address { font-size: 14px; color: #4A5568; line-height: 1.6; }
    
    /* Timeline */
    .vo-timeline {
        position: relative;
        padding-left: 24px;
    }
    .vo-timeline::before {
        content: '';
        position: absolute;
        left: 24px;
        top: 24px;
        bottom: 24px;
        width: 1px;
        background: #E2E8F0;
        z-index: 0;
    }
    .vo-timeline-step {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 32px;
        position: relative;
        z-index: 1;
    }
    .vo-timeline-icon {
        width: 48px; height: 48px; border-radius: 50%; background: white; border: 2px solid #E2E8F0; display: flex; align-items: center; justify-content: center; color: #A0AEC0;
        margin-left: -24px; /* Align over the line */
    }
    .vo-timeline-icon.active {
        border-color: #3182CE; color: #3182CE; background: #EBF8FF;
    }
    .vo-timeline-content { flex: 1; padding-top: 4px; }
    .vo-timeline-title { font-size: 14px; font-weight: bold; color: #2D3748; display: flex; align-items: center; gap: 8px; }
    .vo-timeline-date { font-size: 12px; color: #A0AEC0; margin-top: 4px; }
    .vo-check-icon { color: #38A169; width: 16px; height: 16px; }
    

    @media (max-width: 1024px) {
        .vo-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="vo-wrapper">
    
    {{-- Header --}}
    <div class="vo-header">
        <div>
            <h2 class="vo-order-title">Order <span>#{{ $record->order_number }}</span></h2>
            <div class="vo-order-date">{{ $record->created_at->format('M d, Y \a\t h:i A') }}</div>
        </div>
        <div class="vo-header-actions">
            @php $editUrl = \App\Filament\Resources\OrderResource::getUrl('edit', ['record' => $record]); @endphp
            @if(strtolower($record->status) !== 'canceled' && strtolower($record->status) !== 'refunded')
                <a href="{{ $editUrl }}" class="vo-btn vo-btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    Edit
                </a>
            @endif
            @if(strtolower($record->status) === 'canceled')
                <button wire:click="refundOrder" wire:confirm="Are you sure you want to process the refund for this canceled order? This will mark the order and payment as refunded." class="vo-btn" style="background: #E53E3E; color: white;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                    Process Refund
                </button>
            @endif
            <button class="vo-btn vo-btn-primary" onclick="window.print()">
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
                <h3 class="vo-card-title">Ordered Items <span style="font-size: 12px; color: #A0AEC0; font-weight: normal;">(All items were processed)</span></h3>
                
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
                    <div class="vo-order-note">
                        <strong>Order Note</strong>
                        Please ensure the items are packaged securely. Standard shipping applies.
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
                        <div class="vo-timeline-icon {{ $currentStatusIndex >= 0 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Order Placed 
                                @if($currentStatusIndex >= 0) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                            <div class="vo-timeline-date">{{ $record->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                    
                    {{-- Step 2: Processing --}}
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $currentStatusIndex >= 1 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Processing 
                                @if($currentStatusIndex >= 1) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                            @if($currentStatusIndex >= 1) <div class="vo-timeline-date">{{ $record->updated_at->format('d M Y') }}</div> @endif
                        </div>
                    </div>
                    
                    {{-- Step 3: Shipped --}}
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $currentStatusIndex >= 2 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Shipped 
                                @if($currentStatusIndex >= 2) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                            @if($currentStatusIndex >= 2) <div class="vo-timeline-date">{{ $record->updated_at->format('d M Y') }}</div> @endif
                        </div>
                    </div>
                    
                    {{-- Step 4: Delivered --}}
                    <div class="vo-timeline-step">
                        <div class="vo-timeline-icon {{ $currentStatusIndex >= 3 ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <div class="vo-timeline-content">
                            <div class="vo-timeline-title">
                                Delivered 
                                @if($currentStatusIndex >= 3) <svg class="vo-check-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            
        </div>
        
    </div>

</div>
</x-filament-panels::page>
