<x-filament-panels::page>
    <form wire:submit="save">
        
        {{ $this->form }}

        <div class="mt-6 flex gap-3">
            @if(! $this->isEditing)
                <x-filament::button type="button" wire:click="enableEditing" size="lg" icon="heroicon-o-pencil">
                    Edit Settings
                </x-filament::button>
            @else
                <x-filament::button type="submit" size="lg" color="success" icon="heroicon-o-check">
                    Save Settings
                </x-filament::button>

                <x-filament::button type="button" wire:click="cancelEditing" size="lg" color="gray">
                    Cancel
                </x-filament::button>
            @endif
        </div>
        
    </form>
</x-filament-panels::page>