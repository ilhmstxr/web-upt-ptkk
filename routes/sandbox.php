<?php

use App\Models\Kompetensi;
// ... imports

function SurveyHasilKegiatan()
{
    $pelatihanId = 2;

    // Ambil data pelatihan dan semua 'percobaan' yang terkait melalui model 'Tes'
    // Eager load juga data peserta untuk setiap percobaan

    // pelatihan dengan tes apasaja
    $pelatihan = Pelatihan::with('tes')->select('id', 'judul', 'kompetensi_id', 'pelatihan_id')->findOrFail($pelatihanId);

    $tesId = 6;
    $percobaan = Percobaan::where('tes_id', $tesId)
        ->where('skor', '!=', null)
        ->select('id', 'tes_id', 'peserta_id', 'skor')
        ->get();

    $hp = $percobaan->count();
    return response()->json([
        'pelatihan' => $pelatihan,
        // 'total_percobaan' => $hp,
        // 'perkompetensi' => $perkompetensi,
        'percobaan' => $percobaan,
    ]);
}
function countKompetensi()
{
    // $cb1 = Kompetensi::all();
    // $cb2 = Kompetensi::with('kompetensiPelatihan')->get();
    // $cb3 = Kompetensi::with('kompetensiPelatihan.pendaftaranPelatihan')->get();

    // $count = [
    //     $cb1,
    //     $cb2,
    //     $cb3
    // ];

    // return $cb3;
    // return $count;
    // Menghitung 'JUMLAH PESERTA'

}
// function (){

// }