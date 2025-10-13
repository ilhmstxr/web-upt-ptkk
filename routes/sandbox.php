<?php

use App\Models\JawabanUser;
use App\Models\OpsiJawaban;
use App\Models\Pelatihan;
use App\Models\Percobaan;
use App\Models\Pertanyaan;
use App\Models\Peserta;
use App\Models\Tes;
use Illuminate\Support\Facades\DB;

function chartSurveyMonev()
{
    // return view('filament.resources.jawaban-surveis.pages.report-jawaban-survei');
    // $pelatihan = App\Models\Pelatihan::findOrFail(1);
    // $pelatihanIds = \App\Models\Pelatihan::with([
    //     'tes' => fn($q) => $q->where('tipe', 'survei')
    //         ->select('id', 'pelatihan_id')
    //         ->with([
    //             'pertanyaan' => fn($qp) => $qp->where('tipe_jawaban', 'skala_likert')
    //         ])
    // ])->get();


    // $questions = Pertanyaan::whereIn('id', $pertanyaanIds)->where('tipe_jawaban', 'skala_likert')
    //     ->orderBy('tes_id')->orderBy('nomor')
    //     ->get(['id', 'nomor', 'teks_pertanyaan']);
    // return $questions;
    /**
     * End-to-end pipeline:
     *  - Hitung skala Likert per jawaban {percobaan_id, pertanyaan_id, opsi_jawaban_id, skala}
     *  - Kelompokkan jawaban ke kategori kustom berbasis urutan pertanyaan & penanda 'pesan dan kesan'
     *  - Akumulasi nilai skala (1..4) global dan per kategori
     */
    // =============================
    // 0) Parameter & kategori kustom
    // =============================
    $arrayCustom = [
        'Pendapat Tentang Penyelenggaran Pelatihan',
        'Persepsi Terhadap Program Pelatihan',
        'Penilaian Terhadap Instruktur',
    ];

    // =============================
    // 1) Ambil semua pertanyaan Likert
    // =============================
    $pertanyaanLikert = Pertanyaan::where('tipe_jawaban', 'skala_likert')
        ->orderBy('id')
        ->get(['id', 'tes_id', 'teks_pertanyaan', 'tipe_jawaban', 'nomor']);

    $pertanyaanIds = $pertanyaanLikert->pluck('id');

    // =============================
    // 2) Pivot: pertanyaan -> template_pertanyaan
    // =============================
    $pivot = DB::table('pivot_jawaban')
        ->whereIn('pertanyaan_id', $pertanyaanIds)
        ->pluck('template_pertanyaan_id', 'pertanyaan_id');

    // =============================
    // 3) Kumpulan opsi untuk pertanyaan + template terkait (sekali query)
    // =============================
    $opsi = OpsiJawaban::whereIn(
        'pertanyaan_id',
        collect($pertanyaanIds)->merge($pivot->values())->unique()->all()
    )
        ->orderBy('id') // ganti ke kolom 'urutan' jika tersedia
        ->get(['id', 'pertanyaan_id', 'teks_opsi']);

    // 3a) Peta {pertanyaan_id => [opsi_id => skala]}
    $opsiIdToSkala = $opsi->groupBy('pertanyaan_id')->map(function ($rows) {
        $map = [];
        foreach ($rows->pluck('id')->values() as $i => $id) {
            $map[$id] = $i + 1; // 1..N
        }
        return $map; // contoh: [344=>4,343=>3,342=>2,341=>1]
    });

    // 3b) Peta {pertanyaan_id => [teks_opsi => opsi_id]}
    $opsiTextToId = $opsi->groupBy('pertanyaan_id')
        ->map(fn($rows) => $rows->pluck('id', 'teks_opsi')->mapWithKeys(
            fn($id, $teks) => [trim($teks) => $id]
        ));

    // =============================
    // 4) Jawaban user untuk pertanyaan likert
    // =============================
    $jawaban = JawabanUser::with('pertanyaan')
        ->whereIn('pertanyaan_id', $pertanyaanIds)
        ->get(['percobaan_id', 'pertanyaan_id', 'opsi_jawaban_id', 'jawaban_teks']);

    // =============================
    // 5) Hitung skala per jawaban (fallback ke template jika perlu)
    // =============================
    $hasilFlat = $jawaban->map(function ($j) use ($pivot, $opsiIdToSkala, $opsiTextToId) {
        $pid = (int) $j->pertanyaan_id;

        // Sumber skala: pakai opsi milik pertanyaan; jika kosong, pakai template
        $source = !empty($opsiIdToSkala->get($pid)) ? $pid : ($pivot->get($pid) ?? $pid);

        // Pastikan punya opsi_jawaban_id; jika null, cocokkan dari jawaban_teks
        $opsiId = $j->opsi_jawaban_id;
        if (!$opsiId && $j->jawaban_teks) {
            $opsiId = $opsiTextToId->get($source, collect())->get(trim($j->jawaban_teks));
        }

        // Hitung skala (indeks 1..N pada sumber)
        $skalaMap = $opsiIdToSkala->get($source, []);
        $skala = $opsiId ? ($skalaMap[$opsiId] ?? null) : null;

        return [
            'percobaan_id'    => (int) $j->percobaan_id,
            'pertanyaan_id'   => $pid,
            'opsi_jawaban_id' => $opsiId ? (int) $opsiId : null,
            'skala'           => $skala ? (int) $skala : null,
        ];
    })->values();

    // =============================
    // 6) Mapping pertanyaan -> kategori kustom
    //    Logika: urut per tes & nomor; ketika menemukan pertanyaan `teks_bebas`
    //    yang diawali 'pesan dan kesan', tutup grup dan labeli sesuai $arrayCustom
    // =============================
    $tesIds = $pertanyaanLikert->pluck('tes_id')->filter()->unique()->values();

    $semuaPertanyaanDalamTes = Pertanyaan::whereIn('tes_id', $tesIds)
        ->orderBy('tes_id')
        ->orderBy('nomor')
        ->get(['id', 'tes_id', 'tipe_jawaban', 'teks_pertanyaan', 'nomor']);

    $pertanyaanToKategori = [];

    foreach ($semuaPertanyaanDalamTes->groupBy('tes_id') as $tesId => $questions) {
        $groupKey = 1;        // dimulai 1
        $tempGroup = collect();

        foreach ($questions as $q) {
            $tempGroup->push($q);

            $isBoundary = $q->tipe_jawaban === 'teks_bebas'
                && str_starts_with(strtolower(trim($q->teks_pertanyaan)), 'pesan dan kesan');

            if ($isBoundary) {
                $categoryIndex = $groupKey - 1;
                $category = $arrayCustom[$categoryIndex] ?? ("Kategori " . $groupKey);

                // Map hanya pertanyaan Likert di dalam grup ke kategori
                foreach ($tempGroup as $item) {
                    if ($item->tipe_jawaban === 'skala_likert') {
                        $pertanyaanToKategori[$item->id] = $category;
                    }
                }
                $tempGroup = collect();
                $groupKey++;
            }
        }

        // Jika masih ada sisa grup tanpa boundary di akhir
        if ($tempGroup->isNotEmpty()) {
            $categoryIndex = $groupKey - 1;
            $category = $arrayCustom[$categoryIndex] ?? ("Kategori " . $groupKey);
            foreach ($tempGroup as $item) {
                if ($item->tipe_jawaban === 'skala_likert') {
                    $pertanyaanToKategori[$item->id] = $category;
                }
            }
        }
    }

    // =============================
    // 7) Output 2: dikelompokkan per kategori kustom
    // =============================
    $perKategori = $hasilFlat->groupBy(function ($row) use ($pertanyaanToKategori) {
        return $pertanyaanToKategori[$row['pertanyaan_id']] ?? 'Tanpa Kategori';
    })->map(function ($rows) {
        return $rows->values();
    });

    // =============================
    // 8) Output 3: akumulatif skala 1..4 (global & per kategori)
    // =============================
    $initCounter = fn() => [1 => 0, 2 => 0, 3 => 0, 4 => 0];

    // Global
    $akumulatifGlobal = $initCounter();
    foreach ($hasilFlat as $row) {
        $s = $row['skala'] ?? null;
        if ($s && isset($akumulatifGlobal[$s])) {
            $akumulatifGlobal[$s]++;
        }
    }

    // Per kategori
    $akumulatifPerKategori = [];
    foreach ($perKategori as $kategori => $rows) {
        $counter = $initCounter();
        foreach ($rows as $row) {
            $s = $row['skala'] ?? null;
            if ($s && isset($counter[$s])) {
                $counter[$s]++;
            }
        }
        $akumulatifPerKategori[$kategori] = $counter;
    }

    // =============================
    // Return semua keluaran
    // =============================
    return response()->json([
        // 'pertanyaan' => $pertanyaan,
        'output_1_flat'       => $hasilFlat,                  // [{percobaan_id, pertanyaan_id, opsi_jawaban_id, skala}]
        'output_2_perKategori' => $perKategori,                // {kategori: [...rows...]}
        'output_3_akumulatif' => [
            'global'    => $akumulatifGlobal,                 // {1: n, 2: n, 3: n, 4: n}
            'perKategori' => $akumulatifPerKategori,           // {kategori: {1:n,2:n,3:n,4:n}}
        ],
    ]);
}
function SurveyHasilKegiatan()
{
    $pelatihanId = 2;

    // Ambil data pelatihan dan semua 'percobaan' yang terkait melalui model 'Tes'
    // Eager load juga data peserta untuk setiap percobaan
    
    // pelatihan dengan tes apasaja
    $pelatihan = Pelatihan::with('tes')->select('id', 'judul','bidang_id','pelatihan_id')->findOrFail($pelatihanId);

    $tesId = 6;
    $percobaan = Percobaan::where('tes_id', $tesId)
        ->where('skor', '!=', null)
        ->select('id', 'tes_id', 'peserta_id', 'skor')
        ->get();

    $hp = $percobaan->count();
    return response()->json([
        'pelatihan' => $pelatihan,
        // 'total_percobaan' => $hp,
        // 'perbidang' => $perbidang,
        'percobaan' => $percobaan,
    ]);
}
// function (){

// }