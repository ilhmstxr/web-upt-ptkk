<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use App\Models\Kompetensi;
use App\Models\KompetensiPelatihan;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\Peserta;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListPendaftarans extends ListRecords
{
    protected static string $resource = PendaftaranResource::class;

    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.clusters.kesiswaan.components.resource-tabs', [
            'activeTab' => 'peserta'
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
