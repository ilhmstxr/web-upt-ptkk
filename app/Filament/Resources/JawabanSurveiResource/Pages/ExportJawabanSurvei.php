<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Exports\JawabanSurveiExport;
use Filament\Resources\Pages\Page;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Filament Page yang segera mem-trigger export Excel.
 * Route: filaments resource page akan mendaftarkan ini jika dipakai pada resource getPages().
 */
class ExportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;

    // view minimal (dibutuhkan oleh Filament Page class)
    protected static string $view = 'filament.resources.jawaban-survei-resource.pages.export-jawaban-survei';

    /**
     * mount dipanggil saat page dibuka. Kembalikan Excel download.
     * Ini akan langsung mengirimkan file Excel ke browser.
     */
    public function mount(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        // Jika ingin filter / query, ambil param request mis. request()->query('pelatihan')
        return Excel::download(new JawabanSurveiExport(), 'jawaban_survei.xlsx');
    }
}
