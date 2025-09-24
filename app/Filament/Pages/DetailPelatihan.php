<?php

namespace App\Filament\Pages;

use App\Models\Pelatihan;
use Filament\Pages\Page;

class DetailPelatihan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static string $view = 'filament.pages.detail-pelatihan';
    protected static bool $shouldRegisterNavigation = false; // Sembunyikan dari navigasi

    public Pelatihan $record;

    // Gunakan metode mount() untuk menerima parameter dari URL
    public function mount(Pelatihan $record): void
    {
        $this->record = $record;
    }
}
