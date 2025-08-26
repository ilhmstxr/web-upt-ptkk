<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostTestResource\Pages;
use App\Models\PostTest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostTestResource extends Resource
{
    protected static ?string $model = PostTest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Manajemen Soal';
    protected static ?string $navigationLabel = 'Post-Test';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('pelatihan_id')
                ->label('Pelatihan')
                ->relationship('pelatihan', 'nama_pelatihan')
                ->required(),

            Forms\Components\TextInput::make('nomor')
                ->label('Nomor Pertanyaan')
                ->numeric()
                ->required(),

            Forms\Components\Textarea::make('question')
                ->label('Pertanyaan')
                ->required(),

            Forms\Components\TextInput::make('option_a')->label('Pilihan A')->required(),
            Forms\Components\TextInput::make('option_b')->label('Pilihan B')->required(),
            Forms\Components\TextInput::make('option_c')->label('Pilihan C')->required(),
            Forms\Components\TextInput::make('option_d')->label('Pilihan D')->required(),

            Forms\Components\Select::make('correct_answer')
                ->label('Jawaban Benar')
                ->options([
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pelatihan.nama_pelatihan')
                    ->label('Pelatihan')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('nomor')
                    ->label('No.')
                    ->sortable(),

                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\TextColumn::make('correct_answer')
                    ->label('Jawaban'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePostTests::route('/'),
        ];
    }
}
