<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaResource\Pages;
use App\Models\Peserta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PesertaResource extends Resource
{
    protected static ?string $model = Peserta::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pendaftaran')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('pelatihan_id')
                            ->relationship('pelatihan', 'nama_pelatihan')
                            ->required(),
                        Forms\Components\Select::make('instansi_id')
                            ->relationship('instansi', 'asal_instansi')
                            ->required(),
                    ]),

                Forms\Components\Section::make('Data Diri Peserta')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama')->required(),
                        Forms\Components\TextInput::make('nik')->required()->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('tempat_lahir')->required(),
                        Forms\Components\DatePicker::make('tanggal_lahir')->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])->required(),
                        Forms\Components\TextInput::make('agama')->required(),
                        Forms\Components\TextInput::make('no_hp')->required()->tel(),
                        Forms\Components\TextInput::make('email')->required()->email()->unique(ignoreRecord: true),
                        Forms\Components\Textarea::make('alamat')->required()->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Lampiran Berkas')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('lampiran.fc_ktp')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('KTP')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.fc_ijazah')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Ijazah')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.fc_surat_sehat')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Surat Sehat')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.pas_foto')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Pas Foto')
                            ->required(),

                        Forms\Components\FileUpload::make('lampiran.fc_surat_tugas')
                            ->disk('public')
                            ->directory(fn ($record) => 'lampiran/' . \Str::slug($record->nama))
                            ->label('Surat Tugas')
                            ->nullable(),

                        Forms\Components\TextInput::make('lampiran.no_surat_tugas')
                            ->label('Nomor Surat Tugas')
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')->sortable(),
                Tables\Columns\TextColumn::make('instansi.asal_instansi')->sortable(),
                Tables\Columns\TextColumn::make('email'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesertas::route('/'),
            'create' => Pages\CreatePeserta::route('/create'),
            'edit' => Pages\EditPeserta::route('/{record}/edit'),
        ];
    }
}
