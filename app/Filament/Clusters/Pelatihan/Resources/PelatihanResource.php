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
                Forms\Components\Wizard::make([
                    // STEP 1 — Informasi Dasar
                    Forms\Components\Wizard\Step::make('Informasi Dasar')
                        ->schema([
                            Forms\Components\Section::make('Detail Pelatihan')
                                ->description('Informasi utama mengenai pelatihan.')
                                ->schema([
                                    // Baris atas: nama pelatihan full width
                                    Forms\Components\TextInput::make('nama_pelatihan')
                                        ->label('Nama Pelatihan')
                                        ->required()
                                        ->maxLength(255)
                                        ->columnSpanFull(),

                                    // Baris berikutnya: 2 kolom rapi
                                    Forms\Components\Select::make('jenis_program')
                                        ->label('Jenis Program')
                                        ->options([
                                            'reguler'    => 'Reguler',
                                            'mtu'        => 'MTU',
                                            'akselerasi' => 'Akselerasi',
                                        ])
                                        ->required(),

                                    Forms\Components\Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'Pendaftaran Buka' => 'Pendaftaran Buka',
                                            'Sedang Berjalan'  => 'Sedang Berjalan',
                                            'Selesai'          => 'Selesai',
                                            'Mendatang'        => 'Mendatang',
                                        ])
                                        ->required()
                                        ->default('Mendatang'),

                                    Forms\Components\TextInput::make('angkatan')
                                        ->label('Angkatan')
                                        ->numeric()
                                        ->default(1)
                                        ->required(),

                                    Forms\Components\DatePicker::make('tanggal_mulai')
                                        ->label('Tanggal Mulai')
                                        ->required(),

                                    Forms\Components\DatePicker::make('tanggal_selesai')
                                        ->label('Tanggal Selesai')
                                        ->required(),
                                ])
                                // 2 kolom responsif di dalam section
                                ->columns([
                                    'default' => 1,
                                    'md'      => 2,
                                ]),
                        ]),

                    // STEP 2 — Kurikulum & Jadwal
                    Forms\Components\Wizard\Step::make('Kurikulum & Jadwal')
                        ->schema([
                            Forms\Components\Section::make('Materi & Jadwal')
                                ->description('Atur kurikulum, instruktur, dan lokasi pelatihan.')
                                ->schema([
                                    Forms\Components\Repeater::make('bidangPelatihan')
                                        ->relationship('bidangPelatihan')
                                        ->schema([
                                            // Bidang dibuat lebih lebar (2 kolom)
                                            Forms\Components\Select::make('bidang_id')
                                                ->label('Materi / Bidang')
                                                ->relationship('bidang', 'nama_bidang')
                                                ->required()
                                                ->searchable()
                                                ->preload()
                                                ->columnSpan(2),

                                            Forms\Components\Select::make('instruktur_id')
                                                ->label('Instruktur')
                                                ->relationship('instruktur', 'nama')
                                                ->searchable()
                                                ->preload(),

                                            Forms\Components\TextInput::make('lokasi')
                                                ->label('Lokasi / Ruangan')
                                                ->default('UPT-PTKK'),
                                        ])
                                        // layout dalam repeater: 3 kolom di layar besar
                                        ->columns([
                                            'default' => 1,
                                            'md'      => 3,
                                        ])
                                        ->defaultItems(1)
                                        ->addActionLabel('Tambah Sesi')
                                        ->itemLabel(function (array $state): ?string {
                                            // Tampilkan nama bidang bila ada bidang_id; fallback ke id
                                            if (! empty($state['bidang_id'])) {
                                                $bidang = Bidang::find($state['bidang_id']);
                                                return $bidang ? $bidang->nama_bidang : ('ID: ' . $state['bidang_id']);
                                            }
                                            return null;
                                        })
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ]),

                    // STEP 3 — Konten Halaman Pendaftaran (Rich text untuk accordion)
                    Forms\Components\Wizard\Step::make('Konten Halaman Pendaftaran')
                        ->description('Teks yang akan tampil di accordion halaman daftar pelatihan.')
                        ->schema([
                            Forms\Components\Section::make('Konten Informasi')
                                ->schema([
                                    Forms\Components\RichEditor::make('syarat_ketentuan')
                                        ->label('Syarat & Ketentuan Peserta')
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                            'undo',
                                            'redo',
                                        ])
                                        ->columnSpanFull(),

                                    Forms\Components\RichEditor::make('jadwal_text')
                                        ->label('Informasi Jadwal (untuk accordion)')
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                            'undo',
                                            'redo',
                                        ])
                                        ->columnSpanFull(),

                                    Forms\Components\RichEditor::make('lokasi_text')
                                        ->label('Lokasi Pelaksanaan (untuk accordion)')
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'bulletList',
                                            'orderedList',
                                            'link',
                                            'undo',
                                            'redo',
                                        ])
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ]),
                ])
                ->columnSpanFull(),
            ]);
    }

    /**
     * Table listing di resource index.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Cover image
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Cover')
                    ->square()
                    ->size(60)
                    ->defaultImageUrl('https://via.placeholder.com/150'),

                // Nama pelatihan + deskripsi singkat
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->nama_pelatihan)
                    ->description(fn ($record) =>
                        trim((string) ($record->jenis_program ?? '')) !== ''
                            ? ($record->jenis_program . ' • Angkatan ' . ($record->angkatan ?? '—'))
                            : ('Angkatan ' . ($record->angkatan ?? '—'))
                    )
                    ->weight('medium'),

                // Status dengan warna badge
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Sedang Berjalan'  => 'success',
                        'Pendaftaran Buka' => 'info',
                        'Selesai'          => 'gray',
                        'Mendatang'        => 'warning',
                        default            => 'secondary',
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
                    ->description(fn ($record) =>
                        $record->tanggal_selesai
                            ? 'S/d ' . $record->tanggal_selesai->format('d M Y')
                            : null
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('instansi')
                    ->relationship('instansi', 'asal_instansi'),
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
            'index'             => Pages\ListPelatihans::route('/'),
            'create'            => Pages\CreatePelatihan::route('/create'),
            'view'              => Pages\ViewPelatihan::route('/{record}'),
            'edit'              => Pages\EditPelatihan::route('/{record}/edit'),
            'view-bidang'       => Pages\ViewBidangPelatihan::route('/{record}/bidang/{bidang_id}'),
            'view-monev-detail' => Pages\ViewMonevDetail::route('/{record}/bidang/{bidang_id}/monev'),
        ];
    }
}
