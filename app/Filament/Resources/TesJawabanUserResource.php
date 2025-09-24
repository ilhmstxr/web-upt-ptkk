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
                ->label('Percobaan')
                ->required(),

            Forms\Components\Select::make('pertanyaan_id')
                ->relationship('pertanyaan', 'teks_pertanyaan')
                ->label('Pertanyaan')
                ->required(),

            Forms\Components\Select::make('opsi_jawaban_id')
                ->relationship('opsiJawaban', 'teks_opsi') // singular
                ->label('Jawaban Pilihan')
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
                Tables\Columns\TextColumn::make('percobaan.peserta.nama')
                    ->label('Nama Peserta')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('percobaan.peserta.instansi')
                    ->label('Instansi')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('percobaan.tes.tipe')
                    ->label('Tipe Tes'),

                Tables\Columns\TextColumn::make('percobaan.tes.sub_tipe')
                    ->label('Sub-Tipe'),

                Tables\Columns\TextColumn::make('percobaan.tes.bidang.nama_bidang')
                    ->label('Bidang Keahlian'),

                Tables\Columns\TextColumn::make('pertanyaan.teks_pertanyaan')
                    ->label('Pertanyaan')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('opsiJawaban.teks_opsi')
                    ->label('Jawaban Pilihan'),

                Tables\Columns\TextColumn::make('jawaban_teks')
                    ->label('Jawaban Teks')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('nilai_jawaban')
                    ->label('Nilai Likert'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Filter sub-tipe tes
                Tables\Filters\SelectFilter::make('sub_tipe')
                    ->label('Sub-Tipe Tes')
                    ->options([
                        'pre-test' => 'Pre-Test',
                        'post-test' => 'Post-Test',
                    ])
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->whereHas('percobaan.tes', fn($q) => $q->where('sub_tipe', $value));
                        }
                    }),

                // Filter bidang keahlian (pakai whereHas biar aman)
                Tables\Filters\SelectFilter::make('bidang')
                    ->label('Bidang Keahlian')
                    ->options(function () {
                        return \App\Models\Bidang::pluck('nama_bidang', 'id')->toArray();
                    })
                    ->query(function ($query, $value) {
                        if ($value) {
                            $query->whereHas('percobaan.tes.bidang', fn($q) => $q->where('id', $value));
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                // aktifkan jika sudah install filament/export
                // \Filament\Tables\Actions\ExportBulkAction::make(),
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
