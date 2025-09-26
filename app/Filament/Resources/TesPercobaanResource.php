<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesPercobaanResource\Pages;
use App\Models\Percobaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesPercobaanResource extends Resource
{
    protected static ?string $model = Percobaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationGroup = 'Tes & Percobaan';
    protected static ?string $navigationLabel = 'Tes Percobaan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('peserta_id')
                ->label('Peserta')
                ->relationship('peserta', 'nama')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('tes_id')
                ->label('Tes')
                ->relationship('tes', 'judul')
                ->searchable()
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_mulai')
                ->label('Waktu Mulai')
                ->required(),

            Forms\Components\DateTimePicker::make('waktu_selesai')
                ->label('Waktu Selesai')
                ->required(fn (Get $get) => filled($get('skor')))
                ->minDateTime(fn (Get $get) => $get('waktu_mulai')),

            Forms\Components\TextInput::make('skor')
                ->numeric()
                ->label('Skor')
                ->required(fn (Get $get) => filled($get('waktu_selesai'))),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // pastikan semua relasi diload agar baris tidak “hilang”
            ->modifyQueryUsing(fn ($query) => $query->with(['peserta', 'tes']))
            ->columns([
                Tables\Columns\TextColumn::make('peserta.nama')
                    ->label('Peserta')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tes.judul')
                    ->label('Tes')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Selesai')
                    // $state sudah Carbon karena di-cast di model
                    ->formatStateUsing(fn ($state) => $state?->format('d M Y H:i') ?? 'Belum selesai')
                    ->sortable(),

                Tables\Columns\TextColumn::make('skor')
                    ->label('Skor')
                    ->formatStateUsing(fn ($state) => $state ?? 'Belum dinilai'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->waktu_selesai ? 'Selesai' : 'Proses')
                    ->colors([
                        'success' => 'Selesai',
                        'warning' => 'Proses',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Selesai',
                        'heroicon-o-clock' => 'Proses',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTesPercobaan::route('/'),
            'create' => Pages\CreateTesPercobaan::route('/create'),
            'edit' => Pages\EditTesPercobaan::route('/{record}/edit'),
        ];
    }
}
