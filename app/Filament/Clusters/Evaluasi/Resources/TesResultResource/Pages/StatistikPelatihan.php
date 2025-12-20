<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResultResource;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\PelatihanKompetensiChart;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\PelatihanStatsOverview;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\DB;

class StatistikPelatihan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = TesResultResource::class;
    protected static string $view = 'filament.clusters.evaluasi.pages.statistik-pelatihan';

    public Pelatihan $pelatihan;
    public int $pelatihanId;

    public function mount(Pelatihan $pelatihan): void
    {
        $this->pelatihan = $pelatihan;
        $this->pelatihanId = (int) $pelatihan->id;
    }

    public function getTitle(): string
    {
        return 'Statistik Pelatihan';
    }

    public function getSubheading(): ?string
    {
        return $this->pelatihan->nama_pelatihan ?? null;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PelatihanStatsOverview::class,
            PelatihanKompetensiChart::class,
        ];
    }

    protected function getHeaderWidgetsData(): array
    {
        return ['pelatihanId' => $this->pelatihanId];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return \App\Models\PendaftaranPelatihan::query()
                    ->from('pendaftaran_pelatihan as pp')
                    ->join('kompetensi_pelatihan as kp', 'kp.id', '=', 'pp.kompetensi_pelatihan_id')
                    ->join('kompetensi as k', 'k.id', '=', 'kp.kompetensi_id')
                    ->where('pp.pelatihan_id', $this->pelatihanId)
                    ->select([
                        'kp.id as id',
                        'kp.id as kompetensi_pelatihan_id',
                        'k.nama_kompetensi',
                        DB::raw('COUNT(pp.id) as jumlah_peserta'),
                        DB::raw('ROUND(AVG(pp.nilai_pre_test), 2) as pre_avg'),
                        DB::raw('ROUND(AVG(pp.nilai_post_test), 2) as post_avg'),
                        DB::raw('ROUND(AVG(pp.nilai_praktek), 2) as praktek_avg'),
                        DB::raw('ROUND(AVG((pp.nilai_post_test + pp.nilai_praktek) / 2), 2) as rata_avg'),
                    ])
                    ->groupBy('kp.id', 'k.nama_kompetensi')
                    ->orderBy('k.nama_kompetensi');
            })
            ->heading('Ringkasan per Kompetensi')
            ->description('Klik kompetensi untuk melihat nilai peserta.')
            ->columns([
                Tables\Columns\TextColumn::make('nama_kompetensi')
                    ->label('Kompetensi')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('jumlah_peserta')
                    ->label('Jumlah Peserta')
                    ->numeric()
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('pre_avg')
                    ->label('Pre-Test')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('post_avg')
                    ->label('Post-Test')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('praktek_avg')
                    ->label('Praktek')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),
                Tables\Columns\TextColumn::make('rata_avg')
                    ->label('Rata-Rata')
                    ->alignCenter()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Detail Peserta')
                    ->icon('heroicon-o-arrow-right')
                    ->color('primary')
                    ->url(fn ($record) => TesResultResource::getUrl('kompetensi', [
                        'pelatihan' => $this->pelatihanId,
                        'kompetensi' => $record->kompetensi_pelatihan_id,
                    ])),
            ])
            ->striped();
    }
}
