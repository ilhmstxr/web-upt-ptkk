<?php

namespace App\Filament\Resources\PreTestResultsResource\Pages;

use App\Filament\Resources\PreTestResultsResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;

class ListPreTestResults extends ListRecords
{
    protected static string $resource = PreTestResultsResource::class;
}
