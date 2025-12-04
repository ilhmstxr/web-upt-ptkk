<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita (published) dengan pagination.
     */
    public function index(Request $request)
    {
        // Ambil only published. Jika ingin testing tanpa filter published_at,
        // ganti query sesuai komentar di bawah.
        $query = Berita::query()
            ->where('is_published', true);

        // Optional: pastikan published_at sudah <= now() jika kolom dipakai.
        if (schema_has_column('beritas', 'published_at')) {
            $query->where(function ($q) {
                $q->whereNull('published_at')
                  ->orWhere('published_at', '<=', now());
            });
        }

        $posts = $query->orderByDesc('published_at')
                       ->orderByDesc('id')
                       ->paginate(9)
                       ->withQueryString();

        // Featured = first item (atau null)
        $featured = $posts->count() ? $posts->items()[0] : null;

        return view('pages.berita', compact('posts', 'featured'));
    }

    /**
     * Tampilkan single berita berdasarkan slug.
     */
    public function show($slug)
    {
        $post = Berita::where('slug', $slug)
                      ->where('is_published', true)
                      ->when(schema_has_column('beritas', 'published_at'), function ($q) {
                          $q->where(function ($q2) {
                              $q2->whereNull('published_at')
                                 ->orWhere('published_at', '<=', now());
                          });
                      })
                      ->firstOrFail();

        return view('pages.berita_show', compact('post'));
    }
}

/**
 * Helper kecil: periksa ada kolom di table (agar tidak error di environment dev).
 * NOTE: fungsi global ini diletakkan di file controller untuk safety; jika kamu
 * sudah punya helper lain, silakan hapus bagian ini dan panggil helper yang ada.
 */
if (!function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
