<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrukturResource\Pages;
use App\Filament\Resources\InstrukturResource\RelationManagers;
use App\Models\Instruktur;
use App\Models\Bidang; // Import model Bidang
use App\Models\Pelatihan; // Import model Pelatihan
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class InstrukturResource extends Resource
{
    protected static ?string $model = Instruktur::class;

    protected static ?string $navigationLabel   = 'Instruktur';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Pendaftaran';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelatihan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('bidang_id')
                            ->label('Kompetensi / Bidang')
                            ->options(Bidang::all()->pluck('nama_bidang', 'id')) // Asumsi nama_bidang
                            ->searchable()
                            ->required(),
                        Forms\Components\Select::make('pelatihan_id') // Ganti dari 'pelatihan' ke 'pelatihan_id'
                            ->label('Pelatihan')
                            ->options(Pelatihan::all()->pluck('nama_pelatihan', 'id')) // Asumsi nama_pelatihan
                            ->searchable()
                            ->required(),
                    ]),
                Forms\Components\Section::make('Data Pribadi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_gelar')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('tempat_lahir')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('tgl_lahir')
                            ->required(),
                        Forms\Components\Select::make('jenis_kelamin')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('agama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alamat_rumah')
                            ->required()
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Informasi Kontak & Dokumen')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_hp')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('instansi')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('npwp')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nik')
                            ->required()
                            ->maxLength(255),
                    ]),
                Forms\Components\Section::make('Informasi Keuangan & Pendidikan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nama_bank')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('no_rekening')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pendidikan_terakhir')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('pengalaman_kerja')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_gelar')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bidang.nama_bidang') // Tampilkan nama bidang dari relasi
                    ->label('Kompetensi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan') // Tampilkan nama pelatihan dari relasi
                    ->label('Pelatihan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('no_hp'),
                Tables\Columns\TextColumn::make('instansi')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Cetak Biodata')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Instruktur $record): string => route('instruktur.cetak', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Cetak Massal')
                        ->icon('heroicon-o-printer')
                        ->url(fn(): string => route('pendaftaran.generateMassal')) // Arahkan ke route cetak massal
                        ->openUrlInNewTab()
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListInstrukturs::route('/'),
            'create' => Pages\CreateInstruktur::route('/create'),
            'edit' => Pages\EditInstruktur::route('/{record}/edit'),
        ];
    }
}
