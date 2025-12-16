<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Models\Pelatihan;
use App\Models\Angkatan;
use App\Models\Kompetensi;
use App\Models\Peserta;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PesertaTesPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = 'App\\Filament\\Resources\\TesPercobaanResource';
    protected static string $view = 'filament-panels::pages.page';

    public Pelatihan $pelatihan;
    public Angkatan $angkatan;
    public Kompetensi $kompetensi;

    public function mount($pelatihan, $angkatan, $kompetensi)
    {
        $this->pelatihan = Pelatihan::findOrFail($pelatihan);
        $this->angkatan = Angkatan::findOrFail($angkatan);
        $this->kompetensi = Kompetensi::findOrFail($kompetensi);
    }

    public function getTitle(): string
    {
        return "Peserta - {$this->kompetensi->nama_kompetensi}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peserta::query()
                    ->where('kompetensi_id', $this->kompetensi->id)
                    ->where('angkatan_id', $this->angkatan->id)
            )
            ->columns([
                TextColumn::make('nama')->label('Nama Peserta')->sortable()->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('instansi')->label('Instansi')->placeholder('-'),
            ])
            ->emptyStateHeading('Belum ada peserta untuk kompetensi ini')
            ->paginated(false);
    }
}
