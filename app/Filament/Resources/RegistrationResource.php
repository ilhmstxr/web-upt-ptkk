<?php

namespace App\Filament\Resources;

use App\Exports\TuExport;
use App\Exports\UptExport;
use App\Filament\Resources\RegistrationResource\Pages;
use App\Models\Registration;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen Pendaftaran & Kamar';
    protected static ?string $navigationLabel = 'Daftar Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Biodata Diri')
                            ->description('Data diri lengkap dari pendaftar.')
                            ->schema([
                                Forms\Components\TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255),
                                Forms\Components\TextInput::make('nik')->label('NIK')->required()->numeric()->maxLength(16),
                                Forms\Components\TextInput::make('birth_place')->label('Tempat Lahir')->required()->maxLength(255),
                                Forms\Components\DatePicker::make('birth_date')->label('Tanggal Lahir')->required(),
                                Forms\Components\Select::make('gender')->label('Jenis Kelamin')->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])->required(),
                                Forms\Components\Select::make('religion')->label('Agama')->options([
                                    'Islam' => 'Islam',
                                    'Kristen' => 'Kristen',
                                    'Katolik' => 'Katolik',
                                    'Hindu' => 'Hindu',
                                    'Buddha' => 'Buddha',
                                    'Konghucu' => 'Konghucu',
                                ])->required(),
                                Forms\Components\TextInput::make('address')->label('Alamat Tempat Tinggal')->required()->maxLength(255),
                                Forms\Components\TextInput::make('phone')->label('Nomor Handphone')->required()->tel()->maxLength(15),
                                Forms\Components\TextInput::make('email')->label('Email')->email()->required()->maxLength(255),
                            ])->columns(2),

                        Section::make('Biodata Sekolah')
                            ->description('Data lembaga asal dari pendaftar.')
                            ->schema([
                                Forms\Components\TextInput::make('school_name')->label('Asal Lembaga / Sekolah')->required()->maxLength(255),
                                Forms\Components\TextInput::make('school_address')->label('Alamat Sekolah')->required()->maxLength(255),
                                Forms\Components\Select::make('competence')->label('Kompetensi / Bidang Keahlian')->options([
                                    'Tata Boga' => 'Tata Boga',
                                    'Tata Busana' => 'Tata Busana',
                                    'Tata Kecantikan' => 'Tata Kecantikan',
                                    'Teknik Pendingin dan Tata Udara' => 'Teknik Pendingin dan Tata Udara',
                                ])->required(),
                                Forms\Components\TextInput::make('class')->label('Kelas')->required()->maxLength(255),
                                Forms\Components\Select::make('dinas_branch')->label('Cabang Dinas Wilayah')->options([
                                    'Wilayah I' => 'Wilayah I',
                                    'Wilayah II' => 'Wilayah II',
                                    'Wilayah III' => 'Wilayah III',
                                    'Wilayah IV' => 'Wilayah IV',
                                    'Wilayah V' => 'Wilayah V',
                                ])->required(),
                            ])->columns(2),

                        Section::make('Lampiran Dokumen')
                            ->description('Dokumen-dokumen pendukung yang diunggah oleh pendaftar.')
                            ->schema([
                                Forms\Components\FileUpload::make('ktp_path')->label('Unggah Fotocopy KTP')->disk('public')->required(),
                                Forms\Components\FileUpload::make('ijazah_path')->label('Unggah Fotocopy Ijazah Terakhir')->disk('public')->required(),
                                Forms\Components\FileUpload::make('surat_tugas_path')->label('Unggah Fotocopy Surat Tugas')->disk('public')->required(),
                                Forms\Components\FileUpload::make('surat_sehat_path')->label('Unggah Surat Sehat')->disk('public')->required(),
                                Forms\Components\TextInput::make('surat_tugas_nomor')->label('Nomor Surat Tugas')->required()->maxLength(255),
                                Forms\Components\FileUpload::make('pas_foto_path')->label('Unggah Pas Foto')->disk('public')->required()->helperText('Pas Foto Background Merah ukuran 3x4'),
                            ])->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Peserta')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('gender')->label('Jenis Kelamin')->sortable(),
                Tables\Columns\TextColumn::make('room.name')->label('Kamar')->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('school_name')->label('Asal Sekolah')->searchable(),
                Tables\Columns\TextColumn::make('competence')->label('Bidang Keahlian')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('download_tu')
                    ->label('Download TU')
                    ->icon('heroicon-o-arrow-down-tray')
                    // ->action(fn() => Excel::download(new TuExport, 'TU.xlsx')),
                    ->action(fn() => Excel::download(new TuExport, 'TU.xlsx')),

                Action::make('download_upt')
                    ->label('Download UPT-PELATIHAN')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn() => Excel::download(new UptExport, 'UPT-PELATIHAN.xlsx')),
                
                // Logika Otomatisasi Pembagian Kamar
                Action::make('otomatisasiPembagianKamar')
                    ->label('Otomatisasi Pembagian Kamar')
                    ->icon('heroicon-o-building-library')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function () {
                        DB::beginTransaction();

                        try {
                            $unassignedRegistrations = Registration::whereNull('room_id')->get();
                            if ($unassignedRegistrations->isEmpty()) {
                                Notification::make()->title('Tidak ada peserta yang perlu dibagi kamar.')->success()->send();
                                return;
                            }
                            
                            $availableRooms = Room::where(function ($query) {
                                $query->where('assigned_for', '!=', 'instruktur')
                                      ->orWhereNull('assigned_for');
                            })
                                ->orderBy('name')
                                ->get();

                            if ($availableRooms->isEmpty()) {
                                Notification::make()->title('Tidak ada kamar yang tersedia untuk peserta.')->danger()->send();
                                DB::rollBack();
                                return;
                            }

                            $maleRegistrations = $unassignedRegistrations->where('gender', 'L');
                            $femaleRegistrations = $unassignedRegistrations->where('gender', 'P');
                            
                            $registrantsPerRoom = [];

                            // Logika Pembagian untuk Pria
                            $this->assignRooms($maleRegistrations, $availableRooms, $registrantsPerRoom);

                            // Logika Pembagian untuk Wanita
                            $this->assignRooms($femaleRegistrations, $availableRooms, $registrantsPerRoom);
                            
                            DB::commit();

                            $message = 'Pembagian kamar berhasil: <br><br>';
                            foreach ($registrantsPerRoom as $roomName => $names) {
                                $message .= "<b>Kamar {$roomName}:</b> " . implode(', ', $names) . "<br>";
                            }

                            Notification::make()
                                ->title('Pembagian Kamar Berhasil!')
                                ->body($message)
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Terjadi kesalahan saat pembagian kamar.')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    // Pilihan A: Aksi Massal (Bulk Action) Manual
                    BulkAction::make('assignRoom')
                        ->label('Pindahkan ke Kamar')
                        ->icon('heroicon-o-building-office')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Select::make('room_id')
                                ->label('Pilih Kamar')
                                ->options(Room::pluck('name', 'id'))
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $room = Room::find($data['room_id']);
                            if (!$room) {
                                Notification::make()->title('Kamar tidak ditemukan!')->danger()->send();
                                return;
                            }
                            
                            $currentOccupancy = $room->registrations()->count();
                            $remainingCapacity = $room->capacity - $currentOccupancy;
                            
                            if ($records->count() > $remainingCapacity) {
                                Notification::make()
                                    ->title('Kamar Penuh!')
                                    ->body("Kamar {$room->name} hanya bisa menampung {$remainingCapacity} peserta lagi.")
                                    ->danger()
                                    ->send();
                                return;
                            }

                            foreach ($records as $record) {
                                $record->room_id = $data['room_id'];
                                $record->save();
                            }
                            Notification::make()
                                ->title('Berhasil memindahkan peserta!')
                                ->body("{$records->count()} peserta berhasil dipindahkan ke kamar {$room->name}.")
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRegistrations::route('/'),
            'create' => Pages\CreateRegistration::route('/create'),
            'edit' => Pages\EditRegistration::route('/{record}/edit'),
            'view' => Pages\ViewRegistration::route('/{record}'),
        ];
    }
    
    // Helper function untuk membagi peserta ke kamar
    private function assignRooms($registrations, $rooms, &$registrantsPerRoom)
    {
        $roomIndex = 0;
        
        foreach ($registrations as $registration) {
            $currentRoom = $rooms->get($roomIndex);

            if (!$currentRoom) {
                Notification::make()
                    ->title('Kapasitas kamar tidak mencukupi!')
                    ->body('Beberapa peserta mungkin tidak mendapatkan kamar. Silakan periksa kembali kapasitas kamar.')
                    ->danger()
                    ->send();
                return;
            }
            
            $currentOccupancy = $currentRoom->registrations()->count();
            
            while ($currentOccupancy >= $currentRoom->capacity) {
                $roomIndex++;
                $currentRoom = $rooms->get($roomIndex);
                if (!$currentRoom) {
                     Notification::make()
                        ->title('Kapasitas kamar tidak mencukupi!')
                        ->body('Beberapa peserta mungkin tidak mendapatkan kamar. Silakan periksa kembali kapasitas kamar.')
                        ->danger()
                        ->send();
                    return;
                }
                $currentOccupancy = $currentRoom->registrations()->count();
            }

            $registration->room_id = $currentRoom->id;
            $registration->save();
            
            $registrantsPerRoom["{$currentRoom->name} " . ($currentRoom->section ? "({$currentRoom->section})" : "")][] = $registration->name;
        }
    }
}
