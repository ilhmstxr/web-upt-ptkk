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

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Konten Cerita Kami')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul internal')
                        ->helperText('Hanya untuk admin, di landing heading tetap teks statis.')
                        ->maxLength(150)
                        ->required(),

                    Forms\Components\Toggle::make('is_published')
                        ->label('Tampilkan di landing?')
                        ->default(true),

                    Forms\Components\FileUpload::make('image')
                        ->label('Foto utama (landing)')
                        ->image()
                        ->directory('cerita-kami')
                        ->imageEditor()
                        ->imagePreviewHeight('200')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('excerpt')
                        ->label('Paragraf pendek untuk Landing')
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('content')
                        ->label('Konten lengkap (opsional)')
                        ->columnSpanFull(),
                ]),
        ]);
    }

   public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('title')
                ->label('Judul')
                ->searchable()
                ->sortable(),

            Tables\Columns\IconColumn::make('is_published')
                ->label('Tayang')
                ->boolean(),

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
            'index' => Pages\ListCeritaKamis::route('/'),
            'create' => Pages\CreateCeritaKami::route('/create'),
            'edit' => Pages\EditCeritaKami::route('/{record}/edit'),
        ];
    }
}
