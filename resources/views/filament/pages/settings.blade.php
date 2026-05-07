<x-filament-panels::page>
    <div class="fixed inset-0 z-0 bg-[#fcfcfc]"></div>

    <div class="relative z-10 -mt-10">
        @if($record)
            {{-- Top Header Section --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-12">
                <div>
                    <h1 class="text-[42px] font-bold text-[#1a1a1a] tracking-tight leading-none">Settings Management</h1>
                    <p class="text-gray-400 text-lg mt-3 font-medium">Manage your store resources and data seamlessly.</p>
                </div>
                <div class="mt-6 md:mt-0">
                    <button wire:click="save" 
                        class="flex items-center gap-3 px-10 py-4 bg-[#7c061a] text-white rounded-xl font-bold shadow-xl hover:bg-[#5a0413] transition-all active:scale-95">
                        <x-filament::icon icon="heroicon-m-document-check" class="w-6 h-6" />
                        <span class="text-lg">Save Settings</span>
                    </button>
                </div>
            </div>

            {{-- Main Form Content --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-24 gap-y-16">
                
                {{-- Column 1: Store Identity --}}
                <div class="space-y-12">
                    <h2 class="text-2xl font-bold text-[#7c061a] uppercase tracking-[0.25em] border-b-2 border-gray-100 pb-4">Store Identity</h2>
                    
                    <div class="space-y-10">
                        <div class="group">
                            <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Site Name</label>
                            <input type="text" wire:model="data.site_title" placeholder="ALMAARI" 
                                class="w-full bg-[#f4f4f4] border-none rounded-2xl p-6 text-[#7c061a] font-extrabold text-xl ring-0 focus:ring-2 focus:ring-[#7c061a]/20 transition-all">
                        </div>

                        <div class="group">
                            <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Contact Email</label>
                            <input type="email" wire:model="data.email" 
                                class="w-full bg-[#f4f4f4] border-none rounded-2xl p-6 text-gray-800 font-medium text-lg ring-0 focus:ring-2 focus:ring-[#7c061a]/20 transition-all">
                        </div>

                        <div class="group">
                            <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Contact Phone</label>
                            <input type="text" wire:model="data.phone_1" 
                                class="w-full bg-[#f4f4f4] border-none rounded-2xl p-6 text-gray-800 font-medium text-lg ring-0 focus:ring-2 focus:ring-[#7c061a]/20 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Column 2: Social Links --}}
                <div class="space-y-12">
                    <h2 class="text-2xl font-bold text-[#7c061a] uppercase tracking-[0.25em] border-b-2 border-gray-100 pb-4">Social Links</h2>
                    
                    <div class="space-y-10">
                        <div class="group">
                            <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Instagram URL</label>
                            <input type="text" wire:model="data.instagram" 
                                class="w-full bg-[#f4f4f4] border-none rounded-2xl p-6 text-gray-800 font-medium text-lg ring-0 focus:ring-2 focus:ring-[#7c061a]/20 transition-all">
                        </div>

                        <div class="group">
                            <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Facebook URL</label>
                            <input type="text" wire:model="data.facebook" 
                                class="w-full bg-[#f4f4f4] border-none rounded-2xl p-6 text-gray-800 font-medium text-lg ring-0 focus:ring-2 focus:ring-[#7c061a]/20 transition-all">
                        </div>

                        <div class="group">
                            <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Whatsapp Number</label>
                            <input type="text" wire:model="data.whatsapp" 
                                class="w-full bg-[#f4f4f4] border-none rounded-2xl p-6 text-gray-800 font-medium text-lg ring-0 focus:ring-2 focus:ring-[#7c061a]/20 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Full Width Bottom: Store Address --}}
                <div class="md:col-span-2 mt-8">
                    <label class="text-[13px] font-black text-gray-500 uppercase tracking-[0.15em] block mb-4">Store Address</label>
                    <textarea wire:model="data.address" rows="5" 
                        class="w-full bg-[#f4f4f4] border-none rounded-[32px] p-10 text-gray-800 font-medium text-lg ring-0 focus:ring-2 focus:ring-[#7c061a]/20 resize-none transition-all"></textarea>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>