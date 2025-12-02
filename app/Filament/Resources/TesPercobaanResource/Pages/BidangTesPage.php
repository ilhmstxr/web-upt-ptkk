<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Models\Pelatihan;
use App\Models\Angkatan;
use App\Models\Bidang;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class BidangTesPage extends Page implements Tables\Contracts\HasTable
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
        return "Bidang - {$this->angkatan->nama_angkatan}";
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Bidang::query()->where('angkatan_id', $this->angkatan->id))
            ->columns([
                TextColumn::make('nama_bidang')->label('Nama Bidang')->sortable()->searchable(),
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
                        'bidang' => $record->id,
                    ])),
            ])
            ->emptyStateHeading('Belum ada bidang untuk angkatan ini')
            ->paginated(false);
    }
}
