<x-filament::page>
    <form method="GET" class="mb-4 flex items-center gap-2">
        <input
            type="number"
            name="pelatihanId"
            value="{{ request('pelatihanId') }}"
            class="fi-input w-48"
            placeholder="pelatihanId"
        />
        <button class="fi-btn fi-btn-primary">Terapkan</button>
    </form>

    <x-filament-widgets::widgets :widgets="$this->getHeaderWidgets()" />
</x-filament::page>
