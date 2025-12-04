<?php

namespace App\Filament\Clusters\Alumni\Resources\TracerStudyResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\TracerStudy;

class TracerStudySalaryChart extends Widget
{
    protected static string $view = 'filament.clusters.alumni.resources.tracer-study-resource.widgets.tracer-study-salary-widget';

    protected function getViewData(): array
    {
        return [
            'avgWaitingPeriod' => number_format(TracerStudy::avg('waiting_period') ?? 0, 1),
            'avgSalary' => number_format((TracerStudy::avg('salary') ?? 0) / 1000000, 1),
        ];
    }
}
