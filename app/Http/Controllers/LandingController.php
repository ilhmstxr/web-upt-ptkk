<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\ProfilUPT;
use App\Models\Bidang; // ✅ TAMBAH INI
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil banner aktif, cache 30 menit
        $banners = Cache::remember('landing_banners', 1800, function () {
            return Banner::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->limit(6)
                ->get();
        });

        // Normalisasi URL gambar tiap banner supaya view langsung dapat src yang valid
        $banners = $banners->map(function (Banner $b) {
            $img = $b->image; // apa yang tersimpan di DB

            // default null (atau bisa diisi 'images/placeholder.png' jika mau)
            $imageUrl = null;

            if ($img) {
                // jika sudah full URL
                if (Str::startsWith($img, ['http://', 'https://'])) {
                    $imageUrl = $img;
                } else {
                    // normalization: hapus leading slash jika ada
                    $normalized = ltrim($img, '/');

                    // cek storage/app/public/<normalized>
                    if (Storage::exists("public/{$normalized}")) {
                        // Storage::url -> /storage/...
                        $imageUrl = Storage::url($normalized);
                    }
                    // cek public/<normalized>
                    elseif (file_exists(public_path($normalized))) {
                        $imageUrl = asset($normalized);
                    }
                    // cek public/images/<normalized>
                    elseif (file_exists(public_path("images/{$normalized}"))) {
                        $imageUrl = asset("images/{$normalized}");
                    }
                    // coba juga basename (jika DB hanya menyimpan 'foo.jpg')
                    else {
                        $basename = basename($normalized);
                        if (Storage::exists("public/{$basename}")) {
                            $imageUrl = Storage::url($basename);
                        } elseif (file_exists(public_path("images/{$basename}"))) {
                            $imageUrl = asset("images/{$basename}");
                        } else {
                            // optional: coba di folder banners di public/images/banners
                            if (file_exists(public_path("images/banners/{$basename}"))) {
                                $imageUrl = asset("images/banners/{$basename}");
                            }
                        }
                    }
                }
            }

            // set atribut tambahan (tidak menyimpan ke DB)
            $b->image_url = $imageUrl;

            return $b;
        });

        // Ambil berita terbaru
        $beritas = Cache::remember('landing_beritas', 1800, function () {
            return Berita::where('is_published', true)
                ->orderBy('published_at', 'desc')
                ->limit(6)
                ->get();
        });

        // Ambil profil UPT (1 baris)
        $profil = Cache::remember('landing_profil', 1800, function () {
            return ProfilUPT::first();
        });

        return view('pages.landing', compact('banners', 'beritas', 'profil'));
    }

  // ✅ HALAMAN KOMPETENSI PELATIHAN
public function bidangPelatihan(Request $request)
{
    $activeTab = $request->query('tab', 'keterampilan');

    // kelas_keterampilan = 1 → Kelas Keterampilan & Teknik
    // kelas_keterampilan = 0 → Milenial Job Center (MJC)
    $keterampilan = Bidang::where('kelas_keterampilan', 1)
        ->orderBy('nama_bidang')
        ->get();

    $mjc = Bidang::where('kelas_keterampilan', 0)
        ->orderBy('nama_bidang')
        ->get();

    return view('pages.profil.kompetensi-pelatihan', [
        'keterampilan' => $keterampilan,
        'mjc'          => $mjc,
        'activeTab'    => $activeTab,
    ]);
}
}
