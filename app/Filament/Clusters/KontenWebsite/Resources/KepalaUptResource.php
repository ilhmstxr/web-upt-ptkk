<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource\Pages;
use App\Filament\Clusters\KontenWebsite\Resources\KepalaUptResource\RelationManagers;
use App\Models\KepalaUpt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KepalaUptResource extends Resource
{
    protected static ?string $model = KepalaUpt::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationLabel = "Kepala UPT";

    protected static ?string $modelLabel = 'Kepala UPT';

    protected static ?string $pluralModelLabel = 'Kepala UPT';

   public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\Group::make([
            Forms\Components\FileUpload::make('foto')
                ->label('Foto Kepala UPT')
                ->image()
                ->disk('public') // ⬅ penting! simpan di storage/app/public
                ->directory('konten-website/kepala-upt') // ⬅ folder khusus agar rapi
                ->imageEditor()
                ->downloadable()
                ->maxSize(2048),

            Forms\Components\TextInput::make('nama_kepala_upt')
                ->label('Nama Kepala UPT')
                ->required()
                ->maxLength(255),

            Forms\Components\RichEditor::make('sambutan')
                ->label('Teks Sambutan')
                ->required()
                ->toolbarButtons([
                    'bold', 'italic', 'link', 'bulletList', 'h2', 'h3'
                ]),
        ])->columnSpanFull()
    ]);
}


   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\ImageColumn::make('foto')
                ->label('Foto')
                ->square(),

            Tables\Columns\TextColumn::make('nama_kepala_upt')
                ->label('Nama Kepala UPT')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('sambutan')
                ->label('Sambutan')
                ->limit(50),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),   // ✅ tombol Delete per baris
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(), // ✅ bulk delete (opsional)
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
            'index' => Pages\ListKepalaUpts::route('/'),
            'create' => Pages\CreateKepalaUpt::route('/create'),
            'edit' => Pages\EditKepalaUpt::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return \App\Models\KepalaUpt::count() === 0;
    }
}
