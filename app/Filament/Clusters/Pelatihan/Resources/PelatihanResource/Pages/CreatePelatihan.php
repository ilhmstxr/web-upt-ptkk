<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use \Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreatePelatihan extends CreateRecord
{
    use HasWizard;

    protected static string $resource = PelatihanResource::class;

    protected function getSteps(): array
    {
        return PelatihanResource::getWizardSteps();
    }
}
