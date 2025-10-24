{{-- Lokasi: resources/views/filament/pages/detail-pelatihan.blade.php --}}

<x-filament-panels::page>

    {{-- 
        Judul halaman (Nama Pelatihan) sudah diatur oleh $this->title 
        di file DetailPelatihan.php 
    --}}

    {{-- Render TABS --}}
    <x-filament::tabs>
        @foreach ($this->getTabs() as $tabId => $tab)
            <x-filament::tabs.item
                :active="$activeTab === $tabId"
                wire:click="$set('activeTab', '{{ $tabId }}')"
                :icon="$tab->getIcon()"
            >
                {{ $tab->getLabel() }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>

    {{-- Render WIDGETS untuk tab yang aktif --}}
    {{ $this->getActiveTabWidgets() }}

</x-filament-panels::page>
