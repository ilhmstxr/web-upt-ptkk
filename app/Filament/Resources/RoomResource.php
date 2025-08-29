<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomResource extends Resource
{
    // Properti resource tetap sama
    protected static ?string $model = Room::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Manajemen Kamar & Peserta';
    protected static ?string $navigationLabel = 'Manajemen Kamar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kamar')
                    ->required(),
                Forms\Components\TextInput::make('number') // Mengubah 'nomor_kamar' menjadi 'number'
                    ->label('Nomor Kamar')
                    ->required()
                    ->numeric() // Menambahkan validasi numeric karena di migrasi Anda integer
                    ->unique(ignoreRecord: true), // Menjamin nomor kamar unik
                Forms\Components\Select::make('section')
                    ->label('Bagian')
                    ->options([
                        'atas' => 'Atas',
                        'bawah' => 'Bawah',
                    ])
                    ->nullable(),
                
                Forms\Components\TextInput::make('capacity')
                    ->label('Kapasitas (jumlah bed)')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
                
                Forms\Components\Select::make('assigned_for')
                    ->label('Diperuntukkan untuk')
                    ->options([
                        'instruktur' => 'Instruktur',
                        'laki-laki' => 'Laki-laki',
                        'perempuan' => 'Perempuan',
                    ])
                    ->nullable(),

                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->rows(3)
                    ->maxLength(255)
                    ->nullable(),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kamar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('number') // Mengubah 'nomor_kamar' menjadi 'number'
                    ->label('Nomor Kamar')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('section')
                    ->label('Bagian')
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Kapasitas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assigned_for')
                    ->label('Diperuntukkan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('registrations_count')
                    ->label('Jumlah Terisi')
                    ->counts('registrations')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}