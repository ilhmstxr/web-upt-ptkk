<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\KontenProgramPelatihanResource\Pages;
use App\Models\KontenProgramPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class KontenProgramPelatihanResource extends Resource
{
    protected static ?string $model = KontenProgramPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationLabel = 'Foto Program Pelatihan';

    protected static ?string $modelLabel = 'Foto Program Pelatihan';

    protected static ?string $pluralModelLabel = 'Foto Program Pelatihan';

    protected static ?string $cluster = KontenWebsite::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')
                ->label('Judul Program')
                ->required()
                ->maxLength(255)
                // saat create: bisa diisi, saat edit: dikunci
                ->disabled(fn (?Model $record) => $record !== null),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(6)
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('hero_image')
                ->label('Foto Utama (Hero)')
                ->image()
                ->directory('konten-pelatihan/hero')
                ->visibility('public'),

            Forms\Components\FileUpload::make('galeri')
                ->label('Galeri Foto')
                ->multiple()
                ->image()
                ->directory('konten-pelatihan/galeri')
                ->visibility('public'),
        ]);
    }

 public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('judul')
                ->label('Program')
                ->searchable(),

            Tables\Columns\TextColumn::make('deskripsi')
                ->limit(60),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),   // <= TAMBAHKAN INI
        ])
        ->headerActions([
            // tetap kosong gapapa
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),   // kalau mau bulk delete juga
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
            'index' => Pages\ListKontenProgramPelatihans::route('/'),
            'edit'  => Pages\EditKontenProgramPelatihan::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return true;
    }

   public static function canDelete(Model $record): bool
{
    return true; // atau hapus method ini sekalian
}
}
