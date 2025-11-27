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
                            Forms\Components\TextInput::make('nama_pelatihan')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('jenis_program')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('batch')
                                ->numeric()
                                ->required(),
                            Forms\Components\TextInput::make('quota')
                                ->numeric()
                                ->label('Kuota Peserta')
                                ->required(),
                            Forms\Components\Select::make('instansi_id')
                                ->relationship('instansi', 'asal_instansi')
                                ->required(),
                            Forms\Components\FileUpload::make('gambar')
                                ->image()
                                ->directory('pelatihan-images'),
                            Forms\Components\RichEditor::make('deskripsi')
                                ->columnSpanFull(),
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
                                    Forms\Components\Select::make('metode')
                                        ->options([
                                            'Online' => 'Online',
                                            'Offline' => 'Offline',
                                            'Hybrid' => 'Hybrid',
                                        ])
                                        ->required(),
                                    Forms\Components\FileUpload::make('file_modul')
                                        ->directory('modul-pelatihan')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->label('Modul (PDF)'),
                                    Forms\Components\DatePicker::make('tanggal')
                                        ->required(),
                                    Forms\Components\TimePicker::make('jam_mulai')
                                        ->required(),
                                    Forms\Components\TimePicker::make('jam_selesai')
                                        ->required(),
                                    Forms\Components\Select::make('instruktur_id')
                                        ->relationship('instruktur', 'nama_instruktur') // Assuming 'nama_instruktur' exists
                                        ->label('Instruktur')
                                        ->searchable(),
                                    Forms\Components\TextInput::make('lokasi')
                                        ->label('Lokasi / Ruangan'),
                                ])
                                ->columns(2)
                                ->defaultItems(1)
                                ->addActionLabel('Tambah Sesi'),
                        ]),
                    Forms\Components\Wizard\Step::make('Durasi Pelatihan')
                        ->schema([
                            Forms\Components\DatePicker::make('tanggal_mulai')
                                ->required(),
                            Forms\Components\DatePicker::make('tanggal_selesai')
                                ->required(),
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->circular(),
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batch')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quota')
                    ->numeric()
                    ->label('Kuota'),
                Tables\Columns\TextColumn::make('bidang_pelatihan_count')
                    ->counts('bidangPelatihan')
                    ->label('Sesi'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Mendatang' => 'warning',
                        'Aktif' => 'success',
                        'Selesai' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('instansi')
                    ->relationship('instansi', 'asal_instansi'),
            ])
            ->actions([
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
            'edit' => Pages\EditPelatihan::route('/{record}/edit'),
        ];
    }
}
