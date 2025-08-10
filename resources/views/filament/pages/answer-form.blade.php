<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->getForm() }}
        
        <div class="mt-6">
            <x-filament-actions::actions :actions="$this->getFormActions()" />
        </div>
    </form>
</x-filament-panels::page>
