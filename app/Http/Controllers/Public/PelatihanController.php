<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use App\Services\StatistikPelatihanService;

class PelatihanController extends Controller
{
    /**
     * Tampilkan daftar semua pelatihan.
     * Metode ini akan dipanggil oleh route('pelatihan.index').
     */
    public function index()
    {
        // Ambil semua data pelatihan (atau yang sudah terpublikasi/terjadwal)
        $pelatihans = Pelatihan::with('instansi')
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(12);

        // Pastikan Anda memiliki view 'pages.pelatihan.index'
        return view('pages.pelatihan.index', compact('pelatihans'));
    }

    public function show($id)
    {
        // 1) Ambil pelatihan + relasi yang dipakai di blade kamu
        $pelatihan = Pelatihan::with([
            'pendaftaranPelatihan.peserta.instansi',
            'kompetensiPelatihan.kompetensi',
        ])->findOrFail($id);

        // 2) Rekap nilai (per pelatihan)
        $statistik = $this->buildStatistikPelatihan($pelatihan->id);

        // 3) Rincian nilai per kompetensi
        $competencies = $this->buildRincianPerKompetensi($pelatihan->id);

        // 4) Evaluation data untuk tab hasil (selaras dengan UI yang kamu tulis)
        $avgPre  = (float) ($statistik['pretest']['avg'] ?? 0);
        $avgPost = (float) ($statistik['posttest']['avg'] ?? 0);

        $evalData = [
            'hasData' => (($statistik['pretest']['count'] ?? 0) > 0) || (($statistik['posttest']['count'] ?? 0) > 0),

            'avgPretest' => round($avgPre, 2),
            'avgPosttest' => round($avgPost, 2),
            'improvement' => round(max(0, $avgPost - $avgPre), 2),

            // lulus/remedial dari nilai_post_test
            'lulus' => (int) ($statistik['posttest']['lulus'] ?? 0),
            'remedial' => (int) ($statistik['posttest']['remedial'] ?? 0),
            'total_peserta' => (int) ($statistik['monev']['total_peserta'] ?? 0),

            // CSAT belum kamu sambung DB survei -> amanin dulu
            'csat' => 0,
            'respondents' => 0,

            'competencies' => $competencies,
        ];

        return view('pelatihan.show', compact('pelatihan', 'statistik', 'evalData'));
    }

    /**
     * Rekap PER PELATIHAN dari tabel pendaftaran_pelatihan
     */
    private function buildStatistikPelatihan(int $pelatihanId): array
    {
        $base = DB::table('pendaftaran_pelatihan as pp')
            ->where('pp.pelatihan_id', $pelatihanId);

        $pre = (clone $base)->selectRaw("
            COUNT(NULLIF(pp.nilai_pre_test, 0)) as count,
            COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as avg,
            COALESCE(MAX(NULLIF(pp.nilai_pre_test, 0)), 0) as max,
            COALESCE(MIN(NULLIF(pp.nilai_pre_test, 0)), 0) as min
        ")->first();

        $post = (clone $base)->selectRaw("
            COUNT(NULLIF(pp.nilai_post_test, 0)) as count,
            COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as avg,
            COALESCE(MAX(NULLIF(pp.nilai_post_test, 0)), 0) as max,
            COALESCE(MIN(NULLIF(pp.nilai_post_test, 0)), 0) as min,
            SUM(CASE WHEN pp.nilai_post_test >= 75 THEN 1 ELSE 0 END) as lulus,
            SUM(CASE WHEN pp.nilai_post_test > 0 AND pp.nilai_post_test < 75 THEN 1 ELSE 0 END) as remedial
        ")->first();

        $totalPeserta = (clone $base)->count();

        return [
            'pretest' => [
                'avg' => (float) ($pre->avg ?? 0),
                'max' => (float) ($pre->max ?? 0),
                'min' => (float) ($pre->min ?? 0),
                'count' => (int) ($pre->count ?? 0),
            ],
            'posttest' => [
                'avg' => (float) ($post->avg ?? 0),
                'max' => (float) ($post->max ?? 0),
                'min' => (float) ($post->min ?? 0),
                'count' => (int) ($post->count ?? 0),
                'lulus' => (int) ($post->lulus ?? 0),
                'remedial' => (int) ($post->remedial ?? 0),
            ],
            'monev' => [
                'total_peserta' => (int) $totalPeserta,
                'responden' => 0,
                'percentage' => 0,
            ],
        ];
    }

    /**
     * Rekap PER KOMPETENSI dalam satu pelatihan
     */
    private function buildRincianPerKompetensi(int $pelatihanId): array
    {
        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('kompetensi_pelatihan as kp', 'kp.id', '=', 'pp.kompetensi_pelatihan_id')
            ->join('kompetensi as k', 'k.id', '=', 'kp.kompetensi_id')
            ->where('pp.pelatihan_id', $pelatihanId)
            ->groupBy('kp.id', 'k.nama_kompetensi')
            ->orderBy('k.nama_kompetensi')
            ->get([
                'kp.id as kompetensi_pelatihan_id',
                'k.nama_kompetensi as name',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pretest'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as posttest'),
            ]);

        return $rows->map(function ($r) {
            $post = (float) $r->posttest;

            // Status simple (silakan ubah)
            if ($post >= 85) {
                $status = 'Sangat Baik';
                $badge = 'bg-green-100 text-green-700';
            } elseif ($post >= 75) {
                $status = 'Baik';
                $badge = 'bg-blue-100 text-blue-700';
            } elseif ($post > 0) {
                $status = 'Cukup';
                $badge = 'bg-yellow-100 text-yellow-700';
            } else {
                $status = 'Belum Ada Data';
                $badge = 'bg-gray-100 text-gray-600';
            }

            return [
                'id' => (int) $r->kompetensi_pelatihan_id,
                'name' => (string) $r->name,
                'pretest' => (float) $r->pretest,
                'posttest' => $post,
                'kepuasan' => 0, // nanti kalau sudah ada tabel survei
                'status' => $status,
                'badge_class' => $badge,
            ];
        })->values()->all();
    }
}

