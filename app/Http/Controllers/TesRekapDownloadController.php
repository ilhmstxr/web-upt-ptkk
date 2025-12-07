<?php

namespace App\Http\Controllers;

use App\Models\Tes;
use App\Models\Percobaan;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TesRekapDownloadController extends Controller
{
    public function download(Tes $tes): StreamedResponse
    {
        $rekap = Percobaan::where('tes_id', $tes->id)
            ->with(['peserta', 'pesertaSurvei'])
            ->orderByDesc('skor') // urut skor tertinggi dulu
            ->get()
            ->map(function (Percobaan $percobaan) {
                $nama = $percobaan->pesertaSurvei->nama
                    ?? $percobaan->peserta->nama
                    ?? '-';

                $skor = $percobaan->skor ?? 0;
                $lulus = $skor >= 75;

                // Durasi menit sebagai angka saja
                $durasiMenit = 0;
                if ($percobaan->waktu_mulai && $percobaan->waktu_selesai) {
                    $mulai   = $percobaan->waktu_mulai instanceof Carbon
                        ? $percobaan->waktu_mulai
                        : Carbon::parse($percobaan->waktu_mulai);

                    $selesai = $percobaan->waktu_selesai instanceof Carbon
                        ? $percobaan->waktu_selesai
                        : Carbon::parse($percobaan->waktu_selesai);

                    $durasiMenit = $mulai->diffInMinutes($selesai);
                }

                return [
                    'nama'   => trim($nama),
                    'skor'   => $skor,
                    'status' => $lulus ? 'Lulus' : 'Remedial',
                    'durasi' => $durasiMenit,
                ];
            });

        $fileName = 'rekap-nilai-tes-' . $tes->id . '.csv';

        return response()->streamDownload(function () use ($rekap) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($handle, [
                'Nama Peserta',
                'Nilai',
                'Status',
                'Durasi (menit)',
            ], ';');

            // Baris data
            foreach ($rekap as $row) {
                fputcsv($handle, [
                    $row['nama'],
                    $row['skor'],
                    $row['status'],
                    $row['durasi'] ?? 0,
                ], ';');
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
