<?php

namespace App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;

use App\Filament\Clusters\Fasilitas\Resources\AsramaResource;
use App\Models\Asrama;
use App\Models\Kamar;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config; // Import Config facade

class ListAsramas extends ListRecords
{
    protected static string $resource = AsramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // Action kustom untuk mengimpor konfigurasi kamar dari file config/kamar.php
            Actions\Action::make('ImportKonfigurasiAwal')
                ->label('Import Konfigurasi Kamar')
                ->icon('heroicon-o-building-library')
                ->requiresConfirmation()
                ->color('info')
                ->action(function () {
                    // Ambil data konfigurasi dari config/kamar.php menggunakan helper Config
                    $dataKonfigurasi = Config::get('kamar.data_konfigurasi');

                    if (empty($dataKonfigurasi)) {
                         Notification::make()
                            ->title('Gagal Impor')
                            ->body("Data konfigurasi kamar kosong atau tidak ditemukan di config/kamar.php.")
                            ->danger()
                            ->send();
                        return;
                    }

                    $totalAsrama = 0;
                    $totalKamar = 0;

                    DB::beginTransaction();

                    try {
                        foreach ($dataKonfigurasi as $namaAsrama => $kamarData) {
                            // 1. Tentukan Gender berdasarkan nama Asrama (asumsi sederhana)
                            // Jika ada nama yang mengandung 'Mawar' atau 'Melati' dianggap Perempuan, sisanya Laki-laki
                            $gender = (str_contains($namaAsrama, 'Mawar') || str_contains($namaAsrama, 'Melati')) ? 'Perempuan' : 'Laki-laki';
                            
                            // 2. Tentukan Kapasitas total Asrama (Jumlah bed yang valid)
                            $kapasitasTotal = collect($kamarData)
                                ->filter(fn ($k) => is_numeric($k['bed']) && $k['bed'] > 0)
                                ->sum('bed');

                            // 3. Buat atau Update Asrama
                            $asrama = Asrama::updateOrCreate(
                                ['nama' => $namaAsrama],
                                [
                                    'gender' => $gender,
                                    // Pastikan kapasitas total minimal 1 jika ada kamar yang aktif
                                    'kapasitas' => max(1, $kapasitasTotal), 
                                    'is_active' => true,
                                ]
                            );
                            $totalAsrama++;
                            
                            // 4. Buat Kamar untuk Asrama ini
                            foreach ($kamarData as $kamarItem) {
                                // Tentukan kapasitas bed yang valid (bukan null atau 'rusak')
                                $jumlahBed = is_numeric($kamarItem['bed']) && $kamarItem['bed'] > 0 ? (int)$kamarItem['bed'] : 0;
                                $status = $kamarItem['bed'] === 'rusak' ? 'rusak' : 'aktif';
                                
                                Kamar::updateOrCreate(
                                    [
                                        'asrama_id' => $asrama->id,
                                        'nomor_kamar' => $kamarItem['no']
                                    ],
                                    [
                                        'kapasitas_bed' => $jumlahBed,
                                        'status' => $status,
                                        'keterangan' => $status === 'rusak' ? 'Kamar sedang dalam perbaikan.' : null,
                                    ]
                                );
                                $totalKamar++;
                            }
                        }

                        DB::commit();

                        Notification::make()
                            ->title('Impor Konfigurasi Berhasil')
                            ->body("Berhasil mengimpor {$totalAsrama} Asrama dan {$totalKamar} Kamar ke database.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        
                        Notification::make()
                            ->title('Gagal Impor')
                            ->body("Terjadi kesalahan saat menyimpan data: " . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}