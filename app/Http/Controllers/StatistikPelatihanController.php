<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Percobaan;
use App\Models\SorotanPelatihan;
use Illuminate\Support\Facades\DB;

class StatistikPelatihanController extends Controller
{
    public function index()
    {
        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
            ->leftJoin('kompetensi_pelatihan as kp', 'kp.id', '=', 'pp.kompetensi_pelatihan_id')
            ->leftJoin('kompetensi as k', 'k.id', '=', DB::raw('COALESCE(pp.kompetensi_id, kp.kompetensi_id)'))
            ->whereNotNull('p.tanggal_selesai')
            ->whereDate('p.tanggal_selesai', '<=', now())
            ->groupBy(
                'p.id',
                'p.nama_pelatihan',
                'p.warna',
                'p.warna_inactive',
                'k.id',
                'k.nama_kompetensi'
            )
            ->orderBy('p.nama_pelatihan')
            ->orderBy('k.nama_kompetensi')
            ->get([
                'p.id as pelatihan_id',
                'p.nama_pelatihan',
                'p.warna',
                'p.warna_inactive',
                'k.nama_kompetensi',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pre_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as post_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0) as praktek_avg'),
            ]);

        $pelatihans = collect($rows)
            ->groupBy('pelatihan_id')
            ->map(function ($items) {
                $first = $items->first();

                $kompetensis = $items->map(function ($row) {
                    $pre = (float) ($row->pre_avg ?? 0);
                    $post = (float) ($row->post_avg ?? 0);
                    $prak = (float) ($row->praktek_avg ?? 0);

                    return [
                        'nama' => (string) ($row->nama_kompetensi ?? 'Kompetensi'),
                        'pre' => $pre,
                        'post' => $post,
                        'praktek' => $prak,
                        'rata' => ($post > 0 || $prak > 0) ? round(($post + $prak) / 2, 2) : 0,
                    ];
                })->values()->all();

                return [
                    'id' => (int) ($first->pelatihan_id ?? 0),
                    'nama' => (string) ($first->nama_pelatihan ?? 'Pelatihan'),
                    'warna' => $first->warna ?: '#1524AF',
                    'warna_inactive' => $first->warna_inactive ?: '#081526',
                    'kompetensis' => $kompetensis,
                ];
            })
            ->values();

        $sorotanByTitle = SorotanPelatihan::query()
            ->where('is_published', true)
            ->orderByDesc('updated_at')
            ->get()
            ->mapWithKeys(function (SorotanPelatihan $row) {
                $key = mb_strtolower(trim((string) $row->title));
                return [$key => $row->photo_urls];
            });

        $fallbackFotos = $sorotanByTitle->first() ?? [];

        foreach ($pelatihans as &$pelatihan) {
            $key = mb_strtolower(trim((string) ($pelatihan['nama'] ?? '')));
            $pelatihan['fotos'] = $sorotanByTitle->get($key, $fallbackFotos);
        }
        unset($pelatihan);

        return view('pages.statistik.statistik-detail', [
            'pelatihans' => $pelatihans,
        ]);
    }

    /**
     * Endpoint JSON untuk kebutuhan frontend statistik.
     */
    public function data()
    {
        // 1) Ambil rata-rata skor percobaan per pelatihan per tipe tes
        //    pre-test / post-test / survei
        $rows = Percobaan::query()
            ->select([
                'tes.pelatihan_id as pelatihan_id',
                'tes.tipe as tipe',
                DB::raw('AVG(percobaans.skor) as avg_skor'),
            ])
            ->join('tes', 'tes.id', '=', 'percobaans.tes_id')
            ->whereIn('tes.tipe', ['pre-test', 'post-test', 'survei'])
            ->groupBy('tes.pelatihan_id', 'tes.tipe')
            ->get();

        // 2) Ambil semua pelatihan (agar label chart selalu lengkap)
        $pelatihans = Pelatihan::orderBy('nama_pelatihan')->get();
        $labels = $pelatihans->pluck('nama_pelatihan')->toArray();

        // 3) Siapkan array datasets chart
        $pre = [];
        $post = [];
        $praktek = [];
        $rata = [];

        foreach ($pelatihans as $p) {
            $preAvg = (float) (
                $rows->firstWhere(fn ($r) =>
                    (int) $r->pelatihan_id === (int) $p->id && $r->tipe === 'pre-test'
                )->avg_skor ?? 0
            );

            $postAvg = (float) (
                $rows->firstWhere(fn ($r) =>
                    (int) $r->pelatihan_id === (int) $p->id && $r->tipe === 'post-test'
                )->avg_skor ?? 0
            );

            /**
             * NOTE PRAKTEK:
             * Kalau kamu belum punya tabel nilai praktek -> biarkan 0
             * Kalau sudah ada, ganti dengan query ke model praktek.
             */
            $praktekAvg = 0;

            $pre[] = round($preAvg, 2);
            $post[] = round($postAvg, 2);
            $praktek[] = round($praktekAvg, 2);

            // Rata-rata dari nilai yang ada (yang > 0)
            $parts = array_filter([$preAvg, $postAvg, $praktekAvg], fn ($v) => $v > 0);
            $rata[] = count($parts)
                ? round(array_sum($parts) / count($parts), 2)
                : 0;
        }

        // 4) Summary global untuk card di atas chart
        $globalPre = round($rows->where('tipe', 'pre-test')->avg('avg_skor') ?? 0, 2);
        $globalPost = round($rows->where('tipe', 'post-test')->avg('avg_skor') ?? 0, 2);
        $globalPraktek = 0;

        // 5) Return JSON final untuk frontend
        return response()->json([
            // list pelatihan kiri
            'pelatihans' => $pelatihans->map(fn ($p) => [
                'id' => $p->id,
                'nama' => $p->nama_pelatihan,
            ]),

            // labels chart
            'labels' => $labels,

            // datasets chart
            'datasets' => [
                'pre' => $pre,
                'post' => $post,
                'praktek' => $praktek,
                'rata' => $rata,
            ],

            // angka untuk summary cards
            'summary' => [
                'pre_avg' => $globalPre,
                'praktek_avg' => $globalPraktek,
                'post_avg' => $globalPost,
            ],
        ]);
    }
}
