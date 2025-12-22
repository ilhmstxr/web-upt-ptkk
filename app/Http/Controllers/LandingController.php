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
            $rows = Cache::remember('landing_statistik_v3', 1800, function () {
                $latestPelatihanIds = DB::table('pendaftaran_pelatihan as pp')
                    ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
                    ->whereNotNull('p.tanggal_selesai')
                    ->whereDate('p.tanggal_selesai', '<=', now())
                    ->select('pp.pelatihan_id', DB::raw('MAX(COALESCE(pp.tanggal_pendaftaran, pp.created_at)) as last_daftar'))
                    ->groupBy('pp.pelatihan_id')
                    ->orderByDesc('last_daftar')
                    ->limit(4)
                    ->pluck('pp.pelatihan_id');

                return DB::table('pendaftaran_pelatihan as pp')
                    ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
                    ->leftJoin('kompetensi_pelatihan as kp', 'kp.id', '=', 'pp.kompetensi_pelatihan_id')
                    ->leftJoin('kompetensi as k', 'k.id', '=', DB::raw('COALESCE(pp.kompetensi_id, kp.kompetensi_id)'))
                    ->whereIn('p.id', $latestPelatihanIds)
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
                    ->orderByDesc('p.tanggal_selesai')
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
            });

            $pelatihans = collect($rows)
                ->groupBy('pelatihan_id')
                ->map(function ($items) {
                    $first = $items->first();

                    $kompetensis = $items->map(function ($row) {
                        $pre = (float) ($row->pre_avg ?? 0);
                        $post = (float) ($row->post_avg ?? 0);
                        $prak = (float) ($row->praktek_avg ?? 0);
                        $rata = ($post > 0 || $prak > 0)
                            ? round(($post + $prak) / 2, 2)
                            : 0;

                        return [
                            'nama' => (string) ($row->nama_kompetensi ?? 'Kompetensi'),
                            'pre' => $pre,
                            'post' => $post,
                            'praktek' => $prak,
                            'rata' => $rata,
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
        } catch (Throwable $e) {
            Log::error("Gagal memuat data Statistik untuk Chart: " . $e->getMessage());
        }

        $labels = $pelatihans->map(fn ($p) => (string) ($p['nama'] ?? 'Pelatihan'))->toArray();
        $pre = [];
        $post = [];
        $prak = [];
        $rata = [];

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
        $pelatihans = collect();
        $labels = [];
        $pre = [];
        $post = [];
        $prak = [];
        $rata = [];

        $rows = DB::table('pendaftaran_pelatihan as pp')
            ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
            ->whereNotNull('p.tanggal_selesai')
            ->whereDate('p.tanggal_selesai', '<=', now())
            ->groupBy(
                'p.id',
                'p.nama_pelatihan',
                'p.warna',
                'p.warna_inactive'
            )
            ->orderBy('p.nama_pelatihan')
            ->get([
                'p.id',
                'p.nama_pelatihan',
                'p.warna',
                'p.warna_inactive',
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_pre_test, 0)), 2), 0) as pre_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_post_test, 0)), 2), 0) as post_avg'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(pp.nilai_praktek, 0)), 2), 0) as praktek_avg'),
            ]);

        if ($rows->isNotEmpty()) {
            $pelatihans = $rows;
            $labels = $rows->pluck('nama_pelatihan')->values()->all();
            $pre = $rows->pluck('pre_avg')->map(fn($v) => (float) $v)->values()->all();
            $post = $rows->pluck('post_avg')->map(fn($v) => (float) $v)->values()->all();
            $prak = $rows->pluck('praktek_avg')->map(fn($v) => (float) $v)->values()->all();

            foreach ($rows as $row) {
                $postVal = (float) ($row->post_avg ?? 0);
                $prakVal = (float) ($row->praktek_avg ?? 0);
                $rata[] = ($postVal > 0 || $prakVal > 0)
                    ? round(($postVal + $prakVal) / 2, 2)
                    : 0;
            }
        }

        return view('landing.home', compact('pelatihans','labels','pre','post','prak','rata'));
    }
}

