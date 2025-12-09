<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\Pages;
use App\Filament\Clusters\Fasilitas\Resources\PenempatanAsramaResource\RelationManagers;

use App\Models\Pelatihan;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder;

class PenempatanAsramaResource extends Resource
{
    /**
     * ✅ BASIS LIST = PELATIHAN
     * klik pelatihan -> masuk ke tabel peserta + asrama
     */
    protected static ?string $model = Pelatihan::class;
    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon   = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel  = 'Penempatan Asrama';
    protected static ?string $modelLabel       = 'Pelatihan';
    protected static ?string $pluralModelLabel = 'Pelatihan';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // read-only
    }

    /**
     * ✅ load count peserta biar list pelatihan informatif
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount('pendaftaranPelatihan');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Daftar Pelatihan')
            ->description('Klik pelatihan untuk melihat peserta dan penempatan asrama.')
            ->columns([
                Tables\Columns\TextColumn::make('nama_pelatihan')
                    ->label('Nama Pelatihan')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(80),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'belum dimulai' => 'info',
                        'selesai' => 'gray',
                        default => 'gray',
                    })
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pendaftaran_pelatihan_count')
                    ->label('Total Peserta')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_mulai')
                    ->label('Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_selesai')
                    ->label('Selesai')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Kelola Asrama')
                    ->icon('heroicon-o-home-modern'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PesertaPenempatanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenempatanAsramas::route('/'),
            'view'  => Pages\ViewPenempatanAsrama::route('/{record}'),
        ];
    }
}
