<div x-data="{ 
        show: false, 
        msg: '', 
        type: 'success',
        initToast() {
            @if(session()->has('success'))
                this.msg = '{{ session('success') }}';
                this.type = 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            @elseif(session()->has('message'))
                this.msg = '{{ session('message') }}';
                this.type = 'success';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            @elseif(session()->has('error'))
                this.msg = '{{ session('error') }}';
                this.type = 'error';
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            @endif
        }
     }"
     x-init="initToast()"
     @toast.window="
        msg = $event.detail.msg || $event.detail[0]; 
        type = $event.detail.type || 'success'; 
        show = true; 
        setTimeout(() => show = false, 3000);
     "
     x-show="show"
     x-cloak
     style="display: none;"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-x-4"
     x-transition:enter-end="opacity-100 translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-4"
     class="fixed top-24 right-8 z-[9999] text-white px-6 py-3 rounded-md shadow-2xl flex items-center gap-3 font-sans text-sm tracking-wide"
     :class="type === 'error' ? 'bg-red-600' : 'bg-[#800020]'"
>
    <template x-if="type === 'error'">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
    </template>
    <template x-if="type !== 'error'">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
    </template>
    <span x-text="msg"></span>
</div>