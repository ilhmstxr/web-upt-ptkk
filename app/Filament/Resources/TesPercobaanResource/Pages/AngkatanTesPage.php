<?php

namespace App\Filament\Resources\TesPercobaanResource\Pages;

use App\Models\Angkatan;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AngkatanTesPage extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = 'App\\Filament\\Resources\\TesPercobaanResource';
    protected static ?string $title = 'Data Angkatan';
    protected static string $view = 'filament::pages.base'; // pakai bawaan Filament, tidak custom Blade
    public Pelatihan $pelatihan;

    public function mount($pelatihan): void
    {
        $this->pelatihan = Pelatihan::findOrFail($pelatihan);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Angkatan::query()->where('pelatihan_id', $this->pelatihan->id))
            ->columns([
                Tables\Columns\TextColumn::make('nama_angkatan')
                    ->label('Nama Angkatan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('periode')
                    ->label('Periode')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat_bidang')
                    ->label('Lihat Bidang')
                    ->button()
                    ->icon('heroicon-o-arrow-right')
                    ->url(fn ($record) => route('filament.resources.tes-percobaans.bidang', [
                        'pelatihan' => $this->pelatihan->id,
                        'angkatan' => $record->id,
                    ])),
            ])
            ->emptyStateHeading('Belum ada angkatan untuk pelatihan ini.')
            ->emptyStateDescription('Tambahkan data angkatan di menu manajemen pelatihan.')
            ->paginated(false);
    }
}
