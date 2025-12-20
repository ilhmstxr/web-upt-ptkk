<?php

namespace App\Filament\Clusters\Pelatihan\Resources;

use App\Filament\Clusters\Pelatihan;
use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;
use App\Models\Pelatihan as PelatihanModel;
use App\Models\Bidang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

final class PelatihanResource extends Resource
{
    protected static ?string $model = PelatihanModel::class;

    protected static ?string $cluster = Pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Resource tidak tampil di sidebar, diakses via cluster/tabs.
     */
    protected static bool $shouldRegisterNavigation = false;

    /**
     * Form (Wizard) untuk create / edit.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make(self::getWizardSteps())
                    ->columnSpanFull(),
            ]);
    }

    public static function getWizardSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('Informasi Dasar')
                ->schema([
                    Forms\Components\Section::make('Detail Pelatihan')
                        ->description('Informasi utama mengenai pelatihan')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('nama_pelatihan')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),

                                    Forms\Components\Select::make('jenis_program')
                                        ->options([
                                            'reguler' => 'Reguler',
                                            'mtu' => 'MTU',
                                            'akselerasi' => 'Akselerasi',
                                        ])
                                        ->required(),

                                    Forms\Components\Select::make('status')
                                        ->options([
                                            'belum dimulai' => 'Belum Dimulai',
                                            'aktif' => 'Aktif',
                                            'selesai' => 'Selesai',
                                        ])
                                        ->required()
                                        ->default('belum dimulai'),

                                    Forms\Components\TextInput::make('angkatan')
                                        ->label('Angkatan')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),

                                    Forms\Components\DatePicker::make('tanggal_mulai')
                                        ->required()
                                        ->minDate(now()->startOfDay())
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                            self::updateStatus($get, $set);
                                        }),

                                    Forms\Components\DatePicker::make('tanggal_selesai')
                                        ->required()
                                        ->minDate(fn(Forms\Get $get) => $get('tanggal_mulai') ? \Carbon\Carbon::parse($get('tanggal_mulai')) : now()->startOfDay())
                                        ->live()
                                        ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                            self::updateStatus($get, $set);
                                        }),
                                    Forms\Components\TextInput::make('lokasi')
                                        ->label('Lokasi Pelatihan (Default)')
                                        ->placeholder('Contoh: UPT-PTKK Surabaya')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('nama_cp')
                                        ->label('Nama CP (Contact Person)')
                                        ->placeholder('Contoh: Sdri. Admin')
                                        ->maxLength(100),

                                    Forms\Components\TextInput::make('no_cp')
                                        ->label('No. CP (Contact Person)')
                                        ->placeholder('Contoh: 08123456789')
                                        ->tel()
                                        ->maxLength(20),
                                ]),
                        ]),
                ]),
            Forms\Components\Wizard\Step::make('Kurikulum & Jadwal')
                ->schema([
                    Forms\Components\Section::make('Materi & Jadwal')
                        ->description('Atur kurikulum dan jadwal pelatihan')
                        ->schema([
                            Forms\Components\Repeater::make('kompetensi_items')
                                ->label('Jadwal Kompetensi')
                                ->schema([
                                    Forms\Components\Select::make('kompetensi_id')
                                        ->label('Materi / Kompetensi')
                                        ->options(\App\Models\Kompetensi::pluck('nama_kompetensi', 'id'))
                                        ->searchable()
                                        ->required()
                                        ->columnSpan(2),

                                    Forms\Components\Select::make('instruktur_id')
                                        ->label('Nama Instruktur')
                                        ->options(\App\Models\Instruktur::pluck('nama', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->multiple() // Allow multiple selection
                                        ->required()
                                        ->createOptionForm([
                                            Forms\Components\TextInput::make('nama')
                                                ->required()
                                                ->maxLength(255),
                                            Forms\Components\Select::make('kompetensi_id')
                                                ->label('Kompetensi')
                                                ->options(\App\Models\Kompetensi::pluck('nama_kompetensi', 'id'))
                                                ->searchable()
                                                ->required(),
                                        ])
                                        ->createOptionUsing(function (array $data) {
                                            $data['user_id'] = auth()->id();
                                            $data['tempat_lahir'] = '-';
                                            $data['tgl_lahir'] = '2000-01-01'; // Default dummy date
                                            $data['jenis_kelamin'] = '-';
                                            $data['agama'] = '-';
                                            $data['no_hp'] = '-';
                                            return \App\Models\Instruktur::create($data)->id;
                                        }),

                                    Forms\Components\TextInput::make('lokasi')
                                        ->label('Lokasi / Ruangan')
                                        ->default(fn(Forms\Get $get) => $get('../../lokasi')) // Use parent location as default
                                        ->required(),
                                ])
                                ->columns(2)
                                ->defaultItems(1)
                                ->addActionLabel('Tambah Sesi')
                                ->itemLabel(fn(array $state): ?string => \App\Models\Kompetensi::find($state['kompetensi_pelatihan_id'] ?? null)?->nama_kompetensi),
                        ]),
                ]),
            Forms\Components\Wizard\Step::make('Konten Halaman Pendaftaran')
                ->description('Teks yang akan tampil di accordion halaman daftar pelatihan')
                ->schema([
                    Forms\Components\RichEditor::make('syarat_ketentuan')
                        ->label('Syarat & Ketentuan Peserta')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('jadwal_text')
                        ->label('Informasi Jadwal (untuk accordion)')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('lokasi_text')
                        ->label('Lokasi Pelaksanaan (untuk accordion)')
                        ->columnSpanFull(),
                ]),
        ];
    }

    /**
     * Table listing di resource index.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60)
                    ->tooltip(fn($record) => $record->nama_pelatihan)
                    ->description(
                        fn($record) =>
                        trim((string) ($record->jenis_program ?? '')) !== ''
                            ? ($record->jenis_program . ' • Angkatan ' . ($record->angkatan ?? '—'))
                            : ('Angkatan ' . ($record->angkatan ?? '—'))
                    )
                    ->weight('medium'),

                // Status dengan warna badge
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'aktif' => 'success',
                        'belum dimulai' => 'info',
                        'selesai' => 'gray',
                        default => 'gray',
                    })
                    ->alignCenter(),

                // Hitungan peserta (counts)
                Tables\Columns\TextColumn::make('pendaftaran_pelatihan_count')
                    ->label('Total Peserta')
                    ->counts('pendaftaranPelatihan')
                    ->sortable()
                    ->alignCenter(),

                // Tanggal mulai (dengan deskripsi tanggal selesai bila ada)
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(
                        fn($record) =>
                        $record->tanggal_selesai
                            ? 'S/d ' . $record->tanggal_selesai->format('d M Y')
                            : null
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'belum dimulai' => 'Belum Dimulai',
                        'aktif' => 'Aktif',
                        'selesai' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Relations (RelationManagers) — tambahkan bila perlu.
     *
     * @return array<string>
     */
    public static function getRelations(): array
    {
        return [
            // e.g. RelationManagers\PendafataranPelatihansRelationManager::class,
        ];
    }

    /**
     * Halaman CRUD untuk resource ini.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelatihans::route('/'),
            'create' => Pages\CreatePelatihan::route('/create'),
            'view' => Pages\ViewPelatihan::route('/{record}'),
            'edit' => Pages\EditPelatihan::route('/{record}/edit'),
            'view-kompetensi' => Pages\ViewKompetensiPelatihan::route('/{record}/kompetensi/{kompetensi_id}'),
            'view-monev-detail' => Pages\ViewMonevDetail::route('/{record}/kompetensi/{kompetensi_id}/monev'),
        ];
    }
    public static function updateStatus(Forms\Get $get, Forms\Set $set)
    {
        $mulai = $get('tanggal_mulai');
        $selesai = $get('tanggal_selesai');

        if (!$mulai || !$selesai) {
            return;
        }

        $now = now()->startOfDay();
        $start = \Carbon\Carbon::parse($mulai)->startOfDay();
        $end = \Carbon\Carbon::parse($selesai)->endOfDay();

        if ($now->lt($start)) {
            $set('status', 'belum dimulai');
        } elseif ($now->between($start, $end)) {
            $set('status', 'aktif');
        } else {
            $set('status', 'selesai');
        }
    }
}
