<?php

namespace App\Filament\Clusters\Kesiswaan\Resources;

use App\Filament\Clusters\Kesiswaan;
use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource\Pages;
use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource\RelationManagers;
use App\Models\Instruktur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InstrukturResource extends Resource
{
    protected static ?string $model = Instruktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $cluster = Kesiswaan::class;

    protected static ?string $navigationLabel = 'Data Instruktur';

    protected static ?string $slug = 'instruktur';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pribadi')
                            ->schema([
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Akun User'),
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->numeric()
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->maxLength(255),
                                Forms\Components\DatePicker::make('tgl_lahir')
                                    ->label('Tanggal Lahir'),
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    ]),
                                Forms\Components\Select::make('agama')
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                    ]),
                                Forms\Components\TextInput::make('no_hp')
                                    ->tel()
                                    ->label('No. HP')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('alamat_rumah')
                                    ->columnSpanFull(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pekerjaan')
                            ->schema([
                                Forms\Components\Select::make('bidang_id')
                                    ->relationship('bidang', 'nama_bidang')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('nama_bidang')
                                            ->required()
                                            ->maxLength(255),
                                    ]),
                                Forms\Components\TextInput::make('instansi')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('npwp')
                                    ->label('NPWP')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('nama_bank')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('no_rekening')
                                    ->maxLength(255),
                                Forms\Components\Select::make('pendidikan_terakhir')
                                    ->options([
                                        'SMA/SMK' => 'SMA/SMK',
                                        'D3' => 'D3',
                                        'S1' => 'S1',
                                        'S2' => 'S2',
                                        'S3' => 'S3',
                                    ]),
                                Forms\Components\Textarea::make('pengalaman_kerja')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bidang.nama_bidang')
                    ->label('Bidang')
                    ->sortable(),
                Tables\Columns\TextColumn::make('instansi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No. HP')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bidang')
                    ->relationship('bidang', 'nama_bidang'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\PelatihanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstrukturs::route('/'),
            'create' => Pages\CreateInstruktur::route('/create'),
            'edit' => Pages\EditInstruktur::route('/{record}/edit'),
        ];
    }
}
