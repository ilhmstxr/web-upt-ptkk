<?php

namespace App\Filament\Clusters\Pelatihan\Resources;

use App\Filament\Clusters\Pelatihan;
use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\Pages;
use App\Filament\Clusters\Pelatihan\Resources\PelatihanResource\RelationManagers;
use App\Models\Pelatihan as PelatihanModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelatihanResource extends Resource
{
    protected static ?string $model = PelatihanModel::class;

    protected static ?string $cluster = Pelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Hide from sidebar navigation (accessed via tabs only)
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
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
                                                ->required(),
                                            
                                            Forms\Components\DatePicker::make('tanggal_selesai')
                                                ->required(),
                                        ]),
                                ]),
                        ]),
                    Forms\Components\Wizard\Step::make('Kurikulum & Jadwal')
                        ->schema([
                            Forms\Components\Section::make('Materi & Jadwal')
                                ->description('Atur kurikulum dan jadwal pelatihan')
                                ->schema([
                                    Forms\Components\Repeater::make('kompetensiPelatihan')
                                        ->relationship()
                                        ->schema([
                                            Forms\Components\Select::make('kompetensi_id')
                                                ->relationship('kompetensi', 'nama_kompetensi')
                                                ->required()
                                                ->label('Materi / Kompetensi')
                                                ->columnSpan(2),
                                            
                                            Forms\Components\Select::make('instruktur_id')
                                                ->label('Nama Instruktur')
                                                ->relationship('instruktur', 'nama')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->reactive()
                                                ->afterStateUpdated(fn ($state, callable $set) => $set('nama_instruktur', \App\Models\Instruktur::find($state)?->nama))
                                                ->createOptionForm([
                                                    Forms\Components\TextInput::make('nama')
                                                        ->required()
                                                        ->maxLength(255),
                                                ]),

                                            Forms\Components\Hidden::make('nama_instruktur'),
                                            
                                            Forms\Components\TextInput::make('lokasi')
                                                ->label('Lokasi / Ruangan')
                                                ->default('UPT-PTKK'),
                                        ])
                                        ->columns(2)
                                        ->defaultItems(1)
                                        ->addActionLabel('Tambah Sesi')
                                        ->itemLabel(fn (array $state): ?string => $state['kompetensi_id'] ?? null),
                                ]),
                        ]),
                         // ======================== STEP 3: KONTEN HALAMAN PENDAFTARAN ========================
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
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(100)
                    ->tooltip(fn ($record) => $record->nama_pelatihan)
                    ->description(fn ($record) => $record->jenis_program . ' • Angkatan ' . $record->angkatan)
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'belum dimulai' => 'info',
                        'selesai' => 'gray',
                        default => 'gray',
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pendaftaran_pelatihan_count')
                    ->counts('pendaftaranPelatihan')
                    ->label('Total Peserta')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Jadwal')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn ($record) => $record->tanggal_selesai ? 'S/d ' . $record->tanggal_selesai->format('d M Y') : null),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('instansi')
                    ->relationship('instansi', 'asal_instansi'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

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
}