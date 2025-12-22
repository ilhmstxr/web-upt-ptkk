<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class KompetensiVenueWidget extends Widget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    protected static string $view = 'filament.widgets.kompetensi-venue-widget';
}
