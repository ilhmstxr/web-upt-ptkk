<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelatihanResource\Pages;
use App\Filament\Resources\PelatihanResource\RelationManagers;
use App\Models\Pelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PelatihanResource extends Resource
{
    protected static ?string $model = Pelatihan::class;
    protected static ?string $navigationLabel = 'Pelatihan';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Pendaftaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pelatihan')
                    ->schema([
                        Forms\Components\Select::make('bidang')
                            ->label('Bidang Keahlian')
                            ->relationship('bidang', 'nama_bidang')
                            ->multiple() // ✅ bisa pilih banyak bidang
                            ->preload()
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('nama_pelatihan')
                            ->label('Nama Pelatihan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('jenis_program')
                            ->label('Jenis Program')
                            ->options([
                                'reguler' => 'Reguler',
                                'akselerasi' => 'Akselerasi',
                                'mtu' => 'MTU',
                            ])
                            ->default('reguler')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'belum dimulai' => 'Belum Dimulai',
                                'aktif' => 'Aktif',
                                'selesai' => 'Selesai',
                            ])
                            ->default('belum dimulai')
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_mulai')
                            ->label('Tanggal Mulai')
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_selesai')
                            ->label('Tanggal Selesai')
                            ->required(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bidang.nama_bidang')
                    ->label('Bidang Keahlian')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state, $record) =>
                        $record->bidang->pluck('nama_bidang')->join(', ')
                    ),

                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('jenis_program')
                    ->label('Jenis Program')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
            RelationManagers\PesertasRelationManager::class,
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
