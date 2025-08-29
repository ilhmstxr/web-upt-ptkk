<?php

namespace App\Filament\Resources\JawabanSurveiResource\Pages;

use App\Filament\Resources\JawabanSurveiResource;
use App\Models\PesertaSurvei;
use Filament\Resources\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;

class DaftarPesertaBelumMengisi extends Page implements HasTable
{
    protected static string $resource = JawabanSurveiResource::class;


    use InteractsWithTable; // <-- 4. Gunakan Trait


    protected static string $view = 'filament.resources.jawaban-survei-resource.pages.daftar-peserta-belum-mengisi';

    // Atur judul yang akan muncul di sub-menu
    protected static ?string $navigationLabel = 'Peserta Belum Mengisi';
    protected ?string $heading = 'Daftar Peserta Belum Mengisi Survei';

    // 5. Tambahkan method table() ini
    public function table(Table $table): Table
    {
        return $table
            ->query(
                PesertaSurvei::query()->whereDoesntHave('percobaans')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->label('Pelatihan'),
            ])
            ->paginated([10, 25, 50]); // Tambahkan paginasi jika perlu
    }
}
