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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Informasi Dasar')
                        ->schema([
                            Forms\Components\Select::make('jenis_program')
                                ->options([
                                    'reguler' => 'Reguler',
                                    'mtu' => 'MTU',
                                    'akselerasi' => 'Akselerasi',
                                ])
                                ->required(),
                            Forms\Components\TextInput::make('nama_pelatihan')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('batch')
                                ->label('Angkatan')
                                ->numeric()
                                ->default(1)
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_mulai')
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_selesai')
                                ->required(),
                        ]),
                    Forms\Components\Wizard\Step::make('Kurikulum & Jadwal')
                        ->schema([
                            Forms\Components\Repeater::make('bidangPelatihan')
                                ->relationship()
                                ->schema([
                                    Forms\Components\Select::make('bidang_id')
                                        ->relationship('bidang', 'nama_bidang')
                                        ->required()
                                        ->label('Materi / Bidang'),
                                    // Forms\Components\FileUpload::make('file_modul')
                                    //     ->directory('modul-pelatihan')
                                    //     ->acceptedFileTypes(['application/pdf'])
                                    //     ->label('Modul (PDF)'),
                                    Forms\Components\Select::make('instruktur_id')
                                        ->relationship('instruktur', 'nama_instruktur')
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
                    ->description(fn ($record) => $record->jenis_program . ' • Batch ' . $record->batch)
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

                Tables\Columns\TextColumn::make('kuota_peserta')
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
