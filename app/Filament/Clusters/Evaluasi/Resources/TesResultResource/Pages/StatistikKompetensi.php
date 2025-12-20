<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResultResource;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\KompetensiStatsOverview;
use App\Models\KompetensiPelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Pelatihan;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Tabs\Tab;

class StatistikKompetensi extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = TesResultResource::class;
    protected static string $view = 'filament.clusters.evaluasi.pages.statistik-kompetensi';

    public Pelatihan $pelatihan;
    public KompetensiPelatihan $kompetensi;
    public int $pelatihanId;
    public int $kompetensiPelatihanId;

    public function mount(Pelatihan $pelatihan, KompetensiPelatihan $kompetensi): void
    {
        $this->pelatihan = $pelatihan;
        $this->kompetensi = $kompetensi->loadMissing('kompetensi');
        $this->pelatihanId = (int) $pelatihan->id;
        $this->kompetensiPelatihanId = (int) $kompetensi->id;
    }

    public function getTitle(): string
    {
        return 'Statistik Kompetensi';
    }

    public function getSubheading(): ?string
    {
        $namaKompetensi = $this->kompetensi->kompetensi?->nama_kompetensi;
        return $namaKompetensi
            ? $this->pelatihan->nama_pelatihan . ' - ' . $namaKompetensi
            : $this->pelatihan->nama_pelatihan;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            KompetensiStatsOverview::class,
        ];
    }

    protected function getHeaderWidgetsData(): array
    {
        return [
            'pelatihanId' => $this->pelatihanId,
            'kompetensiPelatihanId' => $this->kompetensiPelatihanId,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return PendaftaranPelatihan::query()
                    ->with(['peserta', 'kompetensiPelatihan.kompetensi'])
                    ->where('pelatihan_id', $this->pelatihanId)
                    ->where('kompetensi_pelatihan_id', $this->kompetensiPelatihanId);
            })
            ->heading('Nilai Peserta')
            ->description('Edit nilai pre-test, post-test, dan praktek.')
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai_pre_test')
                    ->label('Pre-Test')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai_post_test')
                    ->label('Post-Test')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai_praktek')
                    ->label('Praktek')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rata_rata_calc')
                    ->label('Rata-Rata')
                    ->alignCenter()
                    ->getStateUsing(function (PendaftaranPelatihan $record) {
                        $post = (float) ($record->nilai_post_test ?? 0);
                        $prak = (float) ($record->nilai_praktek ?? 0);
                        return number_format(($post + $prak) / 2, 2, ',', '.');
                    }),
                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state) => match (strtolower($state ?? '')) {
                        'diterima' => 'success',
                        'verifikasi' => 'warning',
                        'pending' => 'gray',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'verifikasi' => 'Verifikasi',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit Nilai')
                    ->form([
                        Forms\Components\TextInput::make('nilai_pre_test')
                            ->label('Pre-Test')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        Forms\Components\TextInput::make('nilai_post_test')
                            ->label('Post-Test')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        Forms\Components\TextInput::make('nilai_praktek')
                            ->label('Praktek')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required(),
                        Forms\Components\Placeholder::make('rata_rata')
                            ->label('Rata-Rata (Post + Praktek / 2)')
                            ->content(fn (Get $get) => number_format(
                                ((float) ($get('nilai_post_test') ?? 0) + (float) ($get('nilai_praktek') ?? 0)) / 2,
                                2,
                                ',',
                                '.'
                            )),
                    ])
                    ->mutateFormDataUsing(fn (array $data) => [
                        'nilai_pre_test' => $data['nilai_pre_test'] ?? 0,
                        'nilai_post_test' => $data['nilai_post_test'] ?? 0,
                        'nilai_praktek' => $data['nilai_praktek'] ?? 0,
                    ]),
            ])
            ->striped();
    }

    protected function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'lulus' => Tab::make('Lulus')
                ->modifyQueryUsing(fn ($query) => $query->where('nilai_post_test', '>=', 75)),
            'belum_lengkap' => Tab::make('Belum lengkap')
                ->modifyQueryUsing(fn ($query) => $query
                    ->where(function ($q) {
                        $q->where('nilai_post_test', '<=', 0)
                            ->orWhere('nilai_praktek', '<=', 0);
                    })),
        ];
    }
}
