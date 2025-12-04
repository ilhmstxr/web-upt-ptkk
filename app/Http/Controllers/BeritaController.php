<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita (semua yang is_published = true) dengan pagination.
     * Menghasilkan variabel yang sesuai dengan blade:
     * - $postsPaginator  => LengthAwarePaginator
     * - $featured        => model (atau null)
     * - $others          => Collection (model)
     */
    public function index(Request $request)
    {
        // Ambil semua yang is_published = true (tanpa memeriksa published_at)
        $query = Berita::query()->where('is_published', true);

        // Urutkan: gunakan published_at (jika ada) lalu id
        $postsPaginator = $query->orderByDesc('published_at')
                                ->orderByDesc('id')
                                ->paginate(9)
                                ->withQueryString();

        // Siapkan featured (item pertama dari current page) dan others (sisanya)
        $items = collect($postsPaginator->items()); // array -> collection
        $featured = $items->first() ?: null;
        $others = $items->slice(1);

        return view('pages.berita', compact('postsPaginator', 'featured', 'others'));
    }

    /**
     * Tampilkan detail berita berdasarkan slug.
     * Menampilkan berita yang is_published = true.
     */
    public function show(Request $request, $slug)
    {
        $post = Berita::where('slug', $slug)
                      ->where('is_published', true)
                      ->firstOrFail();

        return view('pages.berita_show', compact('post'));
    }
}
