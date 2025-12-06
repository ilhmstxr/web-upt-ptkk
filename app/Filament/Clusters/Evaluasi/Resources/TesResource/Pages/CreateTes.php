<?php

namespace App\Filament\Clusters\Evaluasi\Resources\TesResource\Pages;

use App\Filament\Clusters\Evaluasi\Resources\TesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTes extends CreateRecord
{
    protected static string $resource = TesResource::class;

    protected static string $view = 'filament.clusters.evaluasi.resources.tes-resource.pages.create-tes';
}
