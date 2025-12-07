<?php

namespace App\Filament\Clusters\Fasilitas\Resources;

use App\Filament\Clusters\Fasilitas;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\Pages;
use App\Filament\Clusters\Fasilitas\Resources\AsramaResource\RelationManagers;
use App\Models\Asrama;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// App\Filament\Clusters\Fasilitas\Resources\AsramaResource.php

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;

    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Asrama')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama') // <-- ganti name -> nama
                                    ->label('Nama Asrama')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('gender')
                                    ->label('Khusus')
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                        'Campur'    => 'Campur',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat / Keterangan Tambahan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Fasilitas Asrama')
            ->description('Ringkasan kapasitas dan status fasilitas setiap asrama berdasarkan jumlah kamar dan bed.')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Asrama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('Khusus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Laki-laki' => 'info',
                        'Perempuan' => 'danger',
                        'Campur'    => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('kamars_count')
                    ->counts('kamars')
                    ->label('Jumlah Kamar')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('deskripsi_fasilitas')
                    ->label('Deskripsi Fasilitas')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\KamarRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'   => Pages\ListAsramas::route('/'),
            'create'  => Pages\CreateAsrama::route('/create'),
            'edit'    => Pages\EditAsrama::route('/{record}/edit'),
            // NANTI: halaman otomasi & riwayat bisa ditaruh di cluster Fasilitas, bukan di resource ini.
        ];
    }
}
