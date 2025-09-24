<?php
namespace App\Filament\Widgets;

use App\Models\Peserta;
use App\Models\Tes;
use App\Models\Percobaan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesertaBelumMengerjakanTable extends BaseWidget
{
    public \App\Models\Pelatihan $record; // Terima record pelatihan
    protected static ?string $heading = 'Daftar Peserta Belum Mengisi Survey';

    public function table(Table $table): Table
    {
        // 1. Cari ID tes untuk survey
        $tesSurvey = Tes::where('pelatihan_id', $this->record->id)->where('tipe', 'survey')->first();

        // 2. Ambil semua ID peserta yang SUDAH mengerjakan survey ini
        $pesertaSudahMengerjakanIds = $tesSurvey 
            ? Percobaan::where('tes_id', $tesSurvey->id)->pluck('peserta_id')->toArray()
            : [];

        return $table
            ->query(
                // 3. Ambil semua peserta di pelatihan ini yang ID-nya TIDAK ADA di array di atas
                Peserta::query()
                    ->where('pelatihan_id', $this->record->id)
                    ->whereNotIn('id', $pesertaSudahMengerjakanIds)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama'),
                Tables\Columns\TextColumn::make('no_hp'),
                Tables\Columns\TextColumn::make('bidang.nama_bidang'),
            ]);
    }
}
