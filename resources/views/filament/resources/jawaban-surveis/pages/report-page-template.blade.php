<!DOCTYPE html>
<html lang="id">
<head>
    <title>{{ $title ?? 'Laporan' }}</title>
    <meta charset="utf-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styles khusus untuk rendering PDF oleh Browsershot */
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            background-color: #ffffff;
            font-family: 'Inter', sans-serif;
        }
        @page {
            size: A4;
            margin: 20mm;
        }
        .avoid-break {
            page-break-inside: avoid;
        }
        .chart-container {
            position: relative;
            width: 100%;
            margin: 20px 0;
        }
        /* Fallback font jika Inter dari CDN gagal dimuat */
        @import url('https://rsms.me/inter/inter.css');
        html { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <main class="px-4">
        <!-- Bagian Header Laporan -->
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-800">{{ $title ?? 'Laporan Survei' }}</h1>
            @isset($subtitle)
                <p class="text-lg text-gray-600 mt-2">{{ $subtitle }}</p>
            @endisset
        </div>

        <!-- Chart Akumulatif -->
        @if (!empty($akumulatifChartData))
            <div class="avoid-break mb-10 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">{{ $akumulatifChartData['heading'] ?? 'Akumulatif Skala Likert' }}</h2>
                <div class="chart-container" style="height:400px">
                    <canvas id="akumulatifChart"></canvas>
                </div>
            </div>
        @endif

        <!-- Chart Per Kategori -->
        @if (!empty($perKategoriChartData))
            <div class="avoid-break mb-10 border border-gray-200 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">{{ $perKategoriChartData['heading'] ?? 'Distribusi Skala per Kategori' }}</h2>
                <div class="chart-container" style="height:400px">
                    <canvas id="perKategoriChart"></canvas>
                </div>
            </div>
        @endif


        <!-- Charts Per Pertanyaan -->
        @if (!empty($perPertanyaanChartsData))
            <div class="avoid-break">
                <h2 class="text-xl font-semibold mb-8 text-gray-700 border-b pb-2">Distribusi Skala per Pertanyaan</h2>
                <div class="space-y-8">
                    @foreach($perPertanyaanChartsData as $chart)
                        <div class="avoid-break border border-gray-200 rounded-lg p-6">
                            <h3 class="text-md font-medium text-gray-800 mb-3">{{ $chart['question_label'] }}</h3>
                            <div class="chart-container" style="height:350px;">
                                <canvas id="pertanyaanChart-{{ $chart['question_id'] }}"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Nonaktifkan animasi untuk semua chart agar rendering PDF lebih cepat dan andal
            Chart.defaults.animation.duration = 0;

            // Data yang dikirim dari controller
            const akumulatifData = @json($akumulatifChartData ?? null);
            const perKategoriData = @json($perKategoriChartData ?? null);
            const perPertanyaanData = @json($perPertanyaanChartsData ?? []);

            // 1. Render Chart Akumulatif (Pie)
            if (akumulatifData && document.getElementById('akumulatifChart')) {
                new Chart(document.getElementById('akumulatifChart'), {
                    type: 'pie',
                    data: {
                        labels: akumulatifData.data.labels,
                        datasets: akumulatifData.data.datasets,
                    },
                    options: {
                        ...(akumulatifData.data.options || {}),
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }

            // 2. Render Chart Per Kategori (Bar)
            if (perKategoriData && document.getElementById('perKategoriChart')) {
                 new Chart(document.getElementById('perKategoriChart'), {
                    type: 'bar',
                    data: {
                        labels: perKategoriData.data.labels,
                        datasets: perKategoriData.data.datasets,
                    },
                    options: {
                        ...(perKategoriData.data.options || {}),
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }

            // 3. Render Charts Per Pertanyaan (Multiple Pie)
            if (perPertanyaanData.length > 0) {
                perPertanyaanData.forEach(chartData => {
                    const canvasId = `pertanyaanChart-${chartData.question_id}`;
                    const ctx = document.getElementById(canvasId);
                    if (ctx) {
                        const total = chartData.data.reduce((a, b) => a + b, 0);
                        const labelsWithPercent = chartData.labels.map((label, index) => {
                             const percentage = total > 0 ? ((chartData.data[index] / total) * 100).toFixed(1) : 0;
                             // Menggunakan format koma sesuai contoh sebelumnya
                             return `${label} — ${String(percentage).replace('.', ',')}%`;
                        });

                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: labelsWithPercent,
                                datasets: [{
                                    label: 'Jumlah Jawaban',
                                    data: chartData.data,
                                    backgroundColor: [
                                        'rgba(248,113,113,0.7)', // Merah
                                        'rgba(251,191,36,0.7)', // Kuning
                                        'rgba(59,130,246,0.7)',  // Biru
                                        'rgba(16,185,129,0.7)',   // Hijau
                                    ],
                                    borderColor: [
                                        'rgb(239,68,68)',
                                        'rgb(245,158,11)',
                                        'rgb(59,130,246)',
                                        'rgb(16,185,129)',
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'right' },
                                    tooltip: { enabled: true }
                                }
                            }
                        });
                    }
                });
            }

            // Memberi sinyal kepada Browsershot bahwa semua chart telah selesai dirender
            window.status = 'charts-rendered';
        });
    </script>

</body>
</html>
