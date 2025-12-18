<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\SorotanPelatihanResource\Pages;
use App\Models\SorotanPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SorotanPelatihanResource extends Resource
{
    protected static ?string $model = SorotanPelatihan::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';
    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationLabel = "Sorotan Pelatihan (4-8 Foto)";
    protected static ?string $modelLabel = 'Sorotan Pelatihan (4-8 Foto)';
    protected static ?string $pluralModelLabel = 'Sorotan Pelatihan (4-8 Foto)';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Sorotan')
            ->schema([
            Forms\Components\TextInput::make('title')
                ->label('Judul Program pelatihan')
                ->required()
                ->maxLength(50),

            Forms\Components\Textarea::make('description')
                ->label('Deskripsi Singkat')
                ->rows(3)
                ->columnSpanFull()
                ->placeholder('Deskripsi singkat tentang program ini...'),

            Forms\Components\Toggle::make('is_published')
                ->label('Publish')
                ->default(true)
                ->onColor('success')
                ->offColor('danger'),
                ]),

            // 🔥 Upload foto slider (fix ke 6 gambar)
            Forms\Components\Section::make('Galeri Foto')
                ->description('Upload minimal 4 dan maksimal 8 foto.')
                ->schema([
                    Forms\Components\FileUpload::make('photos')
                        ->label('Foto Dokumentasi')
                        ->disk('public')            
                        ->directory('sorotan')   
                        ->multiple()            
                        ->reorderable()            
                        ->image()               
                        ->imageEditor()            
                        ->minFiles(4)               
                        ->maxFiles(8)            
                        ->maxSize(5120)          
                        ->columnSpanFull()
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Sorotan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Publish'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index'  => Pages\ListSorotanPelatihans::route('/'),
            'create' => Pages\CreateSorotanPelatihan::route('/create'),
            'edit'   => Pages\EditSorotanPelatihan::route('/{record}/edit'),
        ];
    }
}
