<?php

namespace App\Http\Controllers;

use App\Models\Tes;
use App\Models\Percobaan;
use App\Models\JawabanUser;
use Illuminate\Http\Request;

class TesController extends Controller
{

    public function index()
    {
        $tes = Tes::with(['kompetensi', 'pelatihan'])->get();
        return view('tes.index', compact('tes'));
    }

    public function show(Tes $tes, Request $request)
    {
        [$pesertaId, $pesertaSurveiId] = $this->getPesertaContext();

        // Harus ada salah satu identitas
        if (!$pesertaId && !$pesertaSurveiId) {
            return redirect()->route('dashboard.home')
                ->with('error', 'Silakan pilih/aktifkan peserta terlebih dahulu.');
        }

        // 1 percobaan per peserta per tes (sesuai kebijakanmu)
        $percobaan = $this->firstOrCreatePercobaan($tes->id, $pesertaId, $pesertaSurveiId);

        // Pastikan relasi di model Pertanyaan: function opsiJawaban() { ... }
        $pertanyaanList = $tes->pertanyaan()->with('opsiJawaban')->get();

        $currentQuestionIndex = max(0, (int) $request->query('q', 0));
        $pertanyaan = $pertanyaanList->get($currentQuestionIndex);

        // Jika index di luar jangkauan, arahkan ke hasil
        if (!$pertanyaan) {
            return redirect()->route('tes.result', $percobaan->id);
        }

        return view('tes.show', compact(
            'tes',
            'pertanyaan',
            'percobaan',
            'currentQuestionIndex',
            'pertanyaanList'
        ));
    }

    public function submit(Request $request, Percobaan $percobaan)
    {
        [$pesertaId, $pesertaSurveiId] = $this->getPesertaContext();

        if (!$this->isOwnerPercobaan($percobaan, $pesertaId, $pesertaSurveiId)) {
            abort(403, 'Unauthorized');
        }

        $jawaban = $request->input('jawaban', []);

        foreach ($jawaban as $pertanyaanId => $opsiJawabanId) {
            JawabanUser::updateOrCreate(
                ['percobaan_id' => $percobaan->id, 'pertanyaan_id' => $pertanyaanId],
                ['opsi_jawaban_id' => $opsiJawabanId]
            );
        }

        $total = $percobaan->tes->pertanyaan()->count();
        $currentQuestionIndex = (int) $request->query('q', 0);
        $nextQuestion = $currentQuestionIndex + 1;

        if ($nextQuestion >= $total) {

            if (method_exists($percobaan, 'hitungDanSimpanSkor')) {
                $percobaan->hitungDanSimpanSkor();
            } else {
                $percobaan->waktu_selesai = now();
                $percobaan->skor = $this->hitungSkor($percobaan);
                $percobaan->save();
            }

            return redirect()->route('tes.result', $percobaan->id);
        }

        return redirect()->route('tes.show', [
            'tes' => $percobaan->tes_id,
            'q'   => $nextQuestion
        ]);
    }

    public function result(Percobaan $percobaan)
    {
        [$pesertaId, $pesertaSurveiId] = $this->getPesertaContext();

        if (!$this->isOwnerPercobaan($percobaan, $pesertaId, $pesertaSurveiId)) {
            abort(403, 'Unauthorized');
        }

        $percobaan->loadMissing(['tes', 'jawabanUser.opsiJawaban']);
        return view('tes.result', compact('percobaan'));
    }

    protected function hitungSkor(Percobaan $percobaan): float|int
    {
        // Pastikan relasi di model Percobaan: function jawabanUser() { return $this->hasMany(...); }
        $jawabanUsers = $percobaan->jawabanUser()->with('opsiJawaban')->get();
        $total = $jawabanUsers->count();

        if ($total === 0) return 0;

        $benar = $jawabanUsers->filter(
            fn ($j) => ($j->opsiJawaban->apakah_benar ?? false)
        )->count();

        return round(($benar / $total) * 100, 2);
    }

    protected function getPesertaContext(): array
    {
        $pesertaId = session('peserta_id');
        $pesertaSurveiId = session('peserta_survei_id');

        return [
            $pesertaId ? (int) $pesertaId : null,
            $pesertaSurveiId ? (int) $pesertaSurveiId : null,
        ];
    }

    protected function firstOrCreatePercobaan(int $tesId, ?int $pesertaId, ?int $pesertaSurveiId): Percobaan
    {
        $keys = ['tes_id' => $tesId];

        if (!empty($pesertaSurveiId)) {
            $keys['peserta_survei_id'] = $pesertaSurveiId;
        } else {
            $keys['peserta_id'] = $pesertaId;
        }

        return Percobaan::firstOrCreate(
            $keys,
            ['waktu_mulai' => now()]
        );
    }

    protected function isOwnerPercobaan(Percobaan $percobaan, ?int $pesertaId, ?int $pesertaSurveiId): bool
    {
        // Kalau session survei aktif, validasi pakai peserta_survei_id
        if (!empty($pesertaSurveiId)) {
            return (int) $percobaan->peserta_survei_id === (int) $pesertaSurveiId;
        }

        // Kalau bukan survei, validasi pakai peserta_id
        if (!empty($pesertaId)) {
            return (int) $percobaan->peserta_id === (int) $pesertaId;
        }

        return false;
    }
}
