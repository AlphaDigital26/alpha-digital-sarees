<x-layouts.app>

<div class="grid grid-cols-4 gap-16">

    <!-- FILTER SIDEBAR -->
    <div class="col-span-1">

        <h2 class="font-serif text-2xl mb-6">Filter</h2>

        <!-- Search -->
        <input 
            type="text" 
            placeholder="Search..."
            wire:model.live="search"
            class="w-full bg-transparent border-b border-outline_variant focus:border-primary outline-none py-2 mb-6"
        >

        <!-- Fabric Filter -->
        <select wire:model.live="fabric" class="w-full bg-transparent border-b border-outline_variant py-2">
            <option value="">All Fabrics</option>
            <option value="Banarasi">Banarasi</option>
            <option value="Kanchipuram">Kanchipuram</option>
        </select>

        <!-- Sorting -->
        <select wire:model.live="sort" class="w-full mt-6 bg-transparent border-b border-outline_variant py-2">
            <option value="latest">Latest</option>
            <option value="price_low">Price Low to High</option>
            <option value="price_high">Price High to Low</option>
        </select>

    </div>

    <!-- PRODUCT GRID -->
    <div class="col-span-3">

        <div class="grid grid-cols-3 gap-12">

            @foreach($products as $product)
                <div class="bg-surface_lowest p-4">

                    <div class="h-64 bg-gray-200"></div>

                    <h3 class="mt-4 text-sm font-semibold">
                        {{ $product->name }}
                    </h3>

                    <p class="text-xs opacity-60">
                        ₹{{ $product->price }}
                    </p>

                </div>
            @endforeach

        </div>

    </div>

</div>

</x-layouts.app>