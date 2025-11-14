<x-filament::page>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-filament::stats-card label="Total Pelatihan" value="{{ $data['total_pelatihan'] }}" icon="heroicon-o-academic-cap" />
        <x-filament::stats-card label="Total Peserta" value="{{ $data['total_peserta'] }}" icon="heroicon-o-user-group" />
        <x-filament::stats-card label="Total Tes" value="{{ $data['total_tes'] }}" icon="heroicon-o-document-check" />
        <x-filament::stats-card label="Rata-rata Skor" value="{{ number_format($data['avg_skor'], 2) }}" icon="heroicon-o-chart-bar" />
    </div>

    <div class="mt-6">
        <livewire:tes-percobaan-charts />
    </div>
</x-filament::page>
