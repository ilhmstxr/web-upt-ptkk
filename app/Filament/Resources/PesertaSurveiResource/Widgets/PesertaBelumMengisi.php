<?php

namespace App\Filament\Resources\PesertaSurveiResource\Widgets;

use App\Filament\Resources\PesertaSurveiResource;
use App\Models\Pelatihan;
use App\Models\Peserta;
use App\Models\PesertaSurvei;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class PesertaBelumMengisi extends BaseWidget
{
    // Atur judul widget
    protected static ?string $heading = 'Daftar Peserta Belum Mengisi Survei';

    protected static bool $shouldRegisterNavigation = false; // Sembunyikan dari menu

    public ?Pelatihan $pelatihan = null;

    // Atur seberapa banyak data yang ditampilkan per halaman
    protected static ?int $defaultSortColumnDirection = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                // 1. Ambil ID pelatihan saat ini
                $pelatihanId = $this->pelatihan?->id;

                // --- Di sinilah kita mengimplementasikan logika murninya ---

                // 2. Ambil SEMUA peserta dari pelatihan ini sebagai Collection
                $semuaPeserta = Peserta::where('pelatihan_id', $pelatihanId)->get();

                // 3. Ambil SEMUA peserta yang sudah mengisi survei sebagai Collection
                $pesertaSudahMengisi = PesertaSurvei::where('pelatihan_id', $pelatihanId)->get();

                // 4. Lakukan filter di level PHP (ini adalah logika intinya)
                // Kita "membuang" (reject) peserta jika ia ditemukan di daftar yang sudah mengisi
                $pesertaBelumMengisi = $semuaPeserta->reject(function ($peserta) use ($pesertaSudahMengisi) {

                    // Cek apakah ada data di $pesertaSudahMengisi yang cocok dengan $peserta saat ini
                    return $pesertaSudahMengisi->contains(function ($survei) use ($peserta) {

                        // Kondisi pencocokan: nama ATAU email sama (case-insensitive)
                        return strtolower($survei->nama) === strtolower($peserta->nama)
                            || strtolower($survei->email) === strtolower($peserta->email);
                    });
                });

                // 5. Ambil hanya ID dari peserta yang belum mengisi
                $idPesertaBelumMengisi = $pesertaBelumMengisi->pluck('id')->all();

                // 6. Kembalikan query builder yang mencari berdasarkan ID yang sudah kita saring
                return Peserta::query()
                    ->whereIn('id', $idPesertaBelumMengisi);
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('bidang.nama_bidang'),
            ]);
    }
}
