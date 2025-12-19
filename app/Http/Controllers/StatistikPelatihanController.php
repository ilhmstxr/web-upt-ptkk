<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Percobaan;
use App\Models\StatistikPelatihan;
use Illuminate\Support\Facades\DB;

class StatistikPelatihanController extends Controller
{
    public function index()
    {
        // Get all pelatihan with their related kompetensi data
        // Since we don't have a direct relationship, we'll use dummy data for now
        // In a real implementation, you'd fetch from the database
        $pelatihans = [
            [
                "id" => 1,
                "nama" => "Program Akselerasi Kelas MJC Guru Angkatan I Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "FOTOGRAFI (FOTO PRODUK)", "pre" => "61,00", "post" => "91,33", "praktek" => "86,60", "rata" => "84,51"],
                    ["nama" => "DESAIN GRAFIS (LOGO DAN PACKAGING)", "pre" => "44,67", "post" => "70,00", "praktek" => "86,73", "rata" => "80,85"],
                    ["nama" => "CONTENT CREATOR (VIDEOGRAFI)", "pre" => "46,67", "post" => "58,67", "praktek" => "87,87", "rata" => "80,83"],
                    ["nama" => "ANIMASI (MOTION ANIMATION)", "pre" => "56,67", "post" => "68,33", "praktek" => "83,20", "rata" => "79,06"],
                    ["nama" => "WEB DESAIN", "pre" => "40,33", "post" => "49,67", "praktek" => "86,67", "rata" => "78,33"],
                ]
            ],
            [
                "id" => 2,
                "nama" => "Program Mobile Training Unit (MTU) Angkatan II Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "FOTOGRAFI (FOTO PRODUK)", "pre" => "83,83", "post" => "98,50", "praktek" => "89,43", "rata" => "89,78"],
                    ["nama" => "CONTENT CREATOR (VIDEOGRAFI)", "pre" => "87,50", "post" => "91,33", "praktek" => "86,10", "rata" => "86,76"],
                    ["nama" => "PLC", "pre" => "62,67", "post" => "72,00", "praktek" => "87,30", "rata" => "80,12"],
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA I", "pre" => "61,17", "post" => "68,33", "praktek" => "90,87", "rata" => "85,64"],
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA II", "pre" => "59,17", "post" => "83,17", "praktek" => "98,60", "rata" => "93,11"],
                ]
            ],
            [
                "id" => 3,
                "nama" => "MILEA Menuju Generasi Emas 2045 (Kelas MJC) Angkatan II Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "FOTOGRAFI (FOTO PRODUK)", "pre" => "55,67", "post" => "85,67", "praktek" => "89,40", "rata" => "85,65"],
                    ["nama" => "DESAIN GRAFIS (LOGO DAN PACKAGING)", "pre" => "69,33", "post" => "79,00", "praktek" => "91,00", "rata" => "87,63"],
                    ["nama" => "WEB DESAIN", "pre" => "67,33", "post" => "78,67", "praktek" => "88,47", "rata" => "85,37"],
                    ["nama" => "CONTENT CREATOR (VIDEOGRAFI)", "pre" => "58,00", "post" => "75,67", "praktek" => "85,53", "rata" => "81,79"],
                    ["nama" => "ANIMASI (MOTION ANIMATION)", "pre" => "60,67", "post" => "75,00", "praktek" => "86,80", "rata" => "83,01"],
                ]
            ],
            [
                "id" => 4,
                "nama" => "Akselerasi TUK Kelas Keterampilan (Guru SMK/SMA) Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA", "pre" => "63,07", "post" => "84,27", "praktek" => "92,67", "rata" => "88,87"],
                    ["nama" => "TATA KECANTIKAN", "pre" => "60,00", "post" => "86,40", "praktek" => "92,07", "rata" => "83,89"],
                    ["nama" => "TATA BUSANA", "pre" => "62,67", "post" => "94,13", "praktek" => "90,93", "rata" => "88,43"],
                    ["nama" => "TATA BOGA", "pre" => "88,53", "post" => "99,47", "praktek" => "82,73", "rata" => "84,99"],
                ]
            ],
            [
                "id" => 5,
                "nama" => "Sertifikasi Kompetensi Siswa SMK/SMA (KKNI Nasional) Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA", "pre" => "75,67", "post" => "89,67", "praktek" => "94,53", "rata" => "92,16"],
                    ["nama" => "TATA KECANTIKAN", "pre" => "64,33", "post" => "74,33", "praktek" => "92,87", "rata" => "83,89"],
                    ["nama" => "TATA BUSANA", "pre" => "52,00", "post" => "86,33", "praktek" => "95,53", "rata" => "90,26"],
                    ["nama" => "TATA BOGA", "pre" => "39,67", "post" => "52,00", "praktek" => "93,53", "rata" => "83,99"],
                ]
            ],
            [
                "id" => 6,
                "nama" => "MILEA Menuju Generasi Emas 2045 (Kelas MJC) Angkatan I Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "FOTOGRAFI (FOTO PRODUK)", "pre" => "62,00", "post" => "95,67", "praktek" => "91,13", "rata" => "88,67"],
                    ["nama" => "DESAIN GRAFIS (LOGO DAN PACKAGING)", "pre" => "55,33", "post" => "83,67", "praktek" => "89,53", "rata" => "85,53"],
                    ["nama" => "WEB DESAIN", "pre" => "62,00", "post" => "83,33", "praktek" => "88,33", "rata" => "85,20"],
                    ["nama" => "CONTENT CREATOR (VIDEOGRAFI)", "pre" => "65,67", "post" => "88,67", "praktek" => "85,07", "rata" => "83,49"],
                    ["nama" => "ANIMASI (MOTION ANIMATION)", "pre" => "56,33", "post" => "77,00", "praktek" => "85,27", "rata" => "81,55"],
                ]
            ],
            [
                "id" => 7,
                "nama" => "Program Mobile Training Unit (MTU) Angkatan I Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "FOTOGRAFI (FOTO PRODUK)", "pre" => "85,00", "post" => "97,67", "praktek" => "89,47", "rata" => "89,84"],
                    ["nama" => "CONTENT CREATOR (VIDEOGRAFI)", "pre" => "83,33", "post" => "91,33", "praktek" => "84,40", "rata" => "84,99"],
                    ["nama" => "PLC", "pre" => "56,00", "post" => "77,33", "praktek" => "88,20", "rata" => "83,89"],
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA I", "pre" => "67,67", "post" => "96,33", "praktek" => "94,07", "rata" => "91,65"],
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA II", "pre" => "57,50", "post" => "63,17", "praktek" => "86,87", "rata" => "81,56"],
                ]
            ],
            [
                "id" => 8,
                "nama" => "Pengembangan & Peningkatan Kompetensi Vokasi Siswa SMK/SMA (Generasi Emas 2045) Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA", "pre" => "60,33", "post" => "83,33", "praktek" => "92,00", "rata" => "87,97"],
                    ["nama" => "TATA KECANTIKAN", "pre" => "67,33", "post" => "60,33", "praktek" => "90,33", "rata" => "83,89"],
                    ["nama" => "TATA BUSANA", "pre" => "66,33", "post" => "76,33", "praktek" => "87,33", "rata" => "84,13"],
                    ["nama" => "TATA BOGA", "pre" => "52,33", "post" => "76,33", "praktek" => "90,00", "rata" => "84,87"],
                ]
            ],
            [
                "id" => 9,
                "nama" => "MILEA Kelas Keterampilan Angkatan II Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "TEKNIK PENDINGIN DAN TATA UDARA", "pre" => "61,67", "post" => "85,33", "praktek" => "92,07", "rata" => "88,35"],
                    ["nama" => "TATA KECANTIKAN", "pre" => "69,67", "post" => "62,33", "praktek" => "89,40", "rata" => "83,89"],
                    ["nama" => "TATA BUSANA", "pre" => "68,67", "post" => "75,33", "praktek" => "88,40", "rata" => "85,12"],
                    ["nama" => "TATA BOGA", "pre" => "61,00", "post" => "85,67", "praktek" => "73,00", "rata" => "73,07"],
                ]
            ],
            [
                "id" => 10,
                "nama" => "Akselerasi TUK Kelas MJC (Sertifikasi Kompetensi Siswa SMK/SMA) Tahun 2025",
                "warna" => "#1524AF",
                "warna_inactive" => "#081526",
                "kompetensis" => [
                    ["nama" => "FOTOGRAFI (FOTO PRODUK)", "pre" => "88,67", "post" => "95,67", "praktek" => "96,87", "rata" => "95,93"],
                    ["nama" => "DESAIN GRAFIS (LOGO DAN PACKAGING)", "pre" => "70,67", "post" => "80,33", "praktek" => "98,67", "rata" => "94,03"],
                    ["nama" => "ANIMASI (MOTION ANIMATION)", "pre" => "72,33", "post" => "73,67", "praktek" => "90,00", "rata" => "86,60"],
                    ["nama" => "CONTENT CREATOR (VIDEOGRAFI)", "pre" => "60,00", "post" => "60,67", "praktek" => "86,33", "rata" => "81,13"],
                    ["nama" => "WEB DESAIN", "pre" => "44,67", "post" => "41,00", "praktek" => "90,67", "rata" => "81,10"],
                ]
            ]
        ];

        $fotoByPelatihan = StatistikPelatihan::query()
            ->whereNotNull('foto_galeri')
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('pelatihan_id')
            ->map(fn ($rows) => $rows->first()?->foto_galeri ?? []);

        foreach ($pelatihans as &$pelatihan) {
            $pelatihan['fotos'] = $fotoByPelatihan->get($pelatihan['id'], []);
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
