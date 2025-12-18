<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class StatistikPelatihanService
{
    public static function build(int $pelatihanId): array
    {
        $base = DB::table('pendaftaran_pelatihan as pp')
            ->where('pp.pelatihan_id', $pelatihanId)
            ->where('pp.status_pendaftaran', '!=', 'Batal');

        // ======================
        // PRE TEST
        // ======================
        $pre = (clone $base)->selectRaw("
            COUNT(NULLIF(pp.nilai_pre_test, 0)) as count,
            ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2) as avg,
            MAX(NULLIF(pp.nilai_pre_test, 0)) as max,
            MIN(NULLIF(pp.nilai_pre_test, 0)) as min
        ")->first();

        // ======================
        // POST TEST
        // ======================
        $post = (clone $base)->selectRaw("
            COUNT(NULLIF(pp.nilai_post_test, 0)) as count,
            ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2) as avg,
            SUM(CASE WHEN pp.nilai_post_test >= 75 THEN 1 ELSE 0 END) as lulus,
            SUM(CASE WHEN pp.nilai_post_test > 0 AND pp.nilai_post_test < 75 THEN 1 ELSE 0 END) as remedial
        ")->first();

        $totalPeserta = (clone $base)->count();

        return [
            'pretest' => [
                'avg'   => (float) ($pre->avg ?? 0),
                'max'   => (float) ($pre->max ?? 0),
                'min'   => (float) ($pre->min ?? 0),
                'count' => (int) ($pre->count ?? 0),
            ],
            'posttest' => [
                'avg'      => (float) ($post->avg ?? 0),
                'lulus'    => (int) ($post->lulus ?? 0),
                'remedial' => (int) ($post->remedial ?? 0),
                'count'    => (int) ($post->count ?? 0),
            ],
            'monev' => [
                'total_peserta' => (int) $totalPeserta,
            ],
        ];
    }
}
