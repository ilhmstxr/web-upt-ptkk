<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\SorotanPelatihan;
use Illuminate\Support\Facades\DB;

class StatistikPelatihanController extends Controller
{
    public function index()
    {
        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
            ->join('kompetensi_pelatihan as kp', 'kp.id', '=', 'pp.kompetensi_pelatihan_id')
            ->join('kompetensi as k', 'k.id', '=', 'kp.kompetensi_id')
            ->whereNotNull('p.tanggal_selesai')
            ->whereDate('p.tanggal_selesai', '<=', now())
            ->groupBy(
                'p.id',
                'p.nama_pelatihan',
                'p.warna',
                'p.warna_inactive',
                'kp.id',
                'kp.lokasi',
                'k.nama_kompetensi'
            )
            ->orderBy('p.nama_pelatihan')
            ->orderBy('k.nama_kompetensi')
            ->get([
                'p.id as pelatihan_id',
                'p.nama_pelatihan',
                'p.warna',
                'p.warna_inactive',
                'kp.id as kompetensi_pelatihan_id',
                'kp.lokasi as lokasi',
                'k.nama_kompetensi',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pre_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as post_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0) as praktek_avg'),
            ]);

        $pelatihans = collect($rows)
            ->groupBy('pelatihan_id')
            ->map(function ($items) {
                $first = $items->first();

                $kompetensis = $items->map(function ($row) use ($first) {
                    $pre = (float) ($row->pre_avg ?? 0);
                    $post = (float) ($row->post_avg ?? 0);
                    $prak = (float) ($row->praktek_avg ?? 0);
                    $lokasi = (int) ($first->pelatihan_id ?? 0) === 2
                        ? (string) ($row->lokasi_kompetensi ?? '')
                        : '';

                    return [
                        'nama' => (string) ($row->nama_kompetensi ?? 'Kompetensi'),
                        'lokasi' => $lokasi,
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
        // 1) Ambil semua pelatihan yang selesai
        $pelatihans = Pelatihan::query()
            ->whereNotNull('tanggal_selesai')
            ->whereDate('tanggal_selesai', '<=', now())
            ->orderBy('nama_pelatihan')
            ->get();

        // 2) Ambil rata-rata nilai dari pendaftaran_pelatihan
        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
            ->whereNotNull('p.tanggal_selesai')
            ->whereDate('p.tanggal_selesai', '<=', now())
            ->groupBy('pp.pelatihan_id')
            ->get([
                'pp.pelatihan_id',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pre_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as post_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0) as praktek_avg'),
            ])
            ->keyBy('pelatihan_id');

        // 3) Siapkan array datasets chart
        $labels = $pelatihans->pluck('nama_pelatihan')->toArray();
        $pre = [];
        $post = [];
        $praktek = [];
        $rata = [];

        foreach ($pelatihans as $p) {
            $row = $rows->get($p->id);
            $preAvg = (float) ($row->pre_avg ?? 0);
            $postAvg = (float) ($row->post_avg ?? 0);
            $praktekAvg = (float) ($row->praktek_avg ?? 0);

            $pre[] = round($preAvg, 2);
            $post[] = round($postAvg, 2);
            $praktek[] = round($praktekAvg, 2);

            $rata[] = ($postAvg > 0 || $praktekAvg > 0)
                ? round(($postAvg + $praktekAvg) / 2, 2)
                : 0;
        }

        // 4) Summary global untuk card di atas chart
        $rowValues = $rows->values();
        $globalPre = round($rowValues->avg('pre_avg') ?? 0, 2);
        $globalPost = round($rowValues->avg('post_avg') ?? 0, 2);
        $globalPraktek = round($rowValues->avg('praktek_avg') ?? 0, 2);

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
