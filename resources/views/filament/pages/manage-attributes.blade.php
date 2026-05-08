<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        @livewire(\App\Livewire\FabricTable::class)
        @livewire(\App\Livewire\ColorTable::class)
        @livewire(\App\Livewire\PatternTable::class)
    </div>
</x-filament-panels::page>