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

use Filament\Tables\Columns\Layout\Grid as ColumnGrid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use Illuminate\Database\Eloquent\Builder;

class AsramaResource extends Resource
{
    protected static ?string $model = Asrama::class;
    protected static ?string $cluster = Fasilitas::class;

    protected static ?string $navigationIcon  = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel = 'Asrama';
    protected static ?string $modelLabel      = 'Asrama';
    protected static ?string $pluralModelLabel = 'Asrama';

    /**
     * ✅ Ini kunci: sebelum list tampil, sync dulu dari config kamar.php
     */
    public static function getEloquentQuery(): Builder
    {
        Asrama::syncFromConfig();

        return parent::getEloquentQuery()
            ->withCount('kamars'); // supaya kamars_count jalan
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Asrama')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('nama')
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
            ->description('Ringkasan kapasitas, kondisi kamar, dan informasi bed tiap asrama berdasarkan config kamar.php.')

            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])

            ->columns([
                Stack::make([
                    ColumnGrid::make(12)->schema([
                        TextColumn::make('nama')
                            ->label('')
                            ->size(TextColumn\TextColumnSize::Large)
                            ->weight('bold')
                            ->searchable()
                            ->sortable()
                            ->columnSpan(8),

                        BadgeColumn::make('gender')
                            ->label('')
                            ->columnSpan(4)
                            ->colors([
                                'info'    => 'Laki-laki',
                                'danger'  => 'Perempuan',
                                'warning' => 'Campur',
                            ])
                            ->alignEnd(),
                    ]),

                    ColumnGrid::make(3)->schema([
                        BadgeColumn::make('kamars_count')
                            ->label('Jumlah Kamar (DB)')
                            ->color('gray')
                            ->icon('heroicon-o-building-office-2')
                            ->alignCenter(),

                        BadgeColumn::make('total_bed_config')
                            ->label('Total Bed (Config)')
                            ->color('success')
                            ->icon('heroicon-o-user-group')
                            ->alignCenter(),

                        BadgeColumn::make('kamar_rusak_config')
                            ->label('Kamar Rusak (Config)')
                            ->color(fn ($state) => (int) $state > 0 ? 'danger' : 'gray')
                            ->icon('heroicon-o-exclamation-triangle')
                            ->alignCenter(),
                    ]),

                    TextColumn::make('deskripsi_fasilitas')
                        ->label('')
                        ->wrap()
                        ->color('gray')
                        ->extraAttributes([
                            'class' => 'text-sm leading-relaxed mt-1',
                        ]),

                    TextColumn::make('alamat')
                        ->label('')
                        ->wrap()
                        ->toggleable(isToggledHiddenByDefault: true)
                        ->color('gray')
                        ->extraAttributes([
                            'class' => 'text-xs mt-1',
                        ]),
                ])
                ->space(2)
                ->extraAttributes([
                    'class' => 'p-5 bg-white rounded-2xl shadow-sm ring-1 ring-gray-200',
                ]),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->button(),

                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->button(),
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
            'index'  => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'edit'   => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }
}
