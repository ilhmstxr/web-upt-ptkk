<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\KompetensiStatsOverview;
use App\Models\Kompetensi;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ViewKompetensiDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.pages.view-kompetensi-detail';

    // Menyembunyikan halaman ini dari sidebar utama
    protected static bool $shouldRegisterNavigation = false;

    public \App\Models\Kompetensi $record;

    public function getTitle(): string
    {
        return 'Rekap Kompetensi: ' . $this->record->nama_kompetensi;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\KompetensiStatsOverview::make(['kompetensi' => $this->record]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peserta::query()
                    ->where('kompetensi_id', $this->record->id)
                    ->addSelect([
                        'pre_test_score' => PendaftaranPelatihan::select('nilai_pre_test')
                            ->whereColumn('pendaftaran_pelatihan.peserta_id', 'pesertas.id')
                            ->where('pendaftaran_pelatihan.kompetensi_id', $this->record->id)
                            ->orderByDesc('pendaftaran_pelatihan.id')
                            ->limit(1),
                    ])
                    ->addSelect([
                        'post_test_score' => PendaftaranPelatihan::select('nilai_post_test')
                            ->whereColumn('pendaftaran_pelatihan.peserta_id', 'pesertas.id')
                            ->where('pendaftaran_pelatihan.kompetensi_id', $this->record->id)
                            ->orderByDesc('pendaftaran_pelatihan.id')
                            ->limit(1),
                    ])
                    ->addSelect([
                        'practice_score' => PendaftaranPelatihan::select('nilai_praktek')
                            ->whereColumn('pendaftaran_pelatihan.peserta_id', 'pesertas.id')
                            ->where('pendaftaran_pelatihan.kompetensi_id', $this->record->id)
                            ->orderByDesc('pendaftaran_pelatihan.id')
                            ->limit(1),
                    ])
            )
            ->heading('Daftar Peserta')
            ->columns([
                TextColumn::make('nama')->label('Nama Peserta')->searchable(),
                TextColumn::make('asal_lembaga')->label('Asal Lembaga'),
                TextColumn::make('pre_test_score')->label('Pre-Test')->sortable(),
                TextColumn::make('post_test_score')->label('Post-Test')->sortable(),
                TextColumn::make('practice_score')->label('Praktek')->sortable(),
            ]);
    }
}
