<div class="contact-form-card relative">
    
    @if($successMessage)
        <div class="absolute inset-0 bg-white/95 z-10 flex flex-col items-center justify-center text-center p-8 border border-green-200">
            <svg class="w-16 h-16 text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="text-xl font-serif text-gray-900 mb-2">Message Sent</h3>
            <p class="text-gray-600">{{ $successMessage }}</p>
            <button wire:click="$set('successMessage', '')" class="mt-6 text-[#800020] text-sm font-bold tracking-widest uppercase border-b border-[#800020]">Send Another</button>
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="input-group">
            <label>Full Name</label>
            <input type="text" wire:model="name" placeholder="Enter your full name">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" wire:model="email" placeholder="example@domain.com">
                @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" wire:model="phone" placeholder="+91 00000 00000">
                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="input-group">
            <label>Reason for Inquiry</label>
            <select wire:model="reason">
                <option value="" disabled selected>Select an option</option>
                <option value="Personal Consultation">Personal Consultation</option>
                <option value="Order Status & Tracking">Order Status & Tracking</option>
                <option value="Bulk / Wedding Inquiry">Bulk / Wedding Inquiry</option>
                <option value="Collaborations">Collaborations</option>
            </select>
            @error('reason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="input-group">
            <label>How can we help?</label>
            <textarea wire:model="message" placeholder="Write your message here..."></textarea>
            @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn-submit flex items-center justify-center gap-2">
            <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            
            <span wire:loading.remove wire:target="submit">SEND INQUIRY</span>
            <span wire:loading wire:target="submit">SENDING...</span>
            
            <svg wire:loading.remove wire:target="submit" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </button>
    </form>
</div>