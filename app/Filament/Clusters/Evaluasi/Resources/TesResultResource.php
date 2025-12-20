<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Pages;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\GlobalPelatihanChart;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\GlobalStatsOverview;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class TesResultResource extends Resource
{
    protected static ?string $model = \App\Models\PendaftaranPelatihan::class;

    protected static ?string $cluster = Evaluasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Statistik';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Pelatihan')
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
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('post_avg')
                    ->label('Post-Test')
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('praktek_avg')
                    ->label('Praktek')
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),

                Tables\Columns\TextColumn::make('rata_avg')
                    ->label('Rata-Rata')
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 2, ',', '.')),
            ])
            ->query(function () {
                return \App\Models\PendaftaranPelatihan::query()
                    ->from('pendaftaran_pelatihan as pp')
                    ->join('pelatihan as p', 'p.id', '=', 'pp.pelatihan_id')
                    ->select([
                        'p.id as id',
                        'p.id as pelatihan_id',
                        'p.nama_pelatihan',
                        DB::raw('COUNT(pp.id) as jumlah_peserta'),
                        DB::raw('ROUND(AVG(pp.nilai_pre_test), 2) as pre_avg'),
                        DB::raw('ROUND(AVG(pp.nilai_post_test), 2) as post_avg'),
                        DB::raw('ROUND(AVG(pp.nilai_praktek), 2) as praktek_avg'),
                        DB::raw('ROUND(AVG((pp.nilai_post_test + pp.nilai_praktek) / 2), 2) as rata_avg'),
                    ])
                    ->groupBy('p.id', 'p.nama_pelatihan')
                    ->orderBy('p.nama_pelatihan');
            })
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-arrow-right')
                    ->color('primary')
                    ->url(fn ($record) => self::getUrl('pelatihan', ['pelatihan' => $record->pelatihan_id])),
            ])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


    public static function getWidgets(): array
    {
        return [
            GlobalStatsOverview::class,
            GlobalPelatihanChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTesResults::route('/'),
            'pelatihan' => Pages\StatistikPelatihan::route('/pelatihan/{pelatihan}'),
            'kompetensi' => Pages\StatistikKompetensi::route('/pelatihan/{pelatihan}/kompetensi/{kompetensi}'),
        ];
    }
}
