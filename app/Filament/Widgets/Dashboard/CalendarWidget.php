<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Pelatihan;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $fetchInfo): array
    {
        return Pelatihan::where('tanggal_mulai', '>=', $fetchInfo['start'])
            ->where('tanggal_selesai', '<=', $fetchInfo['end'])
            ->get()
            ->map(function (Pelatihan $pelatihan) {
                return [
                    'id'    => $pelatihan->id,
                    'title' => $pelatihan->nama_pelatihan,
                    'start' => $pelatihan->tanggal_mulai,
                    'end'   => $pelatihan->tanggal_selesai,
                ];
            })
            ->toArray();
    }
}