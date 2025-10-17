<?php

namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class PelatihanStatsOverview extends BaseWidget
{
    public ?Model $record = null; // Menerima record Pelatihan

    protected function getStats(): array
    {
        if (!$this->record) return [];

        return [
            Stat::make('Tanggal Pelatihan', $this->record->tanggal_mulai->format('d M') . ' - ' . $this->record->tanggal_selesai->format('d M Y')),
            Stat::make('Total Peserta', $this->record->pesertas()->count()),
            Stat::make('Total Bidang', $this->record->bidangs()->count()),
            Stat::make('Total Instruktur', $this->record->instrukturs()->count()),
        ];
    }
}