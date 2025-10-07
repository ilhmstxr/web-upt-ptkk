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
        // return true;
        // Filter tanggal opsional
        $range = [
            'from' => $request->date('from'),
            'to' => $request->date('to'),
        ];


        // Kumpulkan pertanyaan Likert yang relevan
        $pertanyaanIds = $this->collectPertanyaanIds($pelatihanId);


        // Bangun peta likert & normalisasi jawaban untuk ringkasan
        [$pivot, $opsiIdToSkala, $opsiTextToId] = $this->buildLikertMaps($pertanyaanIds);
        $answers = $this->normalizedAnswers($pelatihanId, $pertanyaanIds, $pivot, $opsiIdToSkala, $opsiTextToId);


        // Ringkasan sederhana
        $onlyScored = $answers->filter(fn($a) => $a['skala'] !== null);
        $avgSkala = $onlyScored->avg(fn($a) => (float) $a['skala']);
        $fmt1 = static fn($n) => number_format((float) $n, 1, ',', '.');
        $ringkasan = [
            'Jumlah Pertanyaan Likert' => $pertanyaanIds->count(),
            'Total Jawaban Terskala' => $onlyScored->count(),
            'Rata-rata Skala Likert' => $avgSkala ? $fmt1($avgSkala) : '0,0',
        ];


        // Ambil data chart dari trait
        $bar = $this->buildPerKategori($pelatihanId, $range); // {labels, datasets, options}
        $pie = $this->buildPiePerPertanyaan($pelatihanId, $range, $pertanyaanIds->first()); // {labels, datasets, options}
        $line = $this->buildAkumulatif($pelatihanId, $range); // {labels, datasets, options}


        // Normalisasi ke struktur Chart.js: data = {labels, datasets}
        $barData = ['labels' => $bar['labels'] ?? [], 'datasets' => $bar['datasets'] ?? []];
        $pieData = ['labels' => $pie['labels'] ?? [], 'datasets' => $pie['datasets'] ?? []];
        $lineData = ['labels' => $line['labels'] ?? [], 'datasets' => $line['datasets'] ?? []];


        $charts = [
            ['title' => 'Distribusi Jawaban per Kategori', 'type' => 'bar', 'data' => $barData, 'options' => $bar['options'] ?? []],
            ['title' => 'Komposisi per Pertanyaan', 'type' => 'doughnut', 'data' => $pieData, 'options' => $pie['options'] ?? []],
            ['title' => 'Jawaban Akumulatif', 'type' => 'line', 'data' => $lineData, 'options' => $line['options'] ?? []],
        ];


        // Render Blade non-Filament (pastikan view ini tidak memakai <x-filament::page>)
        return view('filament.resources.jawaban-surveis.pages.report-pdf-view', [
            'title' => 'Laporan Jawaban Survei',
            'subtitle' => 'Pelatihan #' . $pelatihanId,
            'pelatihanId' => $pelatihanId,
            'ringkasan' => $ringkasan,
            'charts' => $charts,
            // 'tabel' => [...], // opsional jika butuh tabel tambahan
        ]);
    }
}
