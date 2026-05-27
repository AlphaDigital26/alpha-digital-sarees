<div class="max-w-4xl mx-auto px-5 pt-[80px] pb-12 min-h-screen font-sans">
    
    <x-checkout-progress step="2" />

    <div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-end gap-4">
        <div class="text-left">
            <h1 class="text-3xl font-bold text-[#2A211F] tracking-tight leading-none mb-2" style="font-family: 'Noto Serif', serif;">Delivery Details</h1>
            <p class="text-gray-500 text-xs uppercase tracking-[0.15em] font-bold" style="font-family: 'Manrope', sans-serif;">Where should we send your collection?</p>
        </div>
        
        @if(!$showForm)
            <button wire:click="toggleForm" class="text-[#800020] underline font-bold text-sm bg-transparent border-none cursor-pointer tracking-wider hover:text-[#5D4037] transition sm:mb-1 text-left sm:text-right">Add a new address</button>
        @endif
    </div>

    <div class="bg-white p-6 rounded shadow-sm border border-gray-200">
        @if($showForm)
            <h2 class="text-lg font-bold mb-6">Add a new address</h2>
            <form wire:submit.prevent="saveAddress" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <input type="text" wire:model="first_name" placeholder="First name*" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                        @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="last_name" placeholder="Last name*" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                        @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <input type="text" wire:model="company" placeholder="Company" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                </div>

                <div>
                    <input type="text" wire:model="address_1" placeholder="Address 1*" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                    @error('address_1') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <input type="text" wire:model="address_2" placeholder="Address 2" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                </div>

                <div>
                    <input type="text" wire:model="city" placeholder="City*" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                    @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <input type="text" disabled value="India" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm bg-gray-50 text-gray-500 cursor-not-allowed">
                    </div>
                    <div>
                        <input type="text" wire:model="province" placeholder="Province / State" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <input type="text" wire:model="postal_code" placeholder="Postal/ZIP code*" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                        @error('postal_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="phone" placeholder="Phone" class="w-full border border-gray-300 rounded h-[48px] px-4 text-sm focus:border-[#800020] outline-none transition-colors">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_default" class="w-4 h-4 text-[#800020] focus:ring-[#800020]">
                        <span class="text-sm text-gray-700">Set as default address</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-200 mt-6">
                    <button type="submit" class="bg-[#800020] hover:bg-[#5D4037] text-white px-8 py-3 rounded font-bold text-sm tracking-widest transition-colors cursor-pointer border-none">
                        ADD ADDRESS
                    </button>
                    <button type="button" wire:click="toggleForm" class="bg-transparent text-gray-500 px-6 py-3 font-bold text-sm tracking-widest uppercase hover:text-[#800020] transition-colors cursor-pointer border-none">
                        CANCEL
                    </button>
                </div>
            </form>
        @else
            <h2 class="text-lg font-bold mb-4">Delivery to {{ auth('customer')->user()->name }}</h2>
            
            @if(count($addresses) > 0)
                <div class="space-y-4">
                    @foreach($addresses as $address)
                        <label class="flex items-start p-4 border {{ $selectedAddressId == $address->id ? 'border-[#800020] bg-[#F4F0EB]' : 'border-gray-200' }} rounded cursor-pointer transition-colors">
                            <input type="radio" wire:model="selectedAddressId" value="{{ $address->id }}" class="mt-1 mr-3 accent-[#800020]">
                            <div>
                                <p class="font-bold text-[#2A211F]">{{ $address->first_name }} {{ $address->last_name }}</p>
                                <div class="text-sm text-gray-600 mt-1 leading-relaxed">
                                    @if($address->company)<p>{{ $address->company }}</p>@endif
                                    <p>{{ $address->address_1 }}</p>
                                    @if($address->address_2)<p>{{ $address->address_2 }}</p>@endif
                                    <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                    <p>{{ $address->country }}</p>
                                    @if($address->phone)<p class="mt-1 font-medium">Phone: {{ $address->phone }}</p>@endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('selectedAddressId') <span class="text-red-500 text-xs font-bold mt-2 block">{{ $message }}</span> @enderror
            @else
                <p class="text-gray-500 mb-4">You don't have any addresses saved yet.</p>
                <button wire:click="toggleForm" class="text-[#800020] underline font-bold text-sm bg-transparent border-none cursor-pointer">Add a new address</button>
            @endif

            <div class="mt-8 border-t border-gray-200 pt-6 flex justify-end">
                <button wire:click="continueToSummary" class="bg-[#800020] hover:bg-[#5D4037] text-white px-8 py-3 rounded font-bold text-sm tracking-widest transition-colors cursor-pointer border-none">
                    CONTINUE
                </button>
            </div>
        @endif
    </div>
</div>