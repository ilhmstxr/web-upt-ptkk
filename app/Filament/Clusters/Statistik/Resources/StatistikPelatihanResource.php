<?php

namespace App\Filament\Clusters\Statistik\Resources;

use App\Filament\Clusters\Statistik;
use App\Filament\Clusters\Statistik\Resources\StatistikPelatihanResource\Pages;
use App\Models\StatistikPelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatistikPelatihanResource extends Resource
{
    protected static ?string $model = StatistikPelatihan::class;

    protected static ?string $cluster = Statistik::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Foto Statistik Pelatihan';
    protected static ?string $modelLabel = 'Foto Statistik Pelatihan';
    protected static ?string $pluralModelLabel = 'Foto Statistik Pelatihan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Statistik')
                ->schema([
                    Forms\Components\Select::make('pelatihan_id')
                        ->label('Pelatihan')
                        ->relationship('pelatihan', 'nama_pelatihan')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\TextInput::make('batch')
                        ->label('Batch')
                        ->required()
                        ->maxLength(150)
                        ->default(fn () => now()->format('Y')),
                ])
                ->columns(2),

            Forms\Components\Section::make('Foto Pelatihan')
                ->description('Foto akan tampil sebagai slider di halaman statistik.')
                ->schema([
                    Forms\Components\FileUpload::make('foto_galeri')
                        ->label('Foto Pelatihan')
                        ->disk('public')
                        ->directory('statistik-pelatihan')
                        ->multiple()
                        ->reorderable()
                        ->image()
                        ->imageEditor()
                        ->maxFiles(8)
                        ->maxSize(4096)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('batch')
                    ->label('Batch')
                    ->sortable(),

                Tables\Columns\TextColumn::make('foto_galeri')
                    ->label('Jumlah Foto')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            return is_array($decoded) ? count($decoded) : 0;
                        }
                        return is_array($state) ? count($state) : 0;
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Update')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStatistikPelatihans::route('/'),
            'create' => Pages\CreateStatistikPelatihan::route('/create'),
            'edit'   => Pages\EditStatistikPelatihan::route('/{record}/edit'),
        ];
    }
}
