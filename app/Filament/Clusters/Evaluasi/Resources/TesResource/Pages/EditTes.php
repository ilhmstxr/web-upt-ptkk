<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use App\Models\Pertanyaan;
use App\Models\Percobaan;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTes extends EditRecord
{
    protected static string $resource = TesResource::class;

    protected static string $view = 'filament.clusters.evaluasi.resources.tes-resource.pages.edit-tes';

    public function getMaxContentWidth(): ?string
    {
        return '7xl';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Kirim data tambahan ke Blade: distribusi soal & rekap nilai
     */
    protected function getViewData(): array
    {
        $tes = $this->record; // Tes yang sedang di-edit

        // --- 1) Analisis kesukaran soal untuk Tes ini ---
        $mudah = 0;
        $sedang = 0;
        $sulit = 0;

        $pertanyaans = Pertanyaan::where('tes_id', $tes->id)
            ->withCount([
                'jawabanUsers',
                'jawabanUsers as benar_count' => fn ($q) =>
                    $q->whereHas('opsiJawaban', fn ($q) => $q->where('apakah_benar', true)),
            ])
            ->get();

        foreach ($pertanyaans as $p) {
            $total = $p->jawaban_users_count;

            if ($total === 0) {
                continue; // belum ada data jawaban
            }

            $pIndex = $p->benar_count / $total; // 0–1

            if ($pIndex <= 0.30) {
                $sulit++;
            } elseif ($pIndex <= 0.70) {
                $sedang++;
            } else {
                $mudah++;
            }
        }

        // --- 2) Rekap nilai peserta untuk Tes ini ---
        $rekapNilai = Percobaan::where('tes_id', $tes->id)
            ->with(['peserta', 'pesertaSurvei']) // sama seperti TesPercobaanResource
            ->get()
            ->map(function (Percobaan $percobaan) {
                // Nama: prioritas PesertaSurvei, fallback Peserta
                $nama = $percobaan->pesertaSurvei->nama
                    ?? $percobaan->peserta->nama
                    ?? '-';

                // Durasi pengerjaan (menit)
                $durasi = null;
                if ($percobaan->waktu_mulai && $percobaan->waktu_selesai) {
                    $mulai = $percobaan->waktu_mulai instanceof Carbon
                        ? $percobaan->waktu_mulai
                        : Carbon::parse($percobaan->waktu_mulai);

                    $selesai = $percobaan->waktu_selesai instanceof Carbon
                        ? $percobaan->waktu_selesai
                        : Carbon::parse($percobaan->waktu_selesai);

                    $durasi = $mulai->diffInMinutes($selesai);
                }

                $skor = $percobaan->skor;

                // Tentukan lulus / remedial (misal KKM 75)
                $lulus = !is_null($skor) && $skor >= 75;

                return [
                    'nama'   => $nama,
                    'skor'   => $skor,
                    'lulus'  => $lulus,
                    'durasi' => $durasi, // menit, bisa null
                ];
            });

        return [
            'mudah'      => $mudah,
            'sedang'     => $sedang,
            'sulit'      => $sulit,
            'rekapNilai' => $rekapNilai,
        ];
    }

    /**
     * Download rekap nilai Tes ini dalam bentuk CSV (bisa dibuka di Excel)
     */
    public function downloadRekap()
    {
        $tes = $this->record;

        // Ambil data percobaan untuk Tes ini, sama logika dengan TesPercobaanResource
        $rekap = Percobaan::where('tes_id', $tes->id)
            ->with(['peserta', 'pesertaSurvei'])
            ->get()
            ->map(function (Percobaan $percobaan) {
                $nama = $percobaan->pesertaSurvei->nama
                    ?? $percobaan->peserta->nama
                    ?? '-';

                $skor = $percobaan->skor;
                $lulus = !is_null($skor) && $skor >= 75;

                // Hitung durasi menit
                $durasi = null;
                if ($percobaan->waktu_mulai && $percobaan->waktu_selesai) {
                    $mulai = $percobaan->waktu_mulai instanceof Carbon
                        ? $percobaan->waktu_mulai
                        : Carbon::parse($percobaan->waktu_mulai);

                    $selesai = $percobaan->waktu_selesai instanceof Carbon
                        ? $percobaan->waktu_selesai
                        : Carbon::parse($percobaan->waktu_selesai);

                    $durasi = $mulai->diffInMinutes($selesai);
                }

                return [
                    'nama'   => $nama,
                    'skor'   => $skor,
                    'status' => $lulus ? 'Lulus' : 'Remedial',
                    'durasi' => $durasi,
                ];
            });

        $fileName = 'rekap-nilai-tes-' . $tes->id . '.csv';

        return response()->streamDownload(function () use ($rekap) {
            $handle = fopen('php://output', 'w');

            // Header kolom
            fputcsv($handle, ['Nama Peserta', 'Skor', 'Status', 'Durasi (Menit)']);

            // Isi data
            foreach ($rekap as $row) {
                fputcsv($handle, [
                    $row['nama'],
                    $row['skor'],
                    $row['status'],
                    $row['durasi'] ?? '-',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
