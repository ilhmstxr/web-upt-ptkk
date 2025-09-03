<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstansiResource\Pages;
use App\Models\Instansi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstansiResource extends Resource
{
    protected static ?string $model = Instansi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('asal_instansi')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('alamat_instansi')
                ->required()
                ->columnSpanFull(),

            // ubah bidang_keahlian jadi Select relasi
            Forms\Components\Select::make('bidang_keahlian_id')
                ->label('Bidang Keahlian')
                ->relationship('bidangKeahlian', 'nama')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('kelas')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('cabang_dinas_id')
                ->label('Cabang Dinas')
                ->relationship('cabangDinas', 'nama')
                ->searchable()
                ->preload()
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('asal_instansi')
                ->searchable(),

            Tables\Columns\TextColumn::make('bidangKeahlian.nama')
                ->label('Bidang Keahlian')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('cabangDinas.nama')
                ->label('Cabang Dinas')
                ->sortable()
                ->searchable(),

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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInstansis::route('/'),
            'create' => Pages\CreateInstansi::route('/create'),
            'edit' => Pages\EditInstansi::route('/{record}/edit'),
        ];
    }
}
