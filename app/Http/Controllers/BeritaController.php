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
        // Ubah ke `true` bila kamu ingin menggunakan published_at sebagai filter default.
        $USE_PUBLISHED_AT_BY_DEFAULT = false;

        $usePublishedAt = $request->query('use_published_at')
            ? (bool) $request->query('use_published_at')
            : $USE_PUBLISHED_AT_BY_DEFAULT;

        $isPreview = (bool) $request->query('preview') && Auth::check();

        // Dasar query: hanya yang di-flag sebagai published
        $query = Berita::query()->where('is_published', true);

        // Jika ingin pakai published_at AND kolom ada
        if ($usePublishedAt && $this->hasColumn('beritas', 'published_at')) {
            $now = Carbon::now(config('app.timezone') ?: config('app.locale'));
            if (! $isPreview) {
                // normal mode: hanya yang published_at null atau <= now
                $query->where(function ($q) use ($now) {
                    $q->whereNull('published_at')
                      ->orWhere('published_at', '<=', $now);
                });
            } else {
                // preview mode: tambahkan nothing (tampilkan juga yg scheduled)
            }
        }

        // Urutkan (published_at may be null) lalu id
        $postsPaginator = $query->orderByDesc('published_at')
                               ->orderByDesc('id')
                               ->paginate(9)
                               ->withQueryString();

        // Ambil items pada halaman sekarang sebagai collection
        $items = collect($postsPaginator->items());
        $featured = $items->first() ?: null;
        $others = $items->slice(1);

        // Logging ringan untuk membantu debugging jika kosong
        if ($postsPaginator->total() === 0) {
            Log::debug('BeritaController@index : tidak menemukan berita terpublikasi.', [
                'usePublishedAt' => $usePublishedAt,
                'isPreview' => $isPreview,
                'db' => \DB::connection()->getDatabaseName(),
            ]);
        }

        return view('pages.berita', [
            'postsPaginator' => $postsPaginator,
            'featured' => $featured,
            'others' => $others,
        ]);
    }

    /**
     * show: detail berdasarkan slug
     */
    public function show(Request $request, $slug)
    {
        $isPreview = (bool) $request->query('preview') && Auth::check();

        $query = Berita::where('slug', $slug)
                       ->where('is_published', true);

        // Jika kamu menggunakan published_at filter, kamu bisa uncomment block berikut
        // dan/atau aktifkan dengan ?use_published_at=1 di request.
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

        return view('pages.berita_show', compact('post'));
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
