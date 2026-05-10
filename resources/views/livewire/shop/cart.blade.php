<main class="cart-main">
    <div class="container">
        <div class="cart-header">
            <h1>Review your selected products before proceeding to checkout</h1>
        </div>

        <div class="cart-layout">
            <div class="cart-items">
                @if(count($cartItems) > 0)
                    @foreach($cartItems as $id => $item)
                        <div class="cart-item" wire:key="item-{{ $id }}">
                            <div class="item-img">
                                <img src="{{ $item['image'] }}" alt="Product">
                            </div>
                            <div class="item-details">
                                <h3>Product Title</h3>
                                <p class="details-text">{{ $item['name'] }}</p>
                                <div class="item-controls">
                                    <div class="qty-selector">
                                        <button wire:click="decrementQty({{ $id }})">-</button>
                                        <span>{{ $item['qty'] }}</span>
                                        <button wire:click="incrementQty({{ $id }})">+</button>
                                    </div>
                                    <span class="price">Rs. {{ number_format($item['price'] * $item['qty']) }}</span>
                                </div>
                            </div>
                            <button class="remove-btn" wire:click="removeItem({{ $id }})">
                                <i data-lucide="x"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="py-10 text-center">
                        <p class="text-gray-500 mb-4">Your cart is empty.</p>
                        <a href="/shop" style="color: var(--primary); text-decoration: underline;">Continue Shopping</a>
                    </div>
                @endif
            </div>

            <aside class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>Rs. {{ number_format($this->subtotal) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping <i data-lucide="info" class="info-icon"></i></span>
                    <span>{{ $this->shipping == 0 ? 'Free' : 'Rs. ' . number_format($this->shipping) }}</span>
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span>Rs. {{ number_format($this->total) }}</span>
                </div>
                <button wire:click="checkout" class="btn-checkout">Checkout</button>
                
                @if (session()->has('message'))
                    <div style="color: green; margin-bottom: 1rem; font-size: 0.9rem; text-align: center;">
                        {{ session('message') }}
                    </div>
                @endif
                
                <div class="trust-badge">
                    <p>Heritage Craft Secure Global Payment</p>
                </div>
            </aside>
        </div>
    </div>
</main>