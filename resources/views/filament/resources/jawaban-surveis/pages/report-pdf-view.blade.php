{{-- resources/views/filament/resources/jawaban-surveis/pages/report-pdf-view.blade.php --}}
{{-- Versi tanpa <x-filament::page>. Render sebagai halaman HTML biasa untuk PDF/print. --}}
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Report Jawaban Survei' }}</title>

    {{-- Muat stylesheet Anda sendiri jika perlu. Hindari ketergantungan layout Filament. --}}
    {{-- Contoh: @vite(['resources/css/app.css']) --}}

    <style>
        /* Prefer inline CSS agar konsisten di DOMPDF/wkhtmltopdf */
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
        }

        .section {
            margin-top: 20px;
            break-inside: avoid;
        }

        .card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
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

        .muted {
            opacity: .65;
        }

        .page-break {
            page-break-before: always;
        }

        .no-break {
            break-inside: avoid;
        }

        /* Utility minimal */
        .mb-2 {
            margin-bottom: 8px
        }

        .mb-3 {
            margin-bottom: 12px
        }

        .mb-4 {
            margin-bottom: 16px
        }

        .mt-4 {
            margin-top: 16px
        }
    </style>
</head>

<body>
    <div class="container">
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

        {{-- === CONTOH BLOK RINGKASAN === --}}
        @isset($ringkasan)
            <section class="section card no-break">
                <h3 class="mb-2">Ringkasan</h3>
                {{-- $ringkasan bisa berupa array asosiatif --}}
                @if (is_array($ringkasan))
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
                @else
                    <div class="muted">{{ $ringkasan }}</div>
                @endif
            </section>
        @endisset

        {{-- === CONTOH BLOK TABEL DATA === --}}
        @isset($tabel)
            <section class="section card no-break">
                <h3 class="mb-2">Tabel Data</h3>
                <table class="table">
                    <thead>
                        <tr>
                            @foreach ($tabel['header'] ?? [] as $head)
                                <th>{{ $head }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tabel['rows'] ?? [] as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endisset

        {{-- === CONTOH BLOK CHART (Chart.js) === --}}
        @if (!empty($charts))
            <section class="section no-break">
                <div class="grid">
                    @foreach ($charts as $i => $chart)
                        <div class="col-6 card">
                            <h4 class="mb-2">{{ $chart['title'] ?? 'Chart ' . ($i + 1) }}</h4>
                            <canvas id="chart_{{ $i }}" width="600" height="320"></canvas>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Tempatkan konten laporan lain di bawah ini --}}
        @yield('report-extra')
    </div>

    {{-- Opsional: Chart.js dari CDN. Abaikan jika Anda sudah bundle aset sendiri. --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Jika controller mengoper $charts dengan dataset Chart.js, inisialisasi di sini
        // Struktur ekspektasi minimal per item: { type, data, options }
        @if (!empty($charts))
            (function() {
                if (!window.Chart) return; // Chart.js tidak dimuat
                try {
                    const configs = @json($charts);
                    configs.forEach((cfg, idx) => {
                        const ctx = document.getElementById('chart_' + idx).getContext('2d');
                        const options = cfg.options || {};
                        // Pastikan animasi 0 & tandai selesai render untuk keperluan PDF
                        options.animation = options.animation || {};
                        options.animation.duration = 0;
                        options.animation.onComplete = function() {
                            window.dispatchEvent(new Event('chartjs:rendered'));
                        };
                        new Chart(ctx, {
                            type: cfg.type || 'bar',
                            data: cfg.data || {},
                            options
                        });
                    });
                } catch (e) {
                    console.error(e);
                }
            })();
        @endif

        // Sinyal siap-cetak untuk engine PDF (wkhtmltopdf/Chromium headless)
        (function() {
            let pending = 0;

            function markReady() {
                pending = Math.max(0, pending - 1);
                if (pending === 0) {
                    document.documentElement.setAttribute('data-report-ready', '1');
                }
            }
            window.addEventListener('load', function() {
                // Jika tidak ada chart, tandai siap langsung
                @if (empty($charts))
                    document.documentElement.setAttribute('data-report-ready', '1');
                @else
                    // Estimasikan jumlah chart sebagai pekerjaan tertunda
                    pending = ({{ is_countable($charts) ? count($charts) : 0 }});
                    if (pending === 0) document.documentElement.setAttribute('data-report-ready', '1');
                    window.addEventListener('chartjs:rendered', markReady);
                @endif
            });
        })();
    </script>

    {{-- Trigger print otomatis bila query ?print=1 --}}
    @if (request()->boolean('print'))
        <script>
            window.addEventListener('load', function() {
                // Tunggu sedikit agar font/render stabil
                setTimeout(function() {
                    window.print();
                }, 300);
            });
        </script>
    @endif

</body>

</html>
