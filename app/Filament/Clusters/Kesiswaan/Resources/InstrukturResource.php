<?php

namespace App\Filament\Clusters\Kesiswaan\Resources;

use App\Filament\Clusters\Kesiswaan;
use App\Filament\Clusters\Kesiswaan\Resources\InstrukturResource\Pages;
use App\Models\Instruktur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstrukturResource extends Resource
{
    protected static ?string $model = Instruktur::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $cluster = Kesiswaan::class;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(3)
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Pribadi')
                            ->schema([
                                Forms\Components\TextInput::make('nama')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('tempat_lahir')
                                            ->maxLength(255),
                                        Forms\Components\DatePicker::make('tgl_lahir')
                                            ->label('Tanggal Lahir'),
                                    ]),
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ]),
                                Forms\Components\TextInput::make('agama')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('no_hp')
                                    ->label('No HP')
                                    ->tel()
                                    ->maxLength(255),
                            ])->columns(2),

                        Forms\Components\Section::make('Lampiran')
                            ->relationship('lampiran')
                            ->schema([
                                Forms\Components\FileUpload::make('cv')
                                    ->label('CV')
                                    ->directory('instruktur/cv')
                                    ->downloadable()
                                    ->openable(),
                                Forms\Components\FileUpload::make('ktp')
                                    ->label('KTP')
                                    ->directory('instruktur/ktp')
                                    ->downloadable()
                                    ->openable(),
                                Forms\Components\FileUpload::make('ijazah')
                                    ->label('Ijazah')
                                    ->directory('instruktur/ijazah')
                                    ->downloadable()
                                    ->openable(),
                                Forms\Components\FileUpload::make('sertifikat_kompetensi')
                                    ->label('Sertifikat Kompetensi')
                                    ->directory('instruktur/sertifikat')
                                    ->downloadable()
                                    ->openable(),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Akun & Kompetensi')
                            ->schema([
                                Forms\Components\Select::make('kompetensi_id')
                                    ->relationship('kompetensi', 'nama_kompetensi')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique('users', 'email', ignoreRecord: true),
                                Forms\Components\TextInput::make('password')
                                    ->label('Password')
                                    ->password()
                                    ->dehydrated(fn($state) => filled($state))
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->maxLength(255),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kompetensi.nama_kompetensi')
                    ->label('Kompetensi')
                    ->sortable(),


                Tables\Columns\TextColumn::make('no_hp')
                    ->label('No HP')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_kelamin'),
            ])
            ->filters([
                //
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
            InstrukturResource\RelationManagers\PelatihanRelationManager::class,
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
