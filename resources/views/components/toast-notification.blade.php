@php
    $type = 'success';
    $msg = '';
    
    if (session()->has('success')) {
        $msg = session('success');
    } elseif (session()->has('message')) {
        $msg = session('message');
    } elseif (session()->has('error')) {
        $type = 'error';
        $msg = session('error');
    }
@endphp

@if ($msg)
    <div wire:key="toast-{{ uniqid() }}" x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 2000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-4"
         class="fixed top-24 right-8 z-[9999] text-white px-6 py-3 rounded-md shadow-2xl flex items-center gap-3 font-sans text-sm tracking-wide {{ $type === 'error' ? 'bg-red-600' : 'bg-[#800020]' }}"
    >
        @if($type === 'error')
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        @endif
        {{ $msg }}
    </div>
@endif
