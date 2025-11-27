<?php

namespace App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;

use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource;
use App\Models\BidangPelatihan;
use App\Models\Pelatihan;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ViewBidangPelatihan extends Page
{
    protected static string $resource = PelatihanResource::class;

    protected static string $view = 'filament.clusters.pelatihan.resources.pelatihan-resource.pages.view-bidang-pelatihan';

    public Pelatihan $record;
    public BidangPelatihan $bidangPelatihan;

    public function mount(Pelatihan $record, $bidang_id): void
    {
        $this->record = $record;
        $this->bidangPelatihan = BidangPelatihan::findOrFail($bidang_id);
    }

    public function getTitle(): string | Htmlable
    {
        return $this->bidangPelatihan->bidang->nama_bidang ?? 'Detail Bidang';
    }
}
