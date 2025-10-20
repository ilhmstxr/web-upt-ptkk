<x-filament-panels::page>
    
    {{-- Baris ini akan merender widget dari metode getWidgets() --}}
    <x-filament-panels::widgets
        :widgets="$this->getWidgets()"
    />

</x-filament-panels::page>