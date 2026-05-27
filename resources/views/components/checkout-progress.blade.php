@props(['step' => 1])

<div class="pt-4 mb-4 border-b border-[#E5E0DA] pb-2 flex justify-center items-center gap-4 sm:gap-8 font-sans">
    {{-- Step 1: Shopping Bag --}}
    <div class="flex flex-col items-center gap-2 {{ $step >= 1 ? 'opacity-100' : 'opacity-40' }}">
        <div class="w-8 h-8 rounded-full border-2 {{ $step >= 1 ? 'border-[#800020] text-[#800020] font-bold' : 'border-gray-300 text-gray-400' }} flex items-center justify-center text-sm transition-colors">
            @if($step > 1)
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            @else
                1
            @endif
        </div>
        <span class="text-[10px] sm:text-xs tracking-[0.2em] uppercase font-bold {{ $step >= 1 ? 'text-[#1b1c1a]' : 'text-gray-400' }}">Shopping Bag</span>
    </div>

    {{-- Separator --}}
    <div class="h-[2px] w-8 sm:w-16 {{ $step >= 2 ? 'bg-[#800020]' : 'bg-gray-200' }} transition-colors"></div>

    {{-- Step 2: Delivery --}}
    <div class="flex flex-col items-center gap-2 {{ $step >= 2 ? 'opacity-100' : 'opacity-40' }}">
        <div class="w-8 h-8 rounded-full border-2 {{ $step >= 2 ? 'border-[#800020] text-[#800020] font-bold' : 'border-gray-300 text-gray-400' }} flex items-center justify-center text-sm transition-colors">
            @if($step > 2)
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            @else
                2
            @endif
        </div>
        <span class="text-[10px] sm:text-xs tracking-[0.2em] uppercase font-bold {{ $step >= 2 ? 'text-[#1b1c1a]' : 'text-gray-400' }}">Delivery</span>
    </div>

    {{-- Separator --}}
    <div class="h-[2px] w-8 sm:w-16 {{ $step >= 3 ? 'bg-[#800020]' : 'bg-gray-200' }} transition-colors"></div>

    {{-- Step 3: Payment --}}
    <div class="flex flex-col items-center gap-2 {{ $step >= 3 ? 'opacity-100' : 'opacity-40' }}">
        <div class="w-8 h-8 rounded-full border-2 {{ $step >= 3 ? 'border-[#800020] text-[#800020] font-bold' : 'border-gray-300 text-gray-400' }} flex items-center justify-center text-sm transition-colors">
            3
        </div>
        <span class="text-[10px] sm:text-xs tracking-[0.2em] uppercase font-bold {{ $step >= 3 ? 'text-[#1b1c1a]' : 'text-gray-400' }}">Payment</span>
    </div>
</div>
