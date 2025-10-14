<x-filament::page>

    {{-- Form filter akan dirender di sini --}}
    <form wire:submit.prevent>
        {{ $this->form }}
    </form>

    {{-- Sisipkan widget chart baru di sini (langkah selanjutnya) --}}
    @if ($this->pelatihanId && $this->bidangId)
        @livewire(\App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerBidangChart::class, [
            'pelatihanId' => $this->pelatihanId,
            'bidangId' => $this->bidangId,
        ])
    @endif
    
    @if (request()->boolean('print'))
        <style>
            .fi-topbar,
            .fi-sidebar,
            header,
            nav,
            aside {
                display: none !important;
            }

            body {
                background: #fff !important;
            }

            .fi-main,
            main {
                margin: 0 !important;
                padding: 0 12px !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            .avoid-break {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            canvas {
                max-width: 100% !important;
                height: auto !important;
            }
        </style>
    @endif

    <div class="flex items-center justify-between gap-4 mb-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-bold">{{ $title ?? 'Report Jawaban Survei' }}</h1>
            @isset($subtitle)
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $subtitle }}</p>
            @endisset
        </div>

        {{-- <x-filament::button
            tag="a"
            target="_blank"
            :href="route('reports.jawaban-akumulatif.pdf', ['pelatihanId' => $pelatihanId])"
            icon="heroicon-o-document-arrow-down"
        >
            Export PDF
        </x-filament::button> --}}
    </div>

    {{-- <h1>woi jembut</h1> --}}
    <div class="grid grid-cols-1 gap-6">
        <div class="avoid-break">
            <x-filament-widgets::widgets :widgets="[\App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart::class]" :columns="['default' => 1]" :data="['pelatihanId' => $pelatihanId]" />
        </div>

        <div class="avoid-break">
            <x-filament-widgets::widgets :widgets="[\App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart::class]" :columns="['default' => 1]" :data="['pelatihanId' => $pelatihanId]" />
        </div>

        <div class="avoid-break">
            <x-filament-widgets::widgets :widgets="[\App\Filament\Resources\JawabanSurveiResource\Widgets\PiePerPertanyaanWidget::class]" :columns="['default' => 1]" :data="['pelatihanId' => $pelatihanId]" />
        </div>
    </div>

    {{-- <script>
        window.__chartsReady = false;
        window.addEventListener('load', () => setTimeout(() => window.__chartsReady = true, 3000));
    </script> --}}
    <script>
        // jumlah komponen chart pada halaman
        window.__chartsExpected = 3; // Akumulatif, PerKategori, PiePerPertanyaan
        window.__chartsDone = 0;

        function markChartReady() {
            if (++window.__chartsDone >= window.__chartsExpected) {
                window.__reportReady = true;
            }
        }

        // Chart.js: panggil event ketika render selesai
        // contoh opsi Chart.js saat inisialisasi setiap chart:
        // options: { animation: { duration: 0, onComplete() { window.dispatchEvent(new Event('chartjs:rendered')); } } }

        // Jika pakai Chart.js:
        window.addEventListener('chartjs:rendered', markChartReady);

        // Jika pakai ApexCharts (beberapa paket Filament):
        window.addEventListener('apexchart:rendered', markChartReady);
    </script>

</x-filament::page>
