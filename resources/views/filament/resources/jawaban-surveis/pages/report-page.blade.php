<x-filament::page>
    <x-slot name="heading">{{ $this->getHeading() }}</x-slot>
    @if ($this->getSubheading())
        <x-slot name="subheading">{{ $this->getSubheading() }}</x-slot>
    @endif

    <x-filament-widgets::widgets :widgets="$this->getHeaderWidgets()" />
</x-filament::page>
