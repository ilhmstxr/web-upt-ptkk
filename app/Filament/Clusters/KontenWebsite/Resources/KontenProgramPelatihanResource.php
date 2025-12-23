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

class KontenProgramPelatihanResource extends Resource
{
    protected static ?string $model = KontenProgramPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Program Pelatihan';
    protected static ?string $modelLabel = 'Program Pelatihan';
    protected static ?string $pluralModelLabel = 'Program Pelatihan';
    protected static ?string $cluster = KontenWebsite::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('judul')
                ->label('Judul Program')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('deskripsi')
                ->label('Deskripsi')
                ->rows(6)
                ->columnSpanFull(),

            Forms\Components\FileUpload::make('hero_image')
                ->label('Foto Utama (Hero)')
                ->image()
                ->disk('public')
                ->directory('konten-website/program-pelatihan/hero')
                ->maxSize(4096),

            Forms\Components\FileUpload::make('galeri')
                ->label('Galeri Foto (Maksimal 3 Foto)')
                ->multiple()
                ->reorderable()
                ->maxFiles(3)                 // ✅ limit UI
                ->image()
                ->imagePreviewHeight('150')
                ->disk('public')
                ->directory('konten-website/program-pelatihan/galeri')
                ->maxSize(4096)
                ->helperText('Upload maksimal 3 foto untuk galeri bawah.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(60),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Update')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            // ❌ Jangan tambah CreateAction di sini biar gak double
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKontenProgramPelatihans::route('/'),
            'create' => Pages\CreateKontenProgramPelatihan::route('/create'),
            'edit'   => Pages\EditKontenProgramPelatihan::route('/{record}/edit'),
        ];
    }
}

