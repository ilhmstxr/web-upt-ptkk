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
                // ======================== STEP 1: INFORMASI DASAR ========================
                Forms\Components\Wizard\Step::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\Select::make('jenis_program')
                            ->options([
                                'reguler'     => 'Reguler',
                                'mtu'         => 'MTU',
                                'akselerasi'  => 'Akselerasi',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('nama_pelatihan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('angkatan')
                            ->label('Angkatan')
                            ->numeric()
                            ->default(1)
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai Pelatihan')
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai Pelatihan')
                            ->required(),

                        // ⬇️ BARU: status untuk menentukan aktif / selesai di frontend
                        Forms\Components\Select::make('status')
                            ->label('Status Program')
                            ->options([
                                'aktif'   => 'Aktif (pendaftaran dibuka)',
                                'selesai' => 'Selesai',
                            ])
                            ->default('aktif')
                            ->required(),
                    ]),

                // ======================== STEP 2: KURIKULUM & JADWAL ========================
                Forms\Components\Wizard\Step::make('Kurikulum & Jadwal')
                    ->schema([
                        Forms\Components\Repeater::make('bidangPelatihan')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('bidang_id')
                                    ->relationship('bidang', 'nama_bidang')
                                    ->required()
                                    ->label('Materi / Bidang'),

                                Forms\Components\Select::make('instruktur_id')
                                    ->relationship('instruktur', 'nama')
                                    ->label('Instruktur')
                                    ->searchable(),

                                Forms\Components\TextInput::make('lokasi')
                                    ->label('Lokasi / Ruangan')
                                    ->default('UPT-PTKK'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Tambah Sesi'),
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
                Tables\Columns\ImageColumn::make('gambar')
                    ->label('Cover')
                    ->square()
                    ->size(60)
                    ->defaultImageUrl('https://via.placeholder.com/150'),

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
                        'Sedang Berjalan' => 'success',
                        'Pendaftaran Buka' => 'info',
                        'Selesai' => 'gray',
                        default => 'gray',
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('jumlah_peserta')
                    ->label('Kuota')
                    ->numeric()
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
            'view-bidang' => Pages\ViewBidangPelatihan::route('/{record}/bidang/{bidang_id}'),
        ];
    }
}
