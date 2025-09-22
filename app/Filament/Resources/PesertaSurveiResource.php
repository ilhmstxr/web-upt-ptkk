<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesertaSurveiResource\Pages;
use App\Filament\Resources\PesertaSurveiResource\RelationManagers;
use App\Models\PesertaSurvei;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PesertaSurveiResource extends Resource
{
    protected static ?string $model = PesertaSurvei::class;
    protected static ?string $navigationLabel   = 'Peserta Survei';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable()->sortable(),
                TextColumn::make('bidang.nama_bidang')->searchable()->sortable(),
                TextColumn::make('created_at')->searchable()->sortable(),
            ])
            ->filters([
                // ▼▼▼ TAMBAHKAN FILTER INI ▼▼▼
                SelectFilter::make('status')
                    ->label('Status Pengisian')
                    ->options([
                        'sudah' => 'Sudah Mengisi',
                        'belum' => 'Belum Mengisi',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if ($data['value'] === 'sudah') {
                            return $query->whereHas('percobaan');
                        }
                        if ($data['value'] === 'belum') {
                            return $query->whereDoesntHave('percobaan');
                        }
                        return $query;
                    }),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesertaSurveis::route('/'),
            'create' => Pages\CreatePesertaSurvei::route('/create'),
            'edit' => Pages\EditPesertaSurvei::route('/{record}/edit'),
        ];
    }
}
