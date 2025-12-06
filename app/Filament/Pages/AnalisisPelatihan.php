<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ViewKompetensiDetail;
use App\Filament\Widgets\KompetensiScoresChart;
use App\Filament\Widgets\KompetensiSummaryTable;
use App\Models\Kompetensi;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class AnalisisPelatihan extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static string $view = 'filament.pages.analisis-pelatihan';
    protected static ?string $title = 'Analisis Data Pelatihan';
    protected static ?string $navigationGroup = 'Hasil Kegiatan';



    protected function getHeaderWidgets(): array
    {
        return [
            // KompetensiScoresChart::class,
            // KompetensiSummaryTable::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Kompetensi::query())
            ->heading('Detail per Kompetensi Pelatihan')
            ->columns([
                TextColumn::make('nama_kompetensi')->label('Nama Kompetensi')->searchable(),
                TextColumn::make('pelatihan.nama_pelatihan')->label('Bagian dari Pelatihan'),
            ])
            ->actions([
                Action::make('view_detail')
                    ->label('Lihat Detail Peserta')
                    ->icon('heroicon-o-arrow-right')
                    // ->url(fn(Kompetensi $record): string => ViewKompetensiDetail::getUrl(['record' => $record])),
            ])
            ->paginated(false);
    }
}
