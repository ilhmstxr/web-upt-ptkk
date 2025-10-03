<?php

namespace App\Filament\Pages;

use App\Filament\Pages\ViewBidangDetail;
use App\Filament\Widgets\BidangScoresChart;
use App\Filament\Widgets\BidangSummaryTable;
use App\Models\Bidang;
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
    protected static ?string $navigationLabel = 'Analisis Pelatihan';
    protected static ?string $title = 'Analisis Data Pelatihan';

    protected function getHeaderWidgets(): array
    {
        return [
            BidangScoresChart::class,
            BidangSummaryTable::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Bidang::query())
            ->heading('Detail per Bidang Pelatihan')
            ->columns([
                TextColumn::make('nama_bidang')->label('Nama Bidang')->searchable(),
                TextColumn::make('pelatihan.nama_pelatihan')->label('Bagian dari Pelatihan'),
            ])
            ->actions([
                Action::make('view_detail')
                    ->label('Lihat Detail Peserta')
                    ->icon('heroicon-o-arrow-right')
                    ->url(fn (Bidang $record): string => ViewBidangDetail::getUrl(['record' => $record])),
            ])
            ->paginated(false);
    }
}
