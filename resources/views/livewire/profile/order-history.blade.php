<div class="bg-transparent font-sans">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-secondary m-0 font-serif">Order History</h1>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-white border border-[#E5E0DA] rounded-sm p-6 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 pb-4 border-b border-[#E5E0DA]">
                        <div>
                            <a href="{{ route('profile.orders.details', $order->id) }}" wire:navigate class="text-sm text-[#800020] hover:text-[#570013] font-bold uppercase tracking-wider mb-1 block transition-colors" style="font-family: 'Manrope', sans-serif;">Order {{ $order->order_number }}</a>
                            <p class="text-xs text-gray-400" style="font-family: 'Manrope', sans-serif;">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-left md:text-right">
                            <p class="text-lg font-bold text-[#800020] font-serif">Rs. {{ number_format($order->total_amount) }}</p>
                            <p class="text-xs font-bold uppercase tracking-wider mt-1 {{ $order->status === 'delivered' ? 'text-green-600' : 'text-[#A68A64]' }}" style="font-family: 'Manrope', sans-serif;">
                                Status: {{ ucfirst($order->status) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-2">
                        <div class="text-sm text-gray-600" style="font-family: 'Manrope', sans-serif;">
                            Payment Status: <span class="font-semibold text-gray-800 uppercase text-xs">{{ $order->payment_status ?? 'Paid' }}</span>
                        </div>
                        <a href="{{ route('profile.orders.track', $order->id) }}" wire:navigate class="text-xs font-bold uppercase tracking-wider text-[#800020] hover:text-[#570013] transition border-b border-[#800020] pb-0.5" style="font-family: 'Manrope', sans-serif;">
                            Track Order
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
