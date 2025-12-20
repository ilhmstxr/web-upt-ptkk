<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatistikPelatihanController extends Controller
{
    public function index()
    {
        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
            ->whereNotNull('p.tanggal_selesai')
            ->whereDate('p.tanggal_selesai', '<=', now())
            ->groupBy('p.id', 'p.nama_pelatihan', 'p.warna', 'p.warna_inactive')
            ->orderBy('p.nama_pelatihan')
            ->get([
                'p.id',
                'p.nama_pelatihan as nama',
                'p.warna',
                'p.warna_inactive',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pre_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as post_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0) as praktek_avg'),
            ]);

        if ($rows->isEmpty()) {
            return response()->json([
                'pelatihans' => [],
                'labels' => [],
                'datasets' => ['pre'=>[], 'post'=>[], 'praktek'=>[], 'rata'=>[]],
            ]);
        }

        return response()->json([
            'pelatihans' => $rows->map(fn($r) => [
                'id' => (int) $r->id,
                'nama' => $r->nama,
                'warna' => $r->warna ?? '#1524AF',
                'warna_inactive' => $r->warna_inactive ?? '#000000',
            ])->values(),

            'labels' => $rows->pluck('nama')->values(),

            'datasets' => [
                'pre'     => $rows->pluck('pre_avg')->map(fn($v)=>(float)$v)->values(),
                'post'    => $rows->pluck('post_avg')->map(fn($v)=>(float)$v)->values(),
                'praktek' => $rows->pluck('praktek_avg')->map(fn($v)=>(float)$v)->values(),
                'rata'    => $rows->map(function ($r) {
                    $post = (float) ($r->post_avg ?? 0);
                    $prak = (float) ($r->praktek_avg ?? 0);
                    return ($post > 0 || $prak > 0)
                        ? round(($post + $prak) / 2, 2)
                        : 0;
                })->values(),
            ],
        ]);
    }
}
