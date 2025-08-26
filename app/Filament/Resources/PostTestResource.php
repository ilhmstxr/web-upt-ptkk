<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostTestResource\Pages;
use App\Models\PostTest;
use App\Models\Pelatihan;
use App\Models\Bidang;
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

            Forms\Components\Select::make('bidang_id')
                ->label('Bidang Keahlian')
                ->options(Bidang::all()->pluck('nama_bidang', 'id'))
                ->searchable()
                ->required(),

            Forms\Components\Repeater::make('soal')
                ->label('Soal Post-Test')
                ->schema([
                    Forms\Components\TextInput::make('nomor')
                        ->label('Nomor Soal')
                        ->numeric()
                        ->required(),
                    Forms\Components\Textarea::make('question')
                        ->label('Pertanyaan')
                        ->required(),
                    Forms\Components\TextInput::make('option_a')
                        ->label('Pilihan A')
                        ->required(),
                    Forms\Components\TextInput::make('option_b')
                        ->label('Pilihan B')
                        ->required(),
                    Forms\Components\TextInput::make('option_c')
                        ->label('Pilihan C')
                        ->required(),
                    Forms\Components\TextInput::make('option_d')
                        ->label('Pilihan D')
                        ->required(),
                    Forms\Components\Select::make('correct_answer')
                        ->label('Jawaban Benar')
                        ->options([
                            'A' => 'A',
                            'B' => 'B',
                            'C' => 'C',
                            'D' => 'D',
                        ])
                        ->required(),
                ])
                ->columns(2)
                ->createItemButtonLabel('Tambah Soal')
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

                Tables\Columns\TextColumn::make('bidang.nama_bidang')
                    ->label('Bidang Keahlian')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => $record->bidang?->nama_bidang ?? '-'),

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
            'index'  => Pages\ListPostTests::route('/'),
            'create' => Pages\CreatePostTest::route('/create'),
            'edit'   => Pages\EditPostTest::route('/{record}/edit'),
        ];
    }
}
