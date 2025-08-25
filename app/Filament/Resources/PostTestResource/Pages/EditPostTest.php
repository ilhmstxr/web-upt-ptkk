<?php

namespace App\Filament\Resources\PostTestResource\Pages;

use App\Filament\Resources\PostTestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPostTest extends EditRecord
{
    protected static string $resource = PostTestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
