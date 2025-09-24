<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\PelatihanListWidget;
use Filament\Pages\Page;

class DashboardPelatihan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard-pelatihan';

    protected function getHeaderWidgets(): array
    {
        return [
            PelatihanListWidget::class,
        ];
    }
}
