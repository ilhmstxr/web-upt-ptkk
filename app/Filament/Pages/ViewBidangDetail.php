<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BidangStatsOverview;
use App\Models\Bidang;
use App\Models\Percobaan;
use App\Models\Peserta;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ViewBidangDetail extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.pages.view-bidang-detail';

    // Menyembunyikan halaman ini dari sidebar utama
    protected static bool $shouldRegisterNavigation = false;

    public Bidang $record;

    public function getTitle(): string
    {
        return 'Rekap Bidang: ' . $this->record->nama_bidang;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BidangStatsOverview::make(['bidang' => $this->record]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Peserta::query()
                    ->where('bidang_id', $this->record->id)
                    ->addSelect(['pre_test_score' => Percobaan::select('nilai')->join('tes', 'tes.id', '=', 'percobaans.tes_id')->whereColumn('tes.peserta_id', 'pesertas.id')->where('tes.jenis_tes', 'pre-test')->latest()->limit(1)])
                    ->addSelect(['post_test_score' => Percobaan::select('nilai')->join('tes', 'tes.id', '=', 'percobaans.tes_id')->whereColumn('tes.peserta_id', 'pesertas.id')->where('tes.jenis_tes', 'post-test')->latest()->limit(1)])
                    ->addSelect(['practice_score' => Percobaan::select('nilai')->join('tes', 'tes.id', '=', 'percobaans.tes_id')->whereColumn('tes.peserta_id', 'pesertas.id')->where('tes.jenis_tes', 'praktek')->latest()->limit(1)])
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
