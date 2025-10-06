<x-filament::page>
    @if(request()->boolean('print'))
        <style>
            .fi-topbar, .fi-sidebar, header, nav, aside { display:none!important; }
            body { background:#fff!important; }
            .fi-main, main { margin:0!important; padding:0 12px!important; }
            @page { size: A4; margin: 10mm; }
            .avoid-break { break-inside: avoid; page-break-inside: avoid; }
            canvas { max-width: 100%!important; height: auto!important; }
        </style>
    @endif

    <div class="flex items-center justify-between gap-4 mb-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold">{{ $title ?? 'Report Jawaban Survei' }}</h1>
            @isset($subtitle)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
            @endisset
        </div>

        <x-filament::button
            tag="a"
            target="_blank"
            :href="route('reports.jawaban-akumulatif.pdf', ['pelatihanId' => $pelatihanId])"
            icon="heroicon-o-document-arrow-down"
        >
            Export PDF
        </x-filament::button>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="avoid-break">
            <x-filament-widgets::widgets
                :widgets="[\App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart::class]"
                :columns="['default' => 1]"
                :data="['pelatihanId' => $pelatihanId]"
            />
        </div>

        <div class="avoid-break">
            <x-filament-widgets::widgets
                :widgets="[\App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart::class]"
                :columns="['default' => 1]"
                :data="['pelatihanId' => $pelatihanId]"
            />
        </div>

        <div class="avoid-break">
            <x-filament-widgets::widgets
                :widgets="[\App\Filament\Resources\JawabanSurveiResource\Widgets\PiePerPertanyaanWidget::class]"
                :columns="['default' => 1]"
                :data="['pelatihanId' => $pelatihanId]"
            />
        </div>
    </div>

    <script>
        window.__chartsReady = false;
        window.addEventListener('load', () => setTimeout(() => window.__chartsReady = true, 1200));
    </script>
</x-filament::page>
