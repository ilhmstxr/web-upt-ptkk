<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets;

use App\Models\Asrama;
use App\Models\Kamar;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class AsramaDenahWidget extends Widget
{
    protected static string $view = 'filament.clusters.fasilitas.resources.asrama-resource.widgets.asrama-denah-widget';

    public ?Model $record = null;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        // Jika record (Asrama) ada, ambil kamarnya. Jika tidak, kosong.
        // Widget ini idealnya dipasang di ViewPage atau EditPage dari AsramaResource
        
        $kamars = [];
        if ($this->record) {
            $kamars = $this->record->kamars()->orderBy('lantai')->orderBy('nomor_kamar')->get()->groupBy('lantai');
        }

        return [
            'kamars_by_lantai' => $kamars,
        ];
    }
}
