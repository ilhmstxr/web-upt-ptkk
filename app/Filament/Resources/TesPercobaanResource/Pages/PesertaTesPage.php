<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Models\Pelatihan;
use App\Models\Angkatan;
use App\Models\Bidang;
use App\Models\Peserta;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PesertaTesPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = 'App\\Filament\\Resources\\TesPercobaanResource';

    public Pelatihan $pelatihan;
    public Angkatan $angkatan;
    public Bidang $bidang;

    public function mount($pelatihan, $angkatan, $bidang)
    {
        $this->pelatihan = Pelatihan::findOrFail($pelatihan);
        $this->angkatan = Angkatan::findOrFail($angkatan);
        $this->bidang = Bidang::findOrFail($bidang);
    }

    public function getTitle(): string
    {
        return "Peserta - {$this->bidang->nama_bidang}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peserta::query()
                    ->where('bidang_id', $this->bidang->id)
                    ->where('angkatan_id', $this->angkatan->id)
            )
            ->columns([
                TextColumn::make('nama')->label('Nama Peserta')->sortable()->searchable(),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('instansi')->label('Instansi')->placeholder('-'),
            ])
            ->emptyStateHeading('Belum ada peserta untuk bidang ini')
            ->paginated(false);
    }
}
