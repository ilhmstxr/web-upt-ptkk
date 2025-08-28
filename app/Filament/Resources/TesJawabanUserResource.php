<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TesJawabanUserResource\Pages;
use App\Models\JawabanUser;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;

class TesJawabanUserResource extends Resource
{
    protected static ?string $model = JawabanUser::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static ?string $navigationLabel = 'Jawaban Peserta';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('percobaan_id')
                ->relationship('percobaan', 'id')
                ->required(),

            Forms\Components\Select::make('pertanyaan_id')
                ->relationship('pertanyaan', 'teks_pertanyaan')
                ->required(),

            Forms\Components\Select::make('opsi_jawabans_id')
                ->relationship('opsiJawabans', 'teks_opsi')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('percobaan.peserta.nama')->label('Nama Siswa'),
                Tables\Columns\TextColumn::make('percobaan.peserta.instansi')->label('Instansi'),
                Tables\Columns\TextColumn::make('percobaan.tes.tipe')->label('Tipe Tes'),
                Tables\Columns\TextColumn::make('percobaan.tes.bidang.nama_bidang')->label('Bidang Keahlian'),
                Tables\Columns\TextColumn::make('percobaan.hitungSkor')->label('Skor'),
                Tables\Columns\TextColumn::make('percobaan.hitungSkorPersen')->label('Skor (%)'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Jawaban Dikirim'),
            ])
            ->filters([
                // Filter tipe tes menggunakan filter closure aman
                Tables\Filters\SelectFilter::make('tipe_tes')
                    ->label('Tipe Tes')
                    ->options([
                        'pre-test' => 'Pre-Test',
                        'post-test' => 'Post-Test',
                    ])
                    ->filter(function ($query, $value) {
                        $query->whereHas('percobaan.tes', fn($q) => $q->where('tipe', $value));
                    }),

                // Filter bidang keahlian
                Tables\Filters\SelectFilter::make('bidang')
                    ->label('Bidang Keahlian')
                    ->relationship('percobaan.tes.bidang', 'nama_bidang'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                \Filament\Tables\Actions\ExportBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTesJawabanUser::route('/'),
            'create' => Pages\CreateTesJawabanUser::route('/create'),
            'edit' => Pages\EditTesJawabanUser::route('/{record}/edit'),
        ];
    }
}
