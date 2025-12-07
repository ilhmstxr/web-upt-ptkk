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
        $kamarsByLantai = collect();

        if ($this->record) {
            // asumsikan $this->record adalah instance Asrama
            $kamarsByLantai = $this->record->kamars()
                ->withCount('penempatanAsrama') // ->penempatan_asrama_count
                ->orderBy('lantai')
                ->orderBy('nomor_kamar')
                ->get()
                ->groupBy('lantai');
        }

        return [
            'kamars_by_lantai' => $kamarsByLantai,
            'asrama'           => $this->record,
        ];
    }
}
