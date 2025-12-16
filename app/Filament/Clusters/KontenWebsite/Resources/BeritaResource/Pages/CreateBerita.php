<?php

namespace App\Filament\Clusters\KontenWebsite\Resources\BeritaResource\Pages;

use App\Filament\Clusters\KontenWebsite\Resources\BeritaResource;
use App\Models\Berita;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
{
    if (empty($data['slug']) && !empty($data['title'])) {
        $base = Str::slug($data['title']);
        $slug = $base;
        $i = 2;

        while (Berita::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        $data['slug'] = $slug;
    }

    return $data;
}

}
