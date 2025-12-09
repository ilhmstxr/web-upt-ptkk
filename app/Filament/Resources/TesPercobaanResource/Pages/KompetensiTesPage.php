<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Models\Pelatihan;
use App\Models\Angkatan;

use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class KompetensiTesPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = 'App\\Filament\\Resources\\TesPercobaanResource';

    public Pelatihan $pelatihan;
    public Angkatan $angkatan;

    public function mount($pelatihan, $angkatan)
    {
        $this->pelatihan = Pelatihan::findOrFail($pelatihan);
        $this->angkatan = Angkatan::findOrFail($angkatan);
    }

    public function getTitle(): string
    {
        return "Kompetensi - {$this->angkatan->nama_angkatan}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Kompetensi::query()->where('angkatan_id', $this->angkatan->id))
            ->columns([
                TextColumn::make('nama_kompetensi')->label('Nama Kompetensi')->sortable()->searchable(),
                TextColumn::make('deskripsi')->label('Deskripsi')->limit(50)->placeholder('-'),
            ])
            ->actions([
                Action::make('lihat_peserta')
                    ->label('Lihat Peserta')
                    ->icon('heroicon-o-users')
                    ->color('primary')
                    ->url(fn($record) => route('filament.resources.tes-percobaans.peserta', [
                        'pelatihan' => $this->pelatihan->id,
                        'angkatan' => $this->angkatan->id,
                        'kompetensi' => $record->id,
                    ])),
            ])
            ->emptyStateHeading('Belum ada kompetensi untuk angkatan ini')
            ->paginated(false);
    }
}
