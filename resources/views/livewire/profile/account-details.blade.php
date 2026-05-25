<div class="bg-surface_lowest border border-outline_variant/50 rounded-sm p-8 font-sans">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-secondary m-0 font-serif">Account Details</h1>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-sm">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="updateProfile" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-on_surface/80 mb-2">First Name</label>
                <input type="text" wire:model="first_name" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors">
                @error('first_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-on_surface/80 mb-2">Last Name</label>
                <input type="text" wire:model="last_name" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors">
                @error('last_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-on_surface/80 mb-2">Email</label>
            <input type="email" wire:model="email" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors">
            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-on_surface/80 mb-2">Change Password</label>
            <label class="flex items-center gap-2 cursor-pointer mt-1 mb-4">
                <input type="checkbox" wire:model.live="change_password" class="w-4 h-4 border-outline_variant/70 rounded-sm text-primary focus:ring-primary">
                <span class="text-sm text-tertiary">Update your password</span>
            </label>
            
            @if($change_password)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4 p-4 border border-outline_variant/50 rounded-sm bg-surface">
                    <div class="col-span-full">
                        <label class="block text-sm font-bold text-on_surface/80 mb-2">Current Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="current_password" class="w-full border border-outline_variant/70 rounded-sm h-[48px] pl-4 pr-12 text-sm focus:border-primary outline-none transition-colors">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none flex items-center bg-transparent border-none p-0 cursor-pointer" aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on_surface/80 mb-2">New Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="new_password" class="w-full border border-outline_variant/70 rounded-sm h-[48px] pl-4 pr-12 text-sm focus:border-primary outline-none transition-colors">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none flex items-center bg-transparent border-none p-0 cursor-pointer" aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        @error('new_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-on_surface/80 mb-2">Confirm New Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model="new_password_confirmation" class="w-full border border-outline_variant/70 rounded-sm h-[48px] pl-4 pr-12 text-sm focus:border-primary outline-none transition-colors">
                            <button type="button" @click="show = !show" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors focus:outline-none flex items-center bg-transparent border-none p-0 cursor-pointer" aria-label="Toggle password visibility">
                                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg x-show="show" x-cloak style="display: none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-outline_variant/50">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full bg-surface flex items-center justify-center text-primary">
                        <i data-lucide="cake" class="w-5 h-5"></i>
                    </div>
                    <label class="block text-sm font-bold text-on_surface/80 uppercase tracking-wide">Birthday</label>
                </div>
                <input type="date" wire:model="dob" class="w-full border border-outline_variant/70 rounded-sm h-[48px] px-4 text-sm focus:border-primary outline-none transition-colors text-on_surface">
                @error('dob') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="btn-primary rounded-sm py-3 text-sm px-8 border-none">
                Save Changes
            </button>
        </div>
    </form>
</div>
