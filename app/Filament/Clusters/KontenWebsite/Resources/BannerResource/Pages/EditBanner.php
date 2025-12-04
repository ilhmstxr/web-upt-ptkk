<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\BannerResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
