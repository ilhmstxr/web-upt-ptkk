<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Widgets\AsramaStatsOverview;
use App\Models\Asrama;
use App\Models\Kamar;
use App\Models\Pelatihan;
use App\Models\PendaftaranPelatihan;
use App\Models\PenempatanAsrama;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;

    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Manajemen Asrama';

    /**
     * MENDAPATKAN KONFIGURASI KAMAR (Hybrid: Config File + Fallback)
     * Sistem akan mencoba membaca config/kamar.php.
     * Jika gagal (karena cache), akan menggunakan data hardcoded di bawah ini.
     */
    private static function getDenahAsrama(): array
    {
        // 1. Coba ambil dari file config/kamar.php
        $dataConfig = config('kamar');

        if (!empty($dataConfig) && is_array($dataConfig)) {
            return $dataConfig;
        }

        // 2. Fallback: Data Hardcoded (Jika config gagal terbaca karena cache)
        return [
            'Mawar' => [
                ['no' => 1, 'bed' => 4],
                ['no' => 2, 'bed' => null],
                ['no' => 3, 'bed' => null],
                ['no' => 4, 'bed' => 4],
                ['no' => 5, 'bed' => 4],
                ['no' => 6, 'bed' => 4],
                ['no' => 7, 'bed' => 7],
                ['no' => 8, 'bed' => 9],
            ],
            'Melati Bawah' => [
                ['no' => 1, 'bed' => null],
                ['no' => 2, 'bed' => 4],
                ['no' => 3, 'bed' => 4],
                ['no' => 4, 'bed' => 4],
            ],
            'Melati Atas' => [
                ['no' => 5, 'bed' => 6],
                ['no' => 6, 'bed' => 4],
                ['no' => 7, 'bed' => null],
                ['no' => 8, 'bed' => null],
                ['no' => 9, 'bed' => 'rusak'],
            ],
            'Tulip Bawah' => [
                ['no' => 1, 'bed' => 'rusak'],
                ['no' => 2, 'bed' => 'rusak'],
                ['no' => 3, 'bed' => 'rusak'],
                ['no' => 4, 'bed' => 'rusak'],
                ['no' => 5, 'bed' => 'rusak'],
                ['no' => 6, 'bed' => 3],
                ['no' => 7, 'bed' => 3],
                ['no' => 8, 'bed' => 3],
                ['no' => 9, 'bed' => 'rusak'],
                ['no' => 10, 'bed' => 3],
            ],
            'Tulip Atas' => [
                ['no' => 11, 'bed' => null],
                ['no' => 12, 'bed' => null],
                ['no' => 13, 'bed' => 'rusak'],
                ['no' => 14, 'bed' => 4],
                ['no' => 15, 'bed' => 'rusak'],
                ['no' => 16, 'bed' => 'rusak'],
                ['no' => 17, 'bed' => 'rusak'],
                ['no' => 18, 'bed' => 'rusak'],
                ['no' => 19, 'bed' => 'rusak'],
                ['no' => 20, 'bed' => 'rusak'],
                ['no' => 21, 'bed' => 4],
                ['no' => 22, 'bed' => 3],
                ['no' => 23, 'bed' => 3],
                ['no' => 24, 'bed' => 3],
                ['no' => 25, 'bed' => 3],
                ['no' => 26, 'bed' => 'rusak'],
                ['no' => 27, 'bed' => null],
                ['no' => 28, 'bed' => 4],
            ],
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Asrama')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Gunakan nama persis seperti di config: Mawar, Melati Bawah, dll.'),

                        Forms\Components\Select::make('gender')
                            ->label('Khusus Gender')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan'  => 'Perempuan',
                                'Campur'     => 'Campur',
                            ])
                            ->required(),
                        
                        // Field dummy untuk tampilan kapasitas
                        Forms\Components\TextInput::make('kapasitas_display')
                            ->label('Kapasitas Total')
                            ->placeholder('Dihitung dari kamar')
                            ->disabled()
                            ->dehydrated(false) 
                            ->helperText('Kapasitas dihitung otomatis dari jumlah kamar aktif.'),

                        Forms\Components\TextInput::make('kontak_pic')
                            ->label('Kontak PIC')
                            ->nullable()
                            ->tel()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('alamat')->label('Alamat')->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                            
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Asrama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Peruntukan')
                    ->badge()
                    ->colors([
                        'primary' => 'Laki-laki',
                        'danger' => 'Perempuan',
                        'warning' => 'Campur',
                    ]),
                
                Tables\Columns\TextColumn::make('total_kapasitas')
                    ->label('Kapasitas')
                    ->getStateUsing(function (Asrama $record) {
                        return $record->kamars()->where('is_active', true)->sum('kapasitas');
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('kamars_count')
                    ->label('Jml Kamar')
                    ->counts('kamars')
                    ->badge(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan'  => 'Perempuan',
                        'Campur'     => 'Campur',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('otomasi_pembagian')
                    ->label('Otomasi Pembagian Kamar')
                    ->icon('heroicon-o-cpu-chip')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('pelatihan_id')
                            ->label('Pilih Pelatihan (Ready)')
                            ->options(function () {
                                return Pelatihan::query()
                                    ->orderBy('id', 'desc')
                                    ->pluck('nama_pelatihan', 'id');
                            })
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('asrama_id')
                            ->label('Pilih Asrama Target')
                            ->options(Asrama::pluck('name', 'id'))
                            ->required()
                            ->helperText('Sistem akan menyinkronkan kamar & bed sesuai data config sebelum mengisi peserta.'),
                        
                        Forms\Components\Radio::make('metode')
                            ->label('Metode Pengisian')
                            ->options([
                                'urut' => 'Urut (Isi penuh satu kamar lalu pindah)',
                                'rata' => 'Rata (Sebar peserta ke semua kamar)',
                            ])
                            ->default('urut')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        self::jalankanOtomasi($data);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Logic Utama: 1. Sync Config -> DB, 2. Alokasi Peserta -> Kamar
     */
    protected static function jalankanOtomasi(array $data)
    {
        DB::beginTransaction();
        try {
            $pelatihanId = $data['pelatihan_id'];
            $asramaId = $data['asrama_id'];
            $metode = $data['metode'];

            // --- STEP 1: LOAD ASRAMA & CONFIG ---
            $asramaDb = Asrama::findOrFail($asramaId);
            $namaAsrama = $asramaDb->name; 
            $denahConfig = self::getDenahAsrama(); // Menggunakan fungsi hybrid di atas

            $targetConfig = null;
            foreach ($denahConfig as $key => $conf) {
                if (str_contains(strtolower($namaAsrama), strtolower($key))) {
                    $targetConfig = $conf;
                    break;
                }
            }

            if (!$targetConfig) {
                throw new \Exception("Config untuk asrama '{$namaAsrama}' tidak ditemukan (baik di file config maupun default script). Pastikan nama asrama mengandung kata kunci (Mawar/Melati/Tulip).");
            }

            // --- STEP 2: SYNC DATABASE KAMAR DENGAN CONFIG ---
            foreach ($targetConfig as $kamarConf) {
                $noKamar = (string)$kamarConf['no'];
                $valBed  = $kamarConf['bed']; 

                $kamarDb = Kamar::firstOrCreate(
                    [
                        'asrama_id'   => $asramaDb->id,
                        'nomor_kamar' => $noKamar
                    ],
                    [
                        'nama' => "Kamar {$noKamar}"
                    ]
                );

                $isActive = true;
                $kapasitas = 0;
                $ket = 'Ready';

                if ($valBed === 'rusak') {
                    $isActive = false;
                    $ket = 'Rusak';
                } elseif (is_null($valBed) || $valBed === 0) {
                    $isActive = false;
                    $ket = 'Tidak Digunakan';
                } else {
                    $kapasitas = (int)$valBed;
                }

                $kamarDb->update([
                    'kapasitas'  => $kapasitas,
                    'is_active'  => $isActive,
                    'keterangan' => $ket,
                ]);
            }

            // --- STEP 3: AMBIL KAMAR YANG SIAP PAKAI ---
            $activeKamars = Kamar::where('asrama_id', $asramaDb->id)
                ->where('is_active', true)
                ->where('kapasitas', '>', 0)
                ->orderByRaw('CAST(nomor_kamar AS UNSIGNED) ASC')
                ->withCount(['penempatan' => function ($q) {
                    $q->whereNull('checkout_at');
                }])
                ->get();

            if ($activeKamars->isEmpty()) {
                DB::commit(); 
                Notification::make()->title('Config Sync OK, tapi tidak ada kamar aktif.')->warning()->send();
                return;
            }

            // --- STEP 4: AMBIL PESERTA PELATIHAN (BELUM PUNYA KAMAR) ---
            $queryPeserta = PendaftaranPelatihan::with('peserta')
                ->where('pelatihan_id', $pelatihanId)
                ->whereDoesntHave('penempatanAsrama');

            if ($asramaDb->gender !== 'Campur') {
                $queryPeserta->whereHas('peserta', function($q) use ($asramaDb) {
                    $q->where('jenis_kelamin', $asramaDb->gender);
                });
            }

            $listPeserta = $queryPeserta->get();

            if ($listPeserta->isEmpty()) {
                DB::commit();
                Notification::make()->title('Config Sync OK. Tidak ada peserta baru untuk dialokasikan.')->success()->send();
                return;
            }

            // --- STEP 5: ALGORITMA ALOKASI ---
            $berhasil = 0;
            $kamarIndex = 0;
            $totalKamars = $activeKamars->count();

            foreach ($listPeserta as $pendaftar) {
                $found = false;
                $loopCount = 0;

                while ($loopCount < $totalKamars) {
                    $idx = $kamarIndex % $totalKamars;
                    $targetKamar = $activeKamars[$idx];

                    if ($targetKamar->penempatan_count < $targetKamar->kapasitas) {
                        PenempatanAsrama::create([
                            'pendaftaran_id' => $pendaftar->id,
                            'kamar_id'       => $targetKamar->id,
                            'checkin_at'     => now(),
                        ]);

                        $targetKamar->penempatan_count++; 
                        $berhasil++;
                        $found = true;

                        if ($metode === 'rata') {
                            $kamarIndex++;
                        } else {
                            if ($targetKamar->penempatan_count >= $targetKamar->kapasitas) {
                                $kamarIndex++;
                            }
                        }
                        break; 
                    } else {
                        $kamarIndex++;
                    }
                    $loopCount++;
                }

                if (!$found) break; 
            }

            DB::commit();

            Notification::make()
                ->title('Proses Selesai')
                ->body("Sync Config OK. Berhasil menempatkan {$berhasil} peserta di {$namaAsrama}.")
                ->success()
                ->send();

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\KamarRelationManager::class,
        ];
    }
    
    public static function getWidgets(): array
    {
        return [
            AsramaStatsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'edit'   => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }
}