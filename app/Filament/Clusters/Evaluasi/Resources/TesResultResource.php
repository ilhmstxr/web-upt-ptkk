<?php

namespace App\Filament\Clusters\Evaluasi\Resources;

use App\Filament\Clusters\Evaluasi;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Pages;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\RelationManagers;
use App\Models\TesResult; // Check model name
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Evaluasi\Resources\TesResultResource\Widgets\TesResultChart;

class TesResultResource extends Resource
{
    protected static ?string $model = \App\Models\Percobaan::class; // Assuming Percobaan based on context

    protected static ?string $cluster = Evaluasi::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hasil Tes')
                    ->schema([
                        Forms\Components\TextInput::make('peserta_name')
                            ->label('Peserta')
                            ->formatStateUsing(fn ($record) => $record->peserta->name ?? '-')
                            ->disabled(),
                        Forms\Components\TextInput::make('tes_judul')
                            ->label('Judul Tes')
                            ->formatStateUsing(fn ($record) => $record->tes->judul ?? '-')
                            ->disabled(),
                        Forms\Components\TextInput::make('skor')
                            ->numeric()
                            ->disabled(),
                        Forms\Components\TextInput::make('lulus')
                            ->formatStateUsing(fn ($state) => $state ? 'Lulus' : 'Tidak Lulus')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('waktu_mulai')
                            ->disabled(),
                        Forms\Components\DateTimePicker::make('waktu_selesai')
                            ->disabled(),
                    ])->columns(3),

                Forms\Components\Section::make('Detail Jawaban')
                    ->schema([
                        Forms\Components\Repeater::make('jawabanUser')
                            ->relationship()
                            ->schema([
                                Forms\Components\Textarea::make('pertanyaan_teks')
                                    ->label('Pertanyaan')
                                    ->formatStateUsing(fn ($record) => $record->pertanyaan->teks_pertanyaan ?? '-')
                                    ->disabled()
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('jawaban_user')
                                    ->label('Jawaban Peserta')
                                    ->formatStateUsing(fn ($record) => $record->opsiJawaban->teks_opsi ?? '-')
                                    ->disabled(),
                                Forms\Components\TextInput::make('status_jawaban')
                                    ->label('Status')
                                    ->formatStateUsing(fn ($record) => ($record->opsiJawaban->apakah_benar ?? false) ? 'Benar' : 'Salah')
                                    ->disabled(),
                            ])
                            ->columns(2)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('peserta.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                Tables\Columns\TextColumn::make('skor')
                    ->label('Nilai Akhir')
                    ->numeric()
                    ->sortable()
                    ->alignCenter()
                    ->weight('bold')
                    ->color(fn ($state) => $state >= 75 ? 'success' : 'warning'), // Assuming 75 is KKM

                Tables\Columns\TextColumn::make('lulus')
                    ->label('Status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning') // HTML uses warning for Remedial
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Lulus' : 'Remedial'),

                Tables\Columns\TextColumn::make('durasi_pengerjaan')
                    ->label('Waktu Pengerjaan')
                    ->alignRight()
                    ->getStateUsing(fn ($record) => $record->waktu_mulai && $record->waktu_selesai 
                        ? \Carbon\Carbon::parse($record->waktu_mulai)->diffInMinutes(\Carbon\Carbon::parse($record->waktu_selesai)) . ' Menit'
                        : '-'),
            ])

            ->filters([
                Tables\Filters\SelectFilter::make('tes')
                    ->relationship('tes', 'judul'),
                Tables\Filters\TernaryFilter::make('lulus')
                    ->label('Status Kelulusan'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [
            //
        ];
    }


    public static function getWidgets(): array
    {
        return [
            TesResultChart::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTesResults::route('/'),
            'create' => Pages\CreateTesResult::route('/create'),
            'edit' => Pages\EditTesResult::route('/{record}/edit'),
        ];
    }
}
