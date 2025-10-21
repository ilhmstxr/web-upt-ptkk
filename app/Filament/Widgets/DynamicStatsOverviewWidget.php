<?php

namespace App\Filament\Widgets;
// namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DynamicStatsOverviewWidget extends BaseWidget
{

    public array $stats = [];
    protected function getStats(): array
    {
        $cards = [];
        foreach ($this->stats as $stat) {
            $cards[] = Stat::make($stat['label'], $stat['value'])
                ->description($stat['description'] ?? null)
                ->descriptionIcon($stat['descriptionIcon'] ?? null)
                ->color($stat['color'] ?? 'success');
        }

        return $cards;
    }

    public static function isLazy(): bool
    {
        return false;
    }
}
