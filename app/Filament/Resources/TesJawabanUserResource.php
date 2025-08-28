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
                ->nullable(),

            Forms\Components\TextInput::make('nilai_jawaban')
                ->numeric()
                ->min(1)
                ->max(5)
                ->label('Nilai (Likert)')
                ->nullable(),

            Forms\Components\Textarea::make('jawaban_teks')
                ->label('Jawaban Esai / Teks')
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('percobaan.peserta.nama')->label('Nama Siswa'),
                Tables\Columns\TextColumn::make('percobaan.peserta.instansi')->label('Instansi'),
                Tables\Columns\TextColumn::make('percobaan.tes.tipe')->label('Tipe Tes'),
                Tables\Columns\TextColumn::make('percobaan.tes.sub_tipe')->label('Sub-Tipe'),
                Tables\Columns\TextColumn::make('percobaan.tes.bidang.nama_bidang')->label('Bidang Keahlian'),
                Tables\Columns\TextColumn::make('pertanyaan.teks_pertanyaan')->label('Pertanyaan'),
                Tables\Columns\TextColumn::make('opsiJawabans.teks_opsi')->label('Jawaban Pilihan'),
                Tables\Columns\TextColumn::make('jawaban_teks')->label('Jawaban Teks'),
                Tables\Columns\TextColumn::make('nilai_jawaban')->label('Nilai Likert'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Dikirim'),
            ])
            ->filters([
                // Filter sub-tipe tes
                Tables\Filters\Filter::make('sub_tipe')
                    ->label('Sub-Tipe Tes')
                    ->form([
                        Forms\Components\Select::make('sub_tipe')
                            ->options([
                                'pre-test' => 'Pre-Test',
                                'post-test' => 'Post-Test',
                            ])
                            ->placeholder('Pilih Sub-Tipe'),
                    ])
                    ->query(fn($query, array $data) => 
                        $data['sub_tipe'] 
                            ? $query->whereHas('percobaan.tes', fn($q) => $q->where('sub_tipe', $data['sub_tipe'])) 
                            : $query
                    ),

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
