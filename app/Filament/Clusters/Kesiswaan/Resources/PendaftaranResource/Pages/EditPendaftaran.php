<?php

namespace App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;

use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPendaftaran extends EditRecord
{
    protected static string $resource = PendaftaranResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        $peserta = $record->peserta;

        // Map Pendaftaran attributes
        $data['kompetensi_keahlian'] = $record->kompetensi_pelatihan_id;

        if ($peserta) {
            // Map Peserta attributes
            $data['nama']          = $peserta->nama;
            $data['nik']           = $peserta->nik;
            $data['no_hp']         = $peserta->no_hp;
            $data['tempat_lahir']  = $peserta->tempat_lahir;
            $data['tanggal_lahir'] = $peserta->tanggal_lahir;
            $data['jenis_kelamin'] = $peserta->jenis_kelamin;
            $data['agama']         = $peserta->agama;
            $data['alamat']        = $peserta->alamat;

            // Map User email
            if ($peserta->user) {
                $data['email'] = $peserta->user->email;
            }

            // Map Instansi attributes
            if ($peserta->instansi) {
                $instansi = $peserta->instansi;
                $data['asal_instansi']   = $instansi->asal_instansi;
                $data['alamat_instansi'] = $instansi->alamat_instansi;
                $data['kota']            = $instansi->kota;
                $data['cabangDinas_id']  = $instansi->cabangDinas_id;
            }

            // Map Lampiran attributes
            if ($peserta->lampiran) {
                $lampiran = $peserta->lampiran;
                $data['fc_ktp']             = $lampiran->fc_ktp;
                $data['fc_ijazah']          = $lampiran->fc_ijazah;
                $data['fc_surat_tugas']     = $lampiran->fc_surat_tugas;
                $data['fc_surat_sehat']     = $lampiran->fc_surat_sehat;
                $data['pas_foto']           = $lampiran->pas_foto;
                $data['nomor_surat_tugas']  = $lampiran->no_surat_tugas;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($record, $data) {
            // 1. Update Instansi
            if ($record->peserta && $record->peserta->instansi) {
                $record->peserta->instansi->update([
                    'asal_instansi'   => $data['asal_instansi'],
                    'alamat_instansi' => $data['alamat_instansi'],
                    'kota'            => $data['kota'],
                    'cabangDinas_id'  => $data['cabangDinas_id'],
                ]);
            }

            // 2. Update User (Email/Name)
            if ($record->peserta && $record->peserta->user) {
                $record->peserta->user->update([
                    'email' => $data['email'],
                    'name'  => $data['nama'],
                    'phone' => $data['no_hp'],
                ]);
            }

            // 3. Update Peserta
            if ($record->peserta) {
                $record->peserta->update([
                    'nama'          => $data['nama'],
                    'nik'           => $data['nik'],
                    'no_hp'         => $data['no_hp'],
                    'tempat_lahir'  => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'agama'         => $data['agama'],
                    'alamat'        => $data['alamat'],
                ]);

                // 4. Update Lampiran
                $lampiranData = [
                    'no_surat_tugas' => $data['nomor_surat_tugas'] ?? null,
                ];
                // Only update files if new ones uploaded (Filament passes path string)
                if (isset($data['fc_ktp'])) $lampiranData['fc_ktp'] = $data['fc_ktp'];
                if (isset($data['fc_ijazah'])) $lampiranData['fc_ijazah'] = $data['fc_ijazah'];
                if (isset($data['fc_surat_tugas'])) $lampiranData['fc_surat_tugas'] = $data['fc_surat_tugas'];
                if (isset($data['fc_surat_sehat'])) $lampiranData['fc_surat_sehat'] = $data['fc_surat_sehat'];
                if (isset($data['pas_foto'])) $lampiranData['pas_foto'] = $data['pas_foto'];

                \App\Models\LampiranPeserta::updateOrCreate(
                    ['peserta_id' => $record->peserta->id],
                    $lampiranData
                );
            }

            // 5. Update Pendaftaran
            $record->update([
                'pelatihan_id'            => $data['pelatihan_id'],
                'kompetensi_pelatihan_id' => $data['kompetensi_keahlian'],
                // 'kompetensi_id' => ??? Should perform lookup if changed
                'kelas'                   => $data['kelas'],
                // Update kompetensi_id lookup
                'kompetensi_id'           => \App\Models\KompetensiPelatihan::find($data['kompetensi_keahlian'])?->kompetensi_id ?? $record->kompetensi_id,
            ]);

            return $record;
        });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
