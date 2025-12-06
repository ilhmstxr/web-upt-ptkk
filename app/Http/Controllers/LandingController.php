<?php

namespace App\Http\Controllers\Public;

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
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        // 1) BANNERS (cache 30 menit)
        $banners = Cache::remember('landing_banners', 1800, function () {
            return Banner::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->limit(6)
                ->get();
        });

        // Normalisasi URL image tiap banner -> $banner->image_url
        $banners = $banners->map(function (Banner $b) {
            $img = $b->image; // string dari DB (bisa url, path relatif, atau filename)
            $imageUrl = null;

            if ($img) {
                // jika sudah full URL
                if (Str::startsWith($img, ['http://', 'https://'])) {
                    $imageUrl = $img;
                } else {
                    $normalized = ltrim($img, '/');

                    // coba disk public (storage/app/public)
                    if (Storage::disk('public')->exists($normalized)) {
                        $imageUrl = Storage::url($normalized); // /storage/...
                    }
                    // cek public/<normalized>
                    elseif (file_exists(public_path($normalized))) {
                        $imageUrl = asset($normalized);
                    }
                    // cek public/images/<normalized>
                    elseif (file_exists(public_path("images/{$normalized}"))) {
                        $imageUrl = asset("images/{$normalized}");
                    }
                    // fallback: cek basename
                    else {
                        $basename = basename($normalized);
                        if (Storage::disk('public')->exists($basename)) {
                            $imageUrl = Storage::url($basename);
                        } elseif (file_exists(public_path("images/{$basename}"))) {
                            $imageUrl = asset("images/{$basename}");
                        } elseif (file_exists(public_path("images/banners/{$basename}"))) {
                            $imageUrl = asset("images/banners/{$basename}");
                        } else {
                            $imageUrl = null;
                        }
                    }
                }
            }

            $b->image_url = $imageUrl;
            return $b;
        });

        // 2) BERITA (cache 30 menit)
        $beritas = Cache::remember('landing_beritas', 1800, function () {
            return Berita::where('is_published', true)
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();
        });

        // normalisasi image untuk berita (opsional)
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
                        } else {
                            $imageUrl = null;
                        }
                    }
                }
            }
            $post->image_url = $imageUrl;
            return $post;
        });

        // 3) Profil UPT (cache 30 menit)
        $profil = Cache::remember('landing_profil', 1800, function () {
            return ProfilUPT::first();
        });

        // 4) Kepala UPT (singleton) (cache 1 jam)
        $kepala = Cache::remember('kepala_upt', 3600, function () {
            return KepalaUpt::latest()->first();
        });

        // 5) Cerita Kami (latest published) (cache 1 jam)
        $cerita = Cache::remember('cerita_kami', 3600, function () {
            return CeritaKami::where('is_published', true)->latest()->first();
        });

        // 6) Sorotan Pelatihan (published + fotos) (cache 1 jam)
        $sorotans = Cache::remember('sorotan_pelatihan', 3600, function () {
            return SorotanPelatihan::where('is_published', true)->with('fotos')->get();
        });

        // Normalisasi URL untuk masing-masing foto sorotan
        $sorotans = $sorotans->map(function ($s) {
            $s->fotos = $s->fotos->map(function ($foto) {
                // path atribut umum: 'path' atau 'file' — coba beberapa kemungkinan
                $p = $foto->path ?? $foto->file ?? $foto->filepath ?? null;
                $url = null;
                if ($p) {
                    $p = ltrim($p, '/');
                    if (Storage::disk('public')->exists($p)) {
                        $url = Storage::url($p);
                    } elseif (file_exists(public_path($p))) {
                        $url = asset($p);
                    } elseif (file_exists(public_path("images/{$p}"))) {
                        $url = asset("images/{$p}");
                    } else {
                        $basename = basename($p);
                        if (Storage::disk('public')->exists($basename)) {
                            $url = Storage::url($basename);
                        } elseif (file_exists(public_path("images/{$basename}"))) {
                            $url = asset("images/{$basename}");
                        } else {
                            // fallback null — view harus handle fallback image
                            $url = null;
                        }
                    }
                }
                $foto->url = $url;
                return $foto;
            });
            return $s;
        });

        // --- Tambahan: PELATIHAN (dynamic statistik untuk section)
        // Ambil (misal) 4 pelatihan terbaru / terjadwal
        $pelatihans = Cache::remember('landing_pelatihans', 1800, function () {
            // Pastikan model Pelatihan dan kolomnya sesuai; jika tidak, ubah query di sini
            return Pelatihan::where('is_published', true)
                ->orderBy('tanggal_mulai', 'asc')
                ->take(4)
                ->get();
        });

        // siapkan data chart dari model (gunakan fallback 0 jika field tidak ada)
        $labels = $pelatihans->map(fn($p) => (string) ($p->nama_pelatihan ?? $p->title ?? 'Pelatihan'))->toArray();
        $pre    = $pelatihans->map(fn($p) => (float) ($p->avg_pre_test ?? 0))->toArray();
        $post   = $pelatihans->map(fn($p) => (float) ($p->avg_post_test ?? 0))->toArray();
        $prak   = $pelatihans->map(fn($p) => (float) ($p->avg_praktek ?? 0))->toArray();
        $rata   = $pelatihans->map(fn($p) => (float) ($p->avg_rata ?? round((($p->avg_pre_test ?? 0) + ($p->avg_post_test ?? 0) + ($p->avg_praktek ?? 0)) / 3, 2)))->toArray();

        // KIRIM SEMUA VARIABEL KE VIEW
        return view('pages.landing', compact(
            'banners',
            'beritas',
            'profil',
            'kepala',
            'cerita',
            'sorotans',
            'pelatihans',
            'labels',
            'pre',
            'post',
            'prak',
            'rata'
        ));
    }
}
