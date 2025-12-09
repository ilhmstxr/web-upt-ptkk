<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\BannerResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\BannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Banner;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data['is_featured'] ?? false) {
            Banner::query()
                ->where('id', '!=', $this->record->id)
                ->update(['is_featured' => false]);
        }

        return $data;
    }

}