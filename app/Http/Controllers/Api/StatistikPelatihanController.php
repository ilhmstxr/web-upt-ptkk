<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatistikPelatihanController extends Controller
{
    public function index()
    {
        // ambil batch terbaru
        $batch = DB::table('statistik_pelatihan')
            ->orderByDesc('created_at')
            ->value('batch');

        if (!$batch) {
            return response()->json([
                'pelatihans' => [],
                'labels' => [],
                'datasets' => ['pre'=>[], 'post'=>[], 'praktek'=>[], 'rata'=>[]],
            ]);
        }

        $rows = DB::table('statistik_pelatihan as s')
            ->join('pelatihan as p', 'p.id', '=', 's.pelatihan_id')
            ->where('s.batch', $batch)
            ->orderBy('p.nama_pelatihan')
            ->get([
                'p.id',
                'p.nama_pelatihan as nama',
                'p.warna',
                'p.warna_inactive',
                's.pre_avg',
                's.post_avg',
                's.praktek_avg',
                's.rata_avg',
            ]);

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
                'rata'    => $rows->pluck('rata_avg')->map(fn($v)=>(float)$v)->values(),
            ],
        ]);
    }
}
