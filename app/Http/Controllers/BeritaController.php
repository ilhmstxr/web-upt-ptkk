<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class BeritaController extends Controller
{
    /**
     * index: kirim $postsPaginator, $featured, $others (sesuai blade)
     *
     * Behavior default:
     * - Menampilkan semua record yang is_published = true (tanpa memeriksa published_at).
     *
     * Opsional:
     * - Jika ingin aktifkan filter published_at (hanya tampil jika published_at IS NULL OR <= now),
     *   tambahkan query param ?use_published_at=1 atau ubah $USE_PUBLISHED_AT_BY_DEFAULT ke true.
     *
     * Preview:
     * - Jika ?preview=1 dan user login -> akan mengabaikan pengecekan waktu published_at (jika dipakai).
     */
    public function index(Request $request)
    {
        $USE_PUBLISHED_AT_BY_DEFAULT = false;
        $usePublishedAt = $request->query('use_published_at') ? (bool) $request->query('use_published_at') : $USE_PUBLISHED_AT_BY_DEFAULT;
        $isPreview = (bool) $request->query('preview') && Auth::check();

        // 1. AMBIL "SANG RAJA" (1 Berita Paling Baru utk Banner Atas)
        // Query dasar
        $featuredQuery = Berita::query()->where('is_published', true);
        
        // Filter Tanggal (jika aktif)
        if ($usePublishedAt && $this->hasColumn('beritas', 'published_at')) {
            $now = Carbon::now(config('app.timezone') ?: config('app.locale'));
            if (! $isPreview) {
                $featuredQuery->where(function ($q) use ($now) {
                    $q->whereNull('published_at')->orWhere('published_at', '<=', $now);
                });
            }
        }
        
        // Ambil 1 saja yang paling pucuk
        $featured = $featuredQuery->orderByDesc('published_at')
                                  ->orderByDesc('id')
                                  ->first();

        // 2. AMBIL "RAKYATNYA" (Berita sisanya untuk Grid Bawah)
        $query = Berita::query()->where('is_published', true);

        // PENTING: Jangan tampilkan lagi berita yang sudah jadi Banner ($featured) di grid bawah
        if ($featured) {
            $query->where('id', '!=', $featured->id);
        }

        // Filter Tanggal (Sama seperti atas)
        if ($usePublishedAt && $this->hasColumn('beritas', 'published_at')) {
            $now = Carbon::now(config('app.timezone') ?: config('app.locale'));
            if (! $isPreview) {
                $query->where(function ($q) use ($now) {
                    $q->whereNull('published_at')->orWhere('published_at', '<=', $now);
                });
            }
        }

        // Pagination untuk Grid Bawah
        // Gunakan angka 9 (3 baris x 3 kolom) agar rapi
        $postsPaginator = $query->orderByDesc('published_at')
                                ->orderByDesc('id')
                                ->paginate(9) 
                                ->withQueryString();

        // 3. Kirim ke View
        // $others kita isi dengan items dari paginator saja
        return view('pages.berita', [
            'postsPaginator' => $postsPaginator, // Ini untuk pagination link
            'featured'       => $featured,       // Ini yang SELALU TERBARU (Banner)
            'others'         => $postsPaginator, // Ini untuk lopping grid (Isinya berubah sesuai page)
        ]);
    }

    /**
     * show: detail berdasarkan slug
     */
    public function show(Request $request, $slug)
{
    $isPreview = (bool) $request->query('preview') && Auth::check();

    // --- Ambil berita utama ---
    $query = Berita::where('slug', $slug)
                   ->where('is_published', true);

    if ($request->query('use_published_at') && $this->hasColumn('beritas', 'published_at')) {
        $now = Carbon::now(config('app.timezone') ?: config('app.locale'));
        if (! $isPreview) {
            $query->where(function ($q) use ($now) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', $now);
            });
        }
    }

    $post = $query->firstOrFail();

    // --- Ambil 3 berita lain (sidebar) ---
    $relatedQuery = Berita::where('is_published', true)
        ->where('id', '!=', $post->id);

    if ($request->query('use_published_at') && $this->hasColumn('beritas', 'published_at')) {
        $now = Carbon::now(config('app.timezone') ?: config('app.locale'));
        if (! $isPreview) {
            $relatedQuery->where(function ($q) use ($now) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', $now);
            });
        }
    }

    $relatedPosts = $relatedQuery
        ->orderByDesc('published_at')
        ->orderByDesc('id')
        ->take(3)
        ->get();

    return view('pages.berita_show', [
        'post'         => $post,
        'relatedPosts' => $relatedPosts,
    ]);
}

    /**
     * Utility: periksa apakah table punya kolom tertentu (aman di berbagai env)
     */
    protected function hasColumn(string $table, string $column): bool
    {
        try {
            return Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            Log::warning("Schema::hasColumn error checking {$table}.{$column}: " . $e->getMessage());
            return false;
        }
    }
}
