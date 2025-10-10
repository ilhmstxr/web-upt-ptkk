{{-- resources/views/filament/resources/jawaban-surveis/pages/report-pdf-view.blade.php --}}
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Report Jawaban Survei' }}</title>
    <style>
        /* ... CSS Anda yang lain ... */
        @page {
            size: A4 portrait;
            margin: 16mm;
        }

        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        html,
        body {
            background: #fff;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 14px;
            opacity: .9;
        }

        .meta {
            font-size: 12px;
            opacity: .8;
            margin-bottom: 24px;
            text-align: right;
        }

        .section {
            margin-top: 20px;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
        }

        .col-12 {
            grid-column: span 12;
        }

        .col-6 {
            grid-column: span 6;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .table th,
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }

        .page-break {
            page-break-before: always;
        }

        .mb-2 {
            margin-bottom: 8px
        }

        .mb-4 {
            margin-bottom: 16px
        }

        .chart-container {
            position: relative;
            height: 350px;
            width: 100%;
        }

        /* 🎯 1. CSS BARU DITAMBAHKAN DI SINI */
        .category-legend {
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
        }

        .category-legend h4 {
            margin-bottom: 8px;
            font-weight: 600;
        }

        .category-legend ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }

        .category-legend li {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- ... Bagian header dan ringkasan tetap sama ... --}}
        <header class="header">
            <div>
                <div class="title">{{ $title ?? 'Report Jawaban Survei' }}</div>
                @isset($subtitle)
                    <div class="subtitle">{{ $subtitle }}</div>
                @endisset
            </div>
            <div class="meta">
                <div>Dicetak: {{ now()->format('d M Y H:i') }}</div>
                @isset($pelatihanId)
                    <div>ID Pelatihan: {{ $pelatihanId }}</div>
                @endisset
            </div>
        </header>

        @isset($ringkasan)
            <section class="section card">
                <h3 class="mb-2">Ringkasan</h3>
                <table class="table">
                    <tbody>
                        @foreach ($ringkasan as $label => $nilai)
                            <tr>
                                <th style="width:40%">{{ $label }}</th>
                                <td>{{ $nilai }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endisset

        @if (!empty($charts))
            <section class="section">
                <div class="grid">
                    @foreach ($charts as $i => $chart)
                        <div class="col-12 card">
                            <h4 class="mb-2">{{ $chart['title'] ?? 'Chart ' . ($i + 1) }}</h4>
                            <div class="chart-container">
                                <canvas id="chart_{{ Str::slug($chart['title']) }}"></canvas>
                            </div>

                            {{-- 🎯 2. BLOK HTML BARU DITAMBAHKAN DI SINI --}}
                            {{-- Tampilkan legenda hanya untuk chart kategori dan jika array kustom ada --}}
                            @if (isset($arrayCustom) && !empty($arrayCustom) && Str::contains($chart['title'], 'Kategori'))
                                <div class="category-legend">
                                    <h4>Keterangan Kategori:</h4>
                                    <ul>
                                        @foreach ($arrayCustom as $index => $label)
                                            <li><strong>Kategori {{ $index + 1 }}:</strong> {{ $label }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ... Bagian pie chart tetap sama ... --}}
        @if (!empty($pieCharts))
            <div class="page-break"></div>
            <section class="section">
                <h3 class="mb-2">Detail Jawaban per Pertanyaan</h3>
                <div class="grid">
                    @foreach ($pieCharts as $i => $pie)
                        <div class="col-6 card">
                            <h4 class="mb-2" style="font-size: 12px;">{{ $pie['question_label'] }}</h4>
                            <div class="chart-container">
                                <canvas id="pie_chart_{{ $i }}"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function getPdfChartOptions(originalOptions) {
            const options = originalOptions || {};
            options.animation = options.animation || {};
            options.animation.duration = 0;
            return options;
        }

        (function() {
            if (!window.Chart) return;

            @if (!empty($charts))
                try {
                    const mainChartConfigs = @json($charts);
                    const akumulatifConfig = mainChartConfigs.find(c => c.title.includes('Akumulatif'));
                    if (akumulatifConfig) {
                        const ctxAkumulatif = document.getElementById('chart_jawaban-akumulatif').getContext('2d');
                        const doughnutOptions = getPdfChartOptions(akumulatifConfig.options);
                        doughnutOptions.maintainAspectRatio = false;
                        new Chart(ctxAkumulatif, {
                            type: 'pie',
                            data: akumulatifConfig.data,
                            options: doughnutOptions
                        });
                    }

                    const kategoriConfig = mainChartConfigs.find(c => c.title.includes('Kategori'));
                    if (kategoriConfig) {
                        // 🎯 3. KODE JAVASCRIPT DIKEMBALIKAN SEPERTI SEMULA (TIDAK ADA PERUBAHAN LABEL)
                        const ctxKategori = document.getElementById('chart_distribusi-jawaban-per-kategori').getContext(
                            '2d');
                        const barOptions = getPdfChartOptions(kategoriConfig.options);
                        barOptions.maintainAspectRatio = false;
                        new Chart(ctxKategori, {
                            type: 'bar',
                            data: kategoriConfig.data, // Menggunakan data label asli
                            options: barOptions
                        });
                    }
                } catch (e) {
                    console.error('Error rendering main charts:', e);
                }
            @endif

            {{-- ... Blok JavaScript untuk pie chart tetap sama ... --}}
            @if (!empty($pieCharts))
                try {
                    const pieChartConfigs = @json($pieCharts);
                    pieChartConfigs.forEach((pieConfig, idx) => {
                        const ctxPie = document.getElementById('pie_chart_' + idx).getContext('2d');
                        new Chart(ctxPie, {
                            type: 'pie',
                            data: {
                                labels: pieConfig.labels,
                                datasets: [{
                                    data: pieConfig.data,
                                    backgroundColor: ['#EF4444', '#F59E0B', '#3B82F6',
                                        '#4dcba1'],
                                }]
                            },
                            options: getPdfChartOptions({
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            boxWidth: 12,
                                            font: {
                                                size: 10
                                            },
                                            generateLabels: function(chart) {
                                                const data = chart.data;
                                                if (data.labels.length && data.datasets
                                                    .length) {
                                                    const percentages = pieConfig
                                                        .percentages;
                                                    const meta = chart.getDatasetMeta(0);
                                                    return data.labels.map((label, i) => {
                                                        const style = meta
                                                            .controller.getStyle(i);
                                                        return {
                                                            text: `${label}  ${percentages[i]}%`,
                                                            fillStyle: style
                                                                .backgroundColor,
                                                            strokeStyle: style
                                                                .borderColor,
                                                            lineWidth: style
                                                                .borderWidth,
                                                            hidden: !chart
                                                                .getDataVisibility(
                                                                    i),
                                                            index: i
                                                        };
                                                    });
                                                }
                                                return [];
                                            }
                                        }
                                    }
                                }
                            })
                        });
                    });
                } catch (e) {
                    console.error('Error rendering pie charts:', e);
                }
            @endif

        })();
    </script>
</body>

</html>
