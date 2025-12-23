<?php

namespace App\Filament\Clusters\KontenWebsite\Resources;

use App\Filament\Clusters\KontenWebsite;
use App\Filament\Clusters\KontenWebsite\Resources\CeritaKamiResource\Pages;
use App\Models\CeritaKami;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CeritaKamiResource extends Resource
{
    protected static ?string $model = CeritaKami::class;

    protected static ?string $cluster = KontenWebsite::class;

    protected static ?string $navigationLabel = 'Cerita Kami';
    protected static ?string $pluralLabel = 'Cerita Kami';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 10;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Konten Cerita Kami')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Foto utama')
                        ->image()
                        ->directory('cerita-kami')
                        ->imageEditor()
                        ->columnSpanFull()
                        ->required(),

                    Forms\Components\RichEditor::make('content')
                        ->label('Isi Cerita / Deskripsi')
                        ->required()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->circular(),

                Tables\Columns\TextColumn::make('content')
                    ->label('Isi Ringkas')
                    ->limit(50)
                    ->html() // Render HTML dari RichEditor
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
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
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCeritaKamis::route('/'),
            'create' => Pages\CreateCeritaKami::route('/create'),
            'edit'   => Pages\EditCeritaKami::route('/{record}/edit'),
        ];
    }
}
