<?php

namespace App\Filament\Resources\JawabanSurveiResource\Widgets;

use App\Models\Pelatihan;
use Filament\Widgets\Widget;

class PelatihanList extends Widget
{
    protected static string $view = 'filament.resources.jawaban-survei-resource.widgets.pelatihan-list';

    public $pelatihans;

    public function mount()
    {
        $this->pelatihans = Pelatihan::all();
    }
}
