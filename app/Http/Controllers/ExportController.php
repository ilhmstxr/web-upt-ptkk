<?php

namespace App\Http\Controllers;

use App\Filament\Resources\JawabanSurveiResource\Pages\ReportJawabanSurvei as ReportPage;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Spatie\Browsershot\Enums\Polling;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function reportJawabanSurvei(Request $request): StreamedResponse
    {
        $pelatihanId = $request->integer('pelatihanId');

        $pageUrl = ReportPage::getUrl([
            'print' => true,
            ...($pelatihanId !== null ? ['pelatihanId' => $pelatihanId] : []),
        ], true);

        $pelatihanName = $pelatihanId
            ? Pelatihan::whereKey($pelatihanId)->value('nama_pelatihan')
            : null;

        $filename = 'report-jawaban-survei-'
            . Str::slug($pelatihanName ?: 'semua-pelatihan')
            . '-' . now()->format('Ymd_His') . '.pdf';

        $browsershot = Browsershot::url($pageUrl)
            ->noSandbox()
            ->emulateMedia('screen')
            ->showBackground()
            ->windowSize(800, 600)
            ->deviceScaleFactor(2)
            ->format('A4')
            ->margins(12, 12, 16, 12)
            ->setDelay(300)
            ->setPuppeteerOption('waitUntil', 'domcontentloaded')
            ->setPuppeteerOption('protocolTimeout', 0)
            ->waitForFunction(
                'window.__PDF_READY__ === true || window.__chartsReady === true',
                Polling::RequestAnimationFrame,
                120000
            )
            ->timeout(240);

        // Persist sesi/auth (tanpa header Cookie manual)
        $cookies = $this->buildBrowsershotCookies($request, $pageUrl);
        if (!empty($cookies)) {
            $browsershot->setCookies($cookies);
        }

        // Opsional: teruskan User-Agent nyata
        if ($ua = $request->header('User-Agent')) {
            $browsershot->setExtraHttpHeaders(['User-Agent' => $ua]);
        }

        $pdfBinary = $browsershot->pdf();

        return response()->streamDownload(
            static function () use ($pdfBinary) {
                echo $pdfBinary;
            },
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }

    private function buildBrowsershotCookies(Request $request, string $pageUrl): array
    {
        $domain = parse_url($pageUrl, PHP_URL_HOST) ?: 'localhost';

        $cookies = [];
        foreach ($request->cookies->all() as $name => $value) {
            $cookies[] = [
                'name'   => (string) $name,
                'value'  => is_array($value) ? (string) reset($value) : (string) $value,
                'domain' => $domain,
                'path'   => '/',
            ];
        }

        return $cookies;
    }
}
