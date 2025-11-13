<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use Filament\Resources\Pages\Page;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\JawabanUser;
use App\Models\Pelatihan;
use App\Models\Pertanyaan;

class ExportJawabanSurvei extends Page
{
    protected static string $resource = JawabanSurveiResource::class;
    protected static string $view = 'exports.survei-report';
    protected static ?string $title = 'Export Laporan Survei';

    public function mount()
    {
        // Ambil id pelatihan dari query ?pelatihan_id=
        $pelatihanId = request()->get('pelatihan_id');

        $pelatihan = Pelatihan::find($pelatihanId);
        $judulMonev = $pelatihan->judul_monev ?? 'Monitoring dan Evaluasi Pelatihan';
        $angkatan = $pelatihan->angkatan ?? '-';

        // Ambil data agregat untuk grafik total
        $totalJawaban = JawabanUser::where('pelatihan_id', $pelatihanId)->count();
        $jawabanPerSoal = Pertanyaan::withCount(['jawabanUser as total_jawaban' => function ($q) use ($pelatihanId) {
            $q->where('pelatihan_id', $pelatihanId);
        }])->get();

        // Buat PDF
        $pdf = Pdf::loadView('exports.survei-report', [
            'pelatihan' => $pelatihan,
            'judulMonev' => $judulMonev,
            'angkatan' => $angkatan,
            'totalJawaban' => $totalJawaban,
            'jawabanPerSoal' => $jawabanPerSoal,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'Laporan_Survei_'.$pelatihan->nama.'.pdf'
        );
    }
}
