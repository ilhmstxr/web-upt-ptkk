<x-filament::page>
    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :data="$this->getHeaderWidgetsData()"
        class="mb-6"
    />

    {{ $this->table }}
</x-filament::page>
