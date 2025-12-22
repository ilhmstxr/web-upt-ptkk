<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class AsramaDenahWidget extends Widget
{
    protected static string $view = 'filament.clusters.fasilitas.resources.asrama-resource.widgets.asrama-denah-widget';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $kamars = collect();

        if ($this->record) {
            // asumsikan $this->record adalah instance Asrama
            $kamars = $this->record->kamars()
                ->withCount('penempatanAsrama') // ->penempatan_asrama_count
                ->orderBy('nomor_kamar')
                ->get();
        }

        return [
            'kamars' => $kamars,
            'asrama' => $this->record,
        ];
    }
}
