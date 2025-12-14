<?php

namespace App\Filament\Clusters\Kesiswaan\Resources;

use App\Filament\Clusters\Kesiswaan;
use App\Filament\Clusters\Kesiswaan\Resources\PendaftaranResource\Pages;
use App\Models\PendaftaranPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendaftaranResource extends Resource
{

    protected static ?string $model = PendaftaranPelatihan::class;
    protected static ?string $cluster = Kesiswaan::class;

    // ✅ Hero icon + label biar rapih di sidebar
    protected static ?string $navigationIcon  = 'heroicon-o-users'; // Changed icon to users to match 'Peserta'
    protected static ?string $navigationLabel = 'Peserta';
    protected static ?string $modelLabel      = 'Peserta';
    protected static ?string $pluralModelLabel = 'Peserta';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    // STEP 1: Pelatihan & Kompetensi
                    Forms\Components\Wizard\Step::make('Pelatihan')
                        ->schema([
                            Forms\Components\Select::make('pelatihan_id')
                                ->label('Pelatihan')
                                ->relationship('pelatihan', 'nama_pelatihan')
                                ->reactive()
                                ->required(),

                            Forms\Components\Select::make('kompetensi_keahlian') // Maps to kompetensi_pelatihan_id
                                ->label('Kompetensi Keahlian')
                                ->options(function (Forms\Get $get) {
                                    $pelatihanId = $get('pelatihan_id');
                                    if (!$pelatihanId) return [];
                                    return \App\Models\KompetensiPelatihan::where('pelatihan_id', $pelatihanId)
                                        ->with('kompetensi')
                                        ->get()
                                        ->pluck('kompetensi.nama_kompetensi', 'id');
                                })
                                ->required(),
                        ]),

                    // STEP 2: Data Diri (Peserta & User)
                    Forms\Components\Wizard\Step::make('Data Diri')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->maxLength(150),
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->required()
                                    ->numeric()
                                    ->length(16),
                                Forms\Components\TextInput::make('no_hp')
                                    ->label('No. HP')
                                    ->required()
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->required(),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->required(),
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->required(),
                                Forms\Components\TextInput::make('agama')
                                    ->required(),
                                Forms\Components\Textarea::make('alamat')
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                        ]),

                    // STEP 3: Instansi
                    Forms\Components\Wizard\Step::make('Instansi')
                        ->schema([
                            Forms\Components\TextInput::make('asal_instansi')
                                ->label('Asal Sekolah / Instansi')
                                ->required(),
                            Forms\Components\TextInput::make('alamat_instansi')
                                ->required(),
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('kota')
                                    ->label('Kota / Kabupaten')
                                    ->required(),
                                // Note: kota_id handled via logic or simplify to just text if acceptable, 
                                // but controller requires kota_id. For now let's use a hidden or text input for ID simulation if needed,
                                // or just Select if we have master data. Assuming basic text for now to match UI text.
                                // Actually Controller validation requires kota_id | integer. 
                                // Let's try to find CabangDinas for relation.
                                Forms\Components\Select::make('cabangDinas_id')
                                    ->label('Cabang Dinas')
                                    ->options(\App\Models\CabangDinas::pluck('nama', 'id'))
                                    ->required(),

                                Forms\Components\TextInput::make('kelas')
                                    ->label('Kelas')
                                    ->required(),
                            ]),
                        ]),

                    // STEP 4: Lampiran
                    Forms\Components\Wizard\Step::make('Lampiran')
                        ->schema([
                            Forms\Components\FileUpload::make('fc_ktp')
                                ->label('Scan KTP')
                                ->disk('public')
                                ->directory('lampiran-peserta')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(2048)
                                ->required(),
                            Forms\Components\FileUpload::make('fc_ijazah')
                                ->label('Scan Ijazah')
                                ->disk('public')
                                ->directory('lampiran-peserta')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(2048)
                                ->required(),
                            Forms\Components\FileUpload::make('fc_surat_tugas')
                                ->label('Surat Tugas')
                                ->disk('public')
                                ->directory('lampiran-peserta')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(2048),
                            Forms\Components\FileUpload::make('fc_surat_sehat')
                                ->label('Surat Sehat')
                                ->disk('public')
                                ->directory('lampiran-peserta')
                                ->acceptedFileTypes(['application/pdf', 'image/*'])
                                ->maxSize(2048),
                            Forms\Components\FileUpload::make('pas_foto')
                                ->label('Pas Foto')
                                ->disk('public')
                                ->directory('lampiran-peserta')
                                ->image()
                                ->maxSize(2048)
                                ->required(),
                            Forms\Components\TextInput::make('nomor_surat_tugas')
                                ->label('Nomor Surat Tugas'),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_registrasi')
                    ->label('No. Registrasi')
                    ->icon('heroicon-o-identification')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Nama Peserta')
                    ->icon('heroicon-o-user')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->icon('heroicon-o-academic-cap')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '-';
                        $words = explode(' ', $state);
                        $chunks = array_chunk($words, 4);
                        return implode('<br>', array_map(fn($chunk) => implode(' ', $chunk), $chunks));
                    })
                    ->html()
                    ->searchable()
                    ->sortable(),

                // ✅ kolom KELAS di tabel
                Tables\Columns\TextColumn::make('kelas')
                    ->label('Kelas')
                    ->icon('heroicon-o-building-office-2')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status_pendaftaran')
                    ->label('Status')
                    ->icon('heroicon-o-shield-check')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Verifikasi' => 'info',
                        'Diterima' => 'success',
                        'Ditolak' => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->label('Tanggal Daftar')
                    ->icon('heroicon-o-calendar-days')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pendaftaran')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'verifikasi' => 'Verifikasi',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ]),

                Tables\Filters\SelectFilter::make('pelatihan')
                    ->label('Filter Pelatihan')
                    ->relationship('pelatihan', 'nama_pelatihan'),

                Tables\Filters\SelectFilter::make('kompetensi')
                    ->label('Kompetensi')
                    ->options(fn() => \App\Models\Kompetensi::pluck('nama_kompetensi', 'id'))
                    ->query(function (Builder $query, array $data) {
                        if (empty($data['value'])) return $query;

                        return $query->whereHas('kompetensiPelatihan', function ($q) use ($data) {
                            $q->where('kompetensi_id', $data['value']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }
}
