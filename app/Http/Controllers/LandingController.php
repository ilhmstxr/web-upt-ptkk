<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\ProfilUPT;
use App\Models\KepalaUpt;
use App\Models\CeritaKami;
use App\Models\SorotanPelatihan;
use App\Models\Pelatihan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
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
            $cerita = Cache::remember('cerita_kami', 3600, fn () =>
                CeritaKami::where('is_published', true)->latest()->first()
            );
        } catch (Throwable $e) {
            Log::error("Gagal memuat Cerita Kami: " . $e->getMessage());
        }

        // 6) SOROTAN PELATIHAN
        try {
            $sorotans = Cache::remember('sorotan_pelatihan', 3600, function () {
                return SorotanPelatihan::query()
                    ->where('is_published', true)
                    ->whereIn('kelas', ['mtu', 'reguler', 'akselerasi'])
                    ->orderByRaw("FIELD(kelas, 'mtu', 'reguler', 'akselerasi')")
                    ->get();
            });
        } catch (Throwable $e) {
            Log::error("Gagal memuat Sorotan Pelatihan: " . $e->getMessage());
            $sorotans = collect();
        }

        // 7) DATA PELATIHAN (CHART)
        try {
            $pelatihans = Cache::remember('landing_pelatihans', 1800, function () {
                return Pelatihan::where('status', '!=', 'belum dimulai')
                    ->orderBy('tanggal_mulai', 'asc')
                    ->take(4)
                    ->get();
            });
        } catch (Throwable $e) {
            Log::error("Gagal memuat data Pelatihan untuk Chart: " . $e->getMessage());
        }

        $labels = $pelatihans->map(fn($p) => (string) ($p->nama_pelatihan ?? $p->title ?? 'Pelatihan'))->toArray();
        $pre    = $pelatihans->map(fn($p) => (float) ($p->avg_pre_test ?? 0))->toArray();
        $post   = $pelatihans->map(fn($p) => (float) ($p->avg_post_test ?? 0))->toArray();
        $prak   = $pelatihans->map(fn($p) => (float) ($p->avg_praktek ?? 0))->toArray();
        $rata   = $pelatihans->map(fn($p) => (float) ($p->avg_rata ?? round((($p->avg_pre_test ?? 0) + ($p->avg_post_test ?? 0) + ($p->avg_praktek ?? 0)) / 3, 2)))->toArray();

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
}
