<?php

namespace App\Http\Controllers;

use App\Filament\Resources\JawabanSurveiResource\Pages\ReportJawabanSurvei;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class ExportController extends Controller
{
    public function jawabanAkumulatifPdf(Request $request)
    {
        $pelatihanId = $request->integer('pelatihanId');

        $url = ReportJawabanSurvei::getUrl([
            'pelatihanId' => $pelatihanId,
            'print'       => 1,
        ]);

        $cookieName  = config('session.cookie');
        $cookieValue = $request->cookie($cookieName);
        $domain      = parse_url(config('app.url'), PHP_URL_HOST) ?: $request->getHost();

        $dir = storage_path('app/tmp');
        File::ensureDirectoryExists($dir);

        $filename = 'report-jawaban-survei'
            . ($pelatihanId ? "-pelatihan-{$pelatihanId}" : '')
            . '-' . now()->format('Ymd_His') . '.pdf';

        $fullpath = $dir.'/'.Str::uuid().'.pdf';

        Browsershot::url($url)
            ->setCookies([[
                'name'   => $cookieName,
                'value'  => $cookieValue,
                'domain' => $domain,
                'path'   => '/',
            ]])
            ->waitUntilNetworkIdle()
            ->setDelay(1500)
            ->emulateMedia('screen')
            ->showBackground()
            ->format('A4')
            ->margins(10, 10, 10, 10)
            ->timeout(120)
            ->savePdf($fullpath);

        // Selalu paksa unduh
        return response()
            ->download($fullpath, $filename, ['Content-Type' => 'application/pdf'])
            ->deleteFileAfterSend(true);
    }
}
