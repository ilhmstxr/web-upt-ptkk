<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelatihanResource\Pages;
use App\Models\Pelatihan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PelatihanResource extends Resource
{
    protected static ?string $model = Pelatihan::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Kegiatan Pelatihan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->label('Judul Pelatihan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->disabled()
                    ->helperText('Slug akan dibuat otomatis berdasarkan judul.'),

                Forms\Components\TextInput::make('durasi')
                    ->label('Durasi')
                    ->placeholder('Misal: 5 hari'),

                Forms\Components\TextInput::make('waktu')
                    ->label('Jam Pelaksanaan')
                    ->placeholder('Misal: 08:00 - 16:00'),

                Forms\Components\TextInput::make('tujuan')
                    ->label('Tujuan Pelatihan'),

                Forms\Components\TextInput::make('target_peserta')
                    ->label('Target Peserta'),

                Forms\Components\Textarea::make('materi')
                    ->label('Materi Pelatihan')
                    ->rows(4),

                Forms\Components\FileUpload::make('gambar')
                    ->label('Gambar Utama')
                    ->image()
                    ->directory('pelatihan-images')
                    ->required(),

                Forms\Components\FileUpload::make('galeri')
                    ->label('Galeri Kegiatan')
                    ->multiple()
                    ->image()
                    ->directory('pelatihan-galeri'),

                Forms\Components\DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),

                Forms\Components\DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),

                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Tambahan')
                    ->rows(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Menambahkan withCount untuk menghitung relasi 'registrations'
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')->label('Thumbnail')->rounded(),
                Tables\Columns\TextColumn::make('judul')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->limit(20),
                
                // Kolom baru untuk menampilkan jumlah peserta
                Tables\Columns\TextColumn::make('registrations_count')
                    ->label('Jumlah Peserta')
                    ->counts('registrations') // Menghitung relasi 'registrations'
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_mulai')->label('Mulai')->date()->sortable(),
                Tables\Columns\TextColumn::make('tanggal_selesai')->label('Selesai')->date()->sortable(),
                Tables\Columns\TextColumn::make('durasi')->label('Durasi'),
                Tables\Columns\TextColumn::make('waktu')->label('Jam Pelaksanaan'),
                Tables\Columns\TextColumn::make('tujuan')->label('Tujuan')->limit(30)->wrap(),
                Tables\Columns\TextColumn::make('target_peserta')->label('Target Peserta')->limit(30)->wrap(),
                Tables\Columns\TextColumn::make('materi')->label('Materi')->limit(30)->wrap(),
                Tables\Columns\TextColumn::make('sertifikat')
                    ->label('Sertifikat')
                    ->formatStateUsing(fn($state) => $state ? "<a href='".asset('storage/'.$state)."' target='_blank'>Download</a>" : '-')
                    ->html(),
                Tables\Columns\TextColumn::make('galeri')
                    ->label('Jumlah Gambar')
                    ->getStateUsing(fn($record) => count($record->galeri ?? [])),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPelatihans::route('/'),
            'create' => Pages\CreatePelatihan::route('/create'),
            'edit' => Pages\EditPelatihan::route('/{record}/edit'),
        ];
    }
}