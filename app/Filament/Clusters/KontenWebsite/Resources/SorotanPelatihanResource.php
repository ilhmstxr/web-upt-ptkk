<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\Pages;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\RelationManagers;
use App\Models\SorotanPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SorotanPelatihanResource extends Resource
{
    protected static ?string $model = SorotanPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $cluster = KontenWebsite::class;
    
    protected static ?string $navigationLabel = "Sorotan Pelatihan (4-8 Foto)";

    protected static ?string $modelLabel = 'Sorotan Pelatihan (4-8 Foto)'; // Label tunggal (misal: "Tambah Sorotan Pelatihan")

    protected static ?string $pluralModelLabel = 'Sorotan Pelatihan (4-8 Foto)'; // Label jamak/judul halaman (mengganti "Sorotan Pelatihans")

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Judul')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('is_published')->label('Publish'),

            // Foto: maksimal 8
            Forms\Components\Repeater::make('fotos')
                ->relationship('fotos')
                ->schema([
                    Forms\Components\FileUpload::make('path')
                        ->image()
                        ->directory('sorotan_pelatihan')
                        ->required(),
                ])
                ->minItems(4) // wajib isi minimal 4
                ->maxItems(8) // maksimal 8
                ->defaultItems(4) // langsung muncul 4 slot kosong
                ->columnSpanFull()
                ->addActionLabel('Tambah Foto'),

        ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\ToggleColumn::make('is_published'),
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
            'index' => Pages\ListSorotanPelatihans::route('/'),
            'create' => Pages\CreateSorotanPelatihan::route('/create'),
            'edit' => Pages\EditSorotanPelatihan::route('/{record}/edit'),
        ];
    }
}
