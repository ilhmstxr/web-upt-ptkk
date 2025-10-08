<?php

namespace App\Http\Controllers;

use App\Filament\Resources\JawabanSurveiResource\Pages\ReportJawabanSurvei;
use App\Filament\Resources\JawabanSurveiResource\Widgets\BuildsLikertData;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart;
use App\Filament\Resources\JawabanSurveiResource\Widgets\PiePerPertanyaanWidget;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Knp\Snappy\Pdf;
use ReflectionObject;

class ExportController extends Controller
{
    use BuildsLikertData; // <- INI YANG BENAR, bukan `trait BuildsLikertData;`

    // browsershot
    // public function generateReportPdf($pelatihanId, Request $request)
    // {
    //     $pelatihan = Pelatihan::find($pelatihanId);
    //     if (!$pelatihan) {
    //         abort(404, 'Data Pelatihan tidak ditemukan.');
    //     }

    //     $url = ReportJawabanSurvei::getUrl(['pelatihanId' => $pelatihanId]) . '&print=true';
    //     // $url = welcome::getUrl().'&print=true';

    //     try {
    //         $sessionCookieName = config('session.cookie');
    //         $sessionId = request()->cookie($sessionCookieName);

    //         $pdf = Browsershot::url($url)
    //             // ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe')   // Windows/Laragon
    //             // ->setNodeBinary('C:\Program Files\nodejs\node.exe')
    //             // ->setPuppeteerOptions(['protocolTimeout' => 360000]) // 180s
    //             ->timeout(60000)
    //             ->waituntil('networkidle0')
    //             ->setCookie($sessionCookieName, $sessionId, ['domain' => '127.0.0.1'])      // bawa sesi agar tidak redirect login
    //             ->emulateMedia('screen')    // atau 'print' sesuai CSS
    //             ->showBackground()
    //             ->format('A4')
    //             ->waitForFunction('window.__reportReady === true')
    //             ->pdf();


    //         $filename = 'laporan-pelatihan-' . $pelatihanId . '.pdf';

    //         return Response::make($pdf, 200, [
    //             'Content-Type'        => 'application/pdf',
    //             'Content-Disposition' => 'inline; filename="' . $filename . '"',
    //             // 'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    //         ]);
    //     } catch (CouldNotTakeBrowsershot $e) {
    //         // Memberikan pesan error yang lebih informatif jika gagal
    //         return response("Gagal membuat PDF. Pesan error: <pre>{$e->getMessage()}</pre>", 500);
    //     }
    // }

    // snappy
    // public function generateReportPdf($pelatihanId)
    // {
    //     // 1. Validasi dan ambil data Pelatihan
    //     $pelatihan = Pelatihan::find($pelatihanId);
    //     if (!$pelatihan) {
    //         abort(404, 'Data Pelatihan tidak ditemukan.');
    //     }

    //     // 2. Siapkan data dengan menggunakan kembali logika dari widget Filament Anda.
    //     // Ini adalah cara yang efisien untuk menghindari duplikasi kode.

    //     // Data Chart Akumulatif
    //     $akumulatifWidget = new JawabanAkumulatifChart();
    //     $akumulatifWidget->pelatihanId = $pelatihanId;

    //     // FIX: Panggil method 'getData' yang protected menggunakan Reflection
    //     $reflectionAkumulatif = new ReflectionObject($akumulatifWidget);
    //     $methodAkumulatif = $reflectionAkumulatif->getMethod('getData');
    //     $methodAkumulatif->setAccessible(true);
    //     $akumulatifChartData = $methodAkumulatif->invoke($akumulatifWidget);

    //     // Data Chart Per Kategori
    //     $perKategoriWidget = new JawabanPerKategoriChart();
    //     $perKategoriWidget->pelatihanId = $pelatihanId;

    //     // FIX: Panggil method 'getData' yang protected menggunakan Reflection
    //     $reflectionKategori = new ReflectionObject($perKategoriWidget);
    //     $methodKategori = $reflectionKategori->getMethod('getData');
    //     $methodKategori->setAccessible(true);
    //     $perKategoriChartData = $methodKategori->invoke($perKategoriWidget);

    //     // Data Chart Per Pertanyaan
    //     $perPertanyaanWidget = new PiePerPertanyaanWidget();
    //     $perPertanyaanWidget->pelatihanId = $pelatihanId;
    //     $perPertanyaanWidget->mount(); // Panggil mount() karena data disiapkan di sana
    //     $perPertanyaanChartsData = $perPertanyaanWidget->charts;

    //     // 3. Kumpulkan semua data untuk dikirim ke view PDF
    //     $viewData = [
    //         'title' => 'Laporan Hasil Survei',
    //         'subtitle' => 'Evaluasi Pelatihan: ' . $pelatihan->nama,
    //         'akumulatifChartData' => $akumulatifChartData,
    //         'perKategoriChartData' => $perKategoriChartData,
    //         'perPertanyaanChartsData' => $perPertanyaanChartsData,
    //     ];

    //     // 4. Muat view Blade dan teruskan data ke dalamnya
    //     // 'pdfs.report' mengacu pada file di resources/views/pdfs/report.blade.php
    //     // $pdf = Pdf::loadView('pdfs.report', $viewData);
    //     // $pdf = Pdf::loadView('pdfs.report', $viewData);

    //     // // 5. (Opsional) Atur opsi untuk PDF
    //     // $pdf->setPaper('a4', 'portrait');
    //     // $pdf->setOption('enable-javascript', true); // Penting untuk merender Chart.js
    //     // $pdf->setOption('javascript-delay', 2000); // Beri waktu 2 detik untuk JS merender chart
    //     // $pdf->setOption('no-stop-slow-scripts', true);

    //     // // 6. Tampilkan atau download PDF
    //     // $filename = 'laporan-survei-' . $pelatihanId . '.pdf';

    //     // // Tampilkan di browser
    //     // return $pdf->inline($filename);

    //     // Atau jika ingin langsung download, gunakan:
    //     // return $pdf->download($filename);
    // }





    /**
     * Render halaman laporan PDF tanpa layout Filament.
     * Menggunakan fungsi-fungsi pada trait BuildsLikertData sebagai sumber data chart.
     */
    public function pdfView(Request $request, int $pelatihanId)
    {
        // Filter tanggal opsional
        $range = [
            'from' => $request->date('from'),
            'to' => $request->date('to'),
        ];

        // --- Bagian Ringkasan & Chart Lain (Bar & Line) ---
        // Kode di bagian ini bisa tetap sama jika sudah berfungsi
        $pertanyaanIdsForSummary = $this->collectPertanyaanIds($pelatihanId);
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIdsForSummary);
        $answers = $this->normalizedAnswers($pelatihanId, $pertanyaanIdsForSummary, $pivot, $opsiIdToSkala, $opsiTextToId);
        $onlyScored = $answers->filter(fn($a) => $a['skala'] !== null);
        $avgSkala = $onlyScored->avg(fn($a) => (float) $a['skala']);
        $fmt1 = static fn($n) => number_format((float) $n, 1, ',', '.');
        $ringkasan = [
            'Jumlah Pertanyaan Likert' => $pertanyaanIdsForSummary->count(),
            'Total Jawaban Terskala' => $onlyScored->count(),
            'Rata-rata Skala Likert' => $avgSkala ? $fmt1($avgSkala) : '0,0',
        ];

        $bar = $this->buildPerKategori($pelatihanId, $range);
        $line = $this->buildAkumulatif($pelatihanId, $range);
        $barData = ['labels' => $bar['labels'] ?? [], 'datasets' => $bar['datasets'] ?? []];
        $lineData = ['labels' => $line['labels'] ?? [], 'datasets' => $line['datasets'] ?? []];
        // --------------------------------------------------


        // =======================================================
        // AWAL DARI LOGIKA BARU UNTUK PIE CHARTS
        // =======================================================

        // 1. Ambil SEMUA ID pertanyaan terlebih dahulu
        $piePertanyaanIds = $this->collectPertanyaanIds($pelatihanId);

        // 2. Siapkan array kosong untuk menampung data setiap pie chart
        $pieCharts = [];

        // 3. Looping setiap ID pertanyaan
        foreach ($piePertanyaanIds as $pertanyaanId) {
            // 4. Panggil buildPiePerPertanyaan UNTUK SATU ID PADA SETIAP ITERASI
            $singlePieData = $this->buildPiePerPertanyaan($pelatihanId, $pertanyaanId, $range);

            // 5. Masukkan hasilnya ke dalam array jika tidak kosong
            if (!empty($singlePieData)) {
                $pieCharts[] = $singlePieData;
            }
        }
        // =======================================================
        // AKHIR DARI LOGIKA BARU
        // =======================================================

        // Siapkan data chart utama (tanpa pie chart)
        $charts = [
            ['title' => 'Jawaban Akumulatif', 'type' => 'line', 'data' => $lineData, 'options' => $line['options'] ?? []],
            ['title' => 'Distribusi Jawaban per Kategori', 'type' => 'bar', 'data' => $barData, 'options' => $bar['options'] ?? []],
        ];

        // return $charts;

        // Render Blade dan kirim semua data yang dibutuhkan
        return view('filament.resources.jawaban-surveis.pages.report-pdf-view', [
            'title' => 'Laporan Jawaban Survei',
            'subtitle' => 'Pelatihan #' . $pelatihanId,
            'pelatihanId' => $pelatihanId,
            'ringkasan' => $ringkasan,
            'charts' => $charts,
            'pieCharts' => $pieCharts, // Kirim array berisi semua data pie chart ke view
        ]);
    }
}
