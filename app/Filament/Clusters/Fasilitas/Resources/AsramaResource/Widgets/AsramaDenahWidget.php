<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class AsramaDenahWidget extends Widget
{
    protected static string $view = 'filament.clusters.fasilitas.resources.asrama-resource.widgets.asrama-denah-widget';

    // Jika widget ini dipasang di EditPage / ViewPage, Filament akan meng-inject record.
    public ?Model $record = null;

    // Tampilkan full width default, ubah kalau mau
    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $kamars_by_lantai = collect();

        if ($this->record) {
            // ambil kamar yang terkait, group by lantai supaya template gampang render
            $kamars_by_lantai = $this->record
                ->kamars()
                ->orderBy('lantai')
                ->orderBy('nomor_kamar')
                ->get()
                ->groupBy('lantai');
        }

        return [
            'record' => $this->record,
            'kamars_by_lantai' => $kamars_by_lantai,
        ];
    }
}
