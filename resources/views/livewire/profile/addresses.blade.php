<div class="bg-transparent font-sans">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-secondary m-0 font-serif">Addresses</h1>
        @if(!$showForm)
            <button wire:click="toggleForm" class="btn-primary rounded-sm py-2.5 px-6 text-xs border-none">
                Add a new address
            </button>
        @endif
    </div>

    <x-toast-notification />

    @if($showForm)
        <div class="bg-surface_lowest border border-outline_variant/50 rounded-sm p-8 mb-8 shadow-sm">
            <h2 class="text-lg font-bold text-secondary mb-6 font-serif">{{ $editingId ? 'Edit Address' : 'Add a new address' }}</h2>
            
            <form wire:submit.prevent="saveAddress" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <input type="text" wire:model="first_name" placeholder="First name*" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                        @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="last_name" placeholder="Last name*" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                        @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div>
                    <input type="text" wire:model="address_1" placeholder="Address 1*" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                    @error('address_1') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <input type="text" wire:model="address_2" placeholder="Address 2" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                </div>

                <div>
                    <input type="text" wire:model="city" placeholder="City*" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                    @error('city') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <input type="text" disabled value="India" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm bg-surface_low text-tertiary cursor-not-allowed">
                    </div>
                    <div>
                        <input type="text" wire:model="province" placeholder="Province / State" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <input type="text" wire:model="postal_code" placeholder="Postal/ZIP code*" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                        @error('postal_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <input type="text" wire:model="phone" placeholder="Phone" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors placeholder-tertiary/70">
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model="is_default" class="w-4 h-4 border-outline_variant/70 rounded-sm text-primary focus:ring-primary">
                        <span class="text-sm text-on_surface/80">Set as default address</span>
                    </label>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <button type="submit" class="btn-primary rounded-sm py-3 px-8 text-sm border-none">
                        {{ $editingId ? 'Update Address' : 'Add Address' }}
                    </button>
                    <button type="button" wire:click="toggleForm" class="bg-transparent text-tertiary px-6 py-3 font-bold text-sm tracking-widest uppercase hover:text-primary transition-colors cursor-pointer border-none">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    @endif

    @if(!$showForm)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($addresses as $address)
                <div class="bg-surface_lowest border {{ $address->is_default ? 'border-primary' : 'border-outline_variant/50' }} rounded-sm p-6 relative group hover:shadow-md transition-shadow">
                    @if($address->is_default)
                        <span class="absolute top-4 right-4 bg-primary text-white text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-sm">Default</span>
                    @endif
                    
                    <h3 class="font-bold text-secondary text-base mb-1 font-serif">{{ $address->first_name }} {{ $address->last_name }}</h3>
                    <div class="text-sm text-tertiary leading-relaxed mt-3">
                        <div class="flex items-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 text-secondary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <div>

                                <p>{{ $address->address_1 }}</p>
                                @if($address->address_2)<p>{{ $address->address_2 }}</p>@endif
                                <p>{{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}</p>
                                <p>{{ $address->country }}</p>
                            </div>
                        </div>
                        @if($address->phone)
                        <div class="flex items-center gap-2 mt-3 text-on_surface font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-secondary flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <p>{{ $address->phone }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button wire:click="editAddress({{ $address->id }})" class="btn-primary rounded-sm py-2 px-6 text-xs border-none">
                            Edit
                        </button>
                        <button type="button" wire:click="confirmDelete({{ $address->id }})" class="btn-heritage rounded-sm py-2 px-6 text-xs border-none cursor-pointer">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-surface_lowest border border-outline_variant/50 rounded-sm p-12 text-center">
                    <h3 class="text-lg font-bold text-secondary mb-2 font-serif">No addresses saved</h3>
                    <p class="text-tertiary m-0">You currently don't have any saved delivery addresses.</p>
                </div>
            @endforelse
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-[1001] flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div class="bg-surface_lowest rounded-sm shadow-xl p-8 max-w-sm w-full mx-4 border border-outline_variant/50">
            <h3 class="text-xl font-bold text-secondary mb-4 font-serif text-center">Delete Address</h3>
            <p class="text-tertiary mb-8 text-center text-sm font-sans">Are you sure you want to delete this address? This action cannot be undone.</p>
            <div class="flex items-center gap-4 justify-center">
                <button type="button" wire:click="cancelDelete" class="bg-transparent text-tertiary px-6 py-2.5 font-bold text-sm tracking-widest uppercase hover:text-primary transition-colors cursor-pointer border-none">
                    Cancel
                </button>
                <button type="button" wire:click="deleteAddress" class="btn-heritage rounded-sm py-2.5 px-6 text-xs border-none cursor-pointer">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
