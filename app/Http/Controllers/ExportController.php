<?php

namespace App\Http\Controllers;

use App\views\welcome;
use App\Filament\Resources\JawabanSurveiResource\Pages\ReportJawabanSurvei;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanAkumulatifChart;
use App\Filament\Resources\JawabanSurveiResource\Widgets\JawabanPerKategoriChart;
use App\Filament\Resources\JawabanSurveiResource\Widgets\PiePerPertanyaanWidget;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Spatie\Browsershot\Browsershot;
use Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot;

class ExportController extends Controller
{
    public function generateReportPdf($pelatihanId, Request $request)
    {
        $pelatihan = Pelatihan::find($pelatihanId);
        if (!$pelatihan) {
            abort(404, 'Data Pelatihan tidak ditemukan.');
        }

        $url = ReportJawabanSurvei::getUrl(['pelatihanId' => $pelatihanId]) . '&print=true';
        // $url = welcome::getUrl().'&print=true';

        try {
            $sessionCookieName = config('session.cookie');
            $sessionId = request()->cookie($sessionCookieName);

            $pdf = Browsershot::url($url)
                // ->setChromePath('C:\Program Files\Google\Chrome\Application\chrome.exe')   // Windows/Laragon
                ->setNodeBinary('C:\Program Files\nodejs\node.exe')
                ->setPuppeteerOptions(['protocolTimeout' => 360000]) // 180s
                ->timeout(60000)
                ->waituntil('networkidle0')
                ->setCookie($sessionCookieName, $sessionId, ['domain' => '127.0.0.1'])      // bawa sesi agar tidak redirect login
                ->emulateMedia('screen')    // atau 'print' sesuai CSS
                ->showBackground()
                ->format('A4')
                ->waitForFunction('window.__reportReady === true')
                ->pdf();


            $filename = 'laporan-pelatihan-' . $pelatihanId . '.pdf';

            return Response::make($pdf, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                // 'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (CouldNotTakeBrowsershot $e) {
            // Memberikan pesan error yang lebih informatif jika gagal
            return response("Gagal membuat PDF. Pesan error: <pre>{$e->getMessage()}</pre>", 500);
        }
    }

    public function generatePdf(Request $request)
    {
        // 1. Validasi input, contoh: pelatihanId
        $request->validate(['pelatihanId' => 'required|integer|exists:pelatihan,id']);
        $pelatihanId = $request->input('pelatihanId');
        $pelatihan = Pelatihan::find($pelatihanId); // Ambil detail pelatihan jika perlu

        // 2. Siapkan data dengan menggunakan kembali logika dari widget Filament Anda
        // Ini adalah cara efisien untuk menghindari duplikasi kode.

        // Data Chart Akumulatif
        $akumulatifWidget = new JawabanAkumulatifChart();
        $akumulatifWidget->pelatihanId = $pelatihanId;
        $akumulatifChartData = [
            'heading' => $akumulatifWidget::getHeading(),
            'data' => $akumulatifWidget->getData(),
        ];

        // Data Chart Per Kategori
        $perKategoriWidget = new JawabanPerKategoriChart();
        $perKategoriWidget->pelatihanId = $pelatihanId;
        $perKategoriChartData = [
            'heading' => $perKategoriWidget::getHeading(),
            'data' => $perKategoriWidget->getData(),
        ];

        // Data Chart Per Pertanyaan
        $perPertanyaanWidget = new PiePerPertanyaanWidget();
        $perPertanyaanWidget->pelatihanId = $pelatihanId;
        $perPertanyaanWidget->mount(); // Panggil mount() karena data disiapkan di sana
        $perPertanyaanChartsData = $perPertanyaanWidget->charts;

        // 3. Kumpulkan semua data untuk dikirim ke view
        $viewData = [
            'title' => 'Laporan Hasil Survei',
            'subtitle' => 'Evaluasi Pelatihan: ' . ($pelatihan->nama ?? 'ID ' . $pelatihanId),
            'akumulatifChartData' => $akumulatifChartData,
            'perKategoriChartData' => $perKategoriChartData,
            'perPertanyaanChartsData' => $perPertanyaanChartsData,
        ];

        // 4. Render view Blade menjadi string HTML
        $html = view('report-page-pdf', $viewData)->render();

        // 5. Konversi HTML menjadi PDF menggunakan Browsershot
        try {
            $pdf = Browsershot::html($html)
                // ->setNodeBinary(config('services.browsershot.node_binary')) // Sesuaikan path jika perlu
                // ->setNpmBinary(config('services.browsershot.npm_binary'))   // Sesuaikan path jika perlu
                ->format('A4')
                ->margins(20, 20, 20, 20, 'mm')
                // Tunggu hingga JS selesai merender chart sebelum mengambil "gambar" halaman
                ->waitUntil('window.status === "charts-rendered"', 5000)
                ->pdf();
        } catch (\Exception $e) {
            // Tangani error jika Browsershot gagal (misal: path node/npm salah, timeout)
            return response('Gagal membuat PDF: ' . $e->getMessage(), 500);
        }

        // 6. Kirimkan PDF sebagai response untuk di-download atau ditampilkan di browser
        $fileName = 'report-survei-pelatihan-' . $pelatihanId . '.pdf';
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "inline; filename=\"{$fileName}\"");
    }
}
