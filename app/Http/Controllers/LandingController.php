<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\ProfilUPT;
use App\Models\KepalaUpt;
use App\Models\CeritaKami;
use App\Models\SorotanPelatihan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;


class LandingController extends Controller
{
    public function index()
    {
        // === 🎯 INISIALISASI FALLBACK ===
        $pelatihans = collect();
        $labels = [];
        $pre = [];
        $post = [];
        $prak = [];
        $rata = [];

        $beritas = collect();
        $profil = null;
        $kepala = null;
        $cerita = null;
        $sorotans = collect();
        // =======================================

        // 1) BANNERS
        $featured = Banner::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('updated_at', 'desc')
            ->first();

        $others = Banner::where('is_active', true)
            ->where('is_featured', false)
            ->orderBy('updated_at', 'desc')
            ->get();

        // 2) BERITA
        try {
            $beritas = Cache::remember('landing_beritas', 1800, function () {
                return Berita::where('is_published', true)
                    ->whereNotNull('published_at')
                    ->orderBy('published_at', 'desc')
                    ->limit(6)
                    ->get();
            });

            $beritas = $beritas->map(function (Berita $post) {
                $img = $post->image ?? null;
                $imageUrl = null;

                if ($img) {
                    if (Str::startsWith($img, ['http://', 'https://'])) {
                        $imageUrl = $img;
                    } else {
                        $p = ltrim($img, '/');
                        if (Storage::disk('public')->exists($p)) {
                            $imageUrl = Storage::url($p);
                        } elseif (file_exists(public_path($p))) {
                            $imageUrl = asset($p);
                        } elseif (file_exists(public_path("images/{$p}"))) {
                            $imageUrl = asset("images/{$p}");
                        } else {
                            $basename = basename($p);
                            if (Storage::disk('public')->exists($basename)) {
                                $imageUrl = Storage::url($basename);
                            } elseif (file_exists(public_path("images/{$basename}"))) {
                                $imageUrl = asset("images/{$basename}");
                            }
                        }
                    }
                }

                $post->image_url = $imageUrl;
                return $post;
            });
        } catch (Throwable $e) {
            Log::error("Gagal memuat Berita: " . $e->getMessage());
        }

        // 3) PROFIL UPT
        try {
            $profil = Cache::remember('landing_profil', 1800, fn () => ProfilUPT::first());
        } catch (Throwable $e) {
            Log::error("Gagal memuat Profil UPT: " . $e->getMessage());
        }

        // 4) KEPALA UPT
        try {
            $kepala = Cache::remember('kepala_upt', 3600, fn () => KepalaUpt::latest()->first());
        } catch (Throwable $e) {
            Log::error("Gagal memuat Kepala UPT: " . $e->getMessage());
        }

        // 5) CERITA KAMI (INI YANG DIPAKAI SECTION CERITA KAMI DI LANDING)
        try {
            $cerita = CeritaKami::latest()->first();
            
        } catch (Throwable $e) {
            Log::error("Gagal memuat Cerita Kami: " . $e->getMessage());
            $cerita = null;
        }

        // 6) SOROTAN PELATIHAN
        try {
            // Hapus cache lama dulu biar fresh karena struktur DB berubah
            Cache::forget('sorotan_pelatihan');

            $sorotans = Cache::remember('sorotan_pelatihan', 3600, function () {
                return SorotanPelatihan::query()
                    ->where('is_published', true)
                    ->latest() // Ambil yang terbaru
                    ->get();
            });
        } catch (Throwable $e) {
            Log::error("Gagal memuat Sorotan Pelatihan: " . $e->getMessage());
            $sorotans = collect();
        }

        // 7) DATA STATISTIK (CHART) dari pendaftaran_pelatihan
        try {
            $rows = Cache::remember('landing_statistik', 1800, function () {
                return DB::table('pendaftaran_pelatihan as pp')
                    ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
                    ->whereNotNull('p.tanggal_selesai')
                    ->whereDate('p.tanggal_selesai', '<=', now())
                    ->groupBy('p.id', 'p.nama_pelatihan', 'p.warna', 'p.warna_inactive')
                    ->orderBy('p.nama_pelatihan')
                    ->get([
                        'p.id',
                        'p.nama_pelatihan',
                        'p.warna',
                        'p.warna_inactive',
                        DB::raw('AVG(pp.nilai_pre_test) as pre_avg'),
                        DB::raw('AVG(pp.nilai_post_test) as post_avg'),
                        DB::raw('AVG(pp.nilai_praktek) as praktek_avg'),
                        DB::raw('AVG(pp.rata_rata) as rata_avg'),
                    ]);
            });

            $pelatihans = collect($rows)->map(function ($row) {
                $pre = (float) ($row->pre_avg ?? 0);
                $post = (float) ($row->post_avg ?? 0);
                $prak = (float) ($row->praktek_avg ?? 0);
                $rata = $row->rata_avg !== null
                    ? (float) $row->rata_avg
                    : round(($pre + $post + $prak) / 3, 2);

                return (object) [
                    'id' => $row->id,
                    'nama_pelatihan' => $row->nama_pelatihan,
                    'warna' => $row->warna,
                    'warna_inactive' => $row->warna_inactive,
                    'pre_avg' => $pre,
                    'post_avg' => $post,
                    'praktek_avg' => $prak,
                    'rata_avg' => $rata,
                ];
            });
        } catch (Throwable $e) {
            Log::error("Gagal memuat data Statistik untuk Chart: " . $e->getMessage());
        }

        $labels = $pelatihans->map(fn($p) => (string) ($p->nama_pelatihan ?? 'Pelatihan'))->toArray();
        $pre    = $pelatihans->map(fn($p) => (float) ($p->pre_avg ?? 0))->toArray();
        $post   = $pelatihans->map(fn($p) => (float) ($p->post_avg ?? 0))->toArray();
        $prak   = $pelatihans->map(fn($p) => (float) ($p->praktek_avg ?? 0))->toArray();
        $rata   = $pelatihans->map(fn($p) => (float) ($p->rata_avg ?? 0))->toArray();

        // 🔹 SATU-SATUNYA RETURN
        return view('pages.landing', [
            'featured'   => $featured,
            'others'     => $others,
            'beritas'    => $beritas,
            'profil'     => $profil,
            'kepala'     => $kepala,
            'cerita'     => $cerita,
            'sorotans'   => $sorotans,
            'pelatihans' => $pelatihans,
            'labels'     => $labels,
            'pre'        => $pre,
            'post'       => $post,
            'prak'       => $prak,
            'rata'       => $rata,
        ]);
    }

    // 🔹 METHOD HALAMAN CERITA KAMI
    public function ceritaKami()
    {
        try {
            $kepala = Cache::remember('kepala_upt', 3600, fn () =>
                KepalaUpt::latest()->first()
            );
        } catch (Throwable $e) {
            Log::error("Gagal memuat Kepala UPT: " . $e->getMessage());
            $kepala = null;
        }

        return view('pages.profil.cerita-kami', [
            'kepala' => $kepala,
        ]);
    }

    public function home()
    {
        // ambil batch terbaru dari statistik_pelatihan
        $batch = DB::table('statistik_pelatihan')
            ->orderByDesc('created_at')
            ->value('batch');

        $pelatihans = collect();
        $labels = [];
        $pre = [];
        $post = [];
        $prak = [];
        $rata = [];

        if ($batch) {
            $rows = DB::table('statistik_pelatihan as s')
                ->join('pelatihan as p', 'p.id', '=', 's.pelatihan_id')
                ->where('s.batch', $batch)
                ->orderBy('p.nama_pelatihan')
                ->get([
                    'p.id',
                    'p.nama_pelatihan',
                    'p.warna',
                    'p.warna_inactive',
                    's.pre_avg',
                    's.post_avg',
                    's.praktek_avg',
                    's.rata_avg',
                ]);

            $pelatihans = $rows; // biar blade kamu @forelse($pelatihans as $pel) tetap jalan

            $labels = $rows->pluck('nama_pelatihan')->values()->all();
            $pre    = $rows->pluck('pre_avg')->map(fn($v) => (float) $v)->values()->all();
            $post   = $rows->pluck('post_avg')->map(fn($v) => (float) $v)->values()->all();
            $prak   = $rows->pluck('praktek_avg')->map(fn($v) => (float) $v)->values()->all();
            $rata   = $rows->pluck('rata_avg')->map(fn($v) => (float) $v)->values()->all();
        }

        return view('landing.home', compact('pelatihans','labels','pre','post','prak','rata'));
    }
}
