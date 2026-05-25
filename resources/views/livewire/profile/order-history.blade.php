<div class="bg-transparent font-sans">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-secondary m-0 font-serif">Order History</h1>
    </div>

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
</div>
