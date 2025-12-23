<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\BeritaResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\BeritaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder; // 👈 Import Builder

class ListBeritas extends ListRecords
{
    protected static string $resource = BeritaResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.konten-website.components.resource-tabs', [
            'activeTab' => 'berita'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    /**
     * Metode untuk memodifikasi query yang digunakan untuk mengambil data tabel.
     * 🌟 Ini adalah tempat untuk mengatur pengurutan default atau filter global.
     */
    protected function getEloquentQuery(): Builder
    {
        // Panggil query dasar dari resource
        return parent::getEloquentQuery()
            // Contoh: Urutkan data berdasarkan 'published_at' secara menurun (terbaru dulu)
            ->orderByDesc('published_at');
            
            // Atau, contoh lain: Hanya tampilkan berita yang sudah dipublikasikan di halaman ini
            // ->where('is_published', true) 
    }
}