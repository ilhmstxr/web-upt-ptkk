<?php
namespace App\Filament\Widgets;

use App\Models\Pelatihan;
use Filament\Widgets\Widget;

class PelatihanListWidget extends Widget
{
    protected static string $view = 'filament.widgets.pelatihan-list-widget';
    protected int | string | array $columnSpan = 'full';

    public function getPelatihanProperty()
    {
        // Ambil semua data pelatihan, diurutkan berdasarkan tanggal terbaru
        return Pelatihan::orderBy('tanggal_mulai', 'desc')->get();
    }
}