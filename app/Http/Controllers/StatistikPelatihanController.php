<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Percobaan;
use Illuminate\Support\Facades\DB;

class StatistikPelatihanController extends Controller
{
    /**
     * Endpoint JSON untuk kebutuhan frontend statistik.
     */
    public function data()
    {
        // 1) Ambil rata-rata skor percobaan per pelatihan per tipe tes
        //    pre-test / post-test / survey
        $rows = Percobaan::query()
            ->select([
                'tes.pelatihan_id as pelatihan_id',
                'tes.tipe as tipe',
                DB::raw('AVG(percobaans.skor) as avg_skor'),
            ])
            ->join('tes', 'tes.id', '=', 'percobaans.tes_id')
            ->whereIn('tes.tipe', ['pre-test', 'post-test', 'survey'])
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
