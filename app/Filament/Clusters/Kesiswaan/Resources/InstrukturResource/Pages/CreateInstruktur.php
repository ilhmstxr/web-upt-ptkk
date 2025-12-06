<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInstruktur extends CreateRecord
{
    protected static string $resource = InstrukturResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = \App\Models\User::create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        // Assign role 'instruktur' if using Spatie Permission
        // $user->assignRole('instruktur'); 

        $data['user_id'] = $user->id;

        unset($data['email']);
        unset($data['password']);

        return $data;
    }
}
