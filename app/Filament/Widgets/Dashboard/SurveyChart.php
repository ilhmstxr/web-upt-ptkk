<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\ChartWidget;

class SurveyChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
