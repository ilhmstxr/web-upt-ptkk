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
     * ✅ sebelum list tampil, sync dulu dari config kamar.php
     */
    public static function getEloquentQuery(): Builder
    {
        Asrama::syncFromConfig();

        return parent::getEloquentQuery()
            ->withCount('kamars'); // hasilkan kamars_count
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
            ->description('Menampilkan nama asrama, jumlah kamar, dan jumlah bed.')

            // ✅ grid card
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])

            ->columns([
                Stack::make([
                    // ===== Nama Asrama =====
                    TextColumn::make('nama')
                        ->label('')
                        ->size(TextColumn\TextColumnSize::Large)
                        ->weight('bold')
                        ->searchable()
                        ->sortable()
                        ->extraAttributes([
                            'class' => 'text-indigo-800 text-xl font-extrabold tracking-wide',
                        ]),

                    // ===== Stats kamar & bed =====
                    ColumnGrid::make(2)->schema([
                        BadgeColumn::make('kamars_count')
                            ->label('Jumlah Kamar')
                            ->color('primary') // ✅ bukan gray
                            ->icon('heroicon-o-building-office-2')
                            ->alignCenter()
                            ->extraAttributes([
                                'class' => 'text-white font-bold',
                            ]),

                        BadgeColumn::make('total_bed_config')
                            ->label('Jumlah Bed')
                            ->color('success') // ✅ bukan gray
                            ->icon('heroicon-o-user-group')
                            ->alignCenter()
                            ->extraAttributes([
                                'class' => 'text-white font-bold',
                            ]),
                    ]),
                ])
                ->space(3)
                ->extraAttributes([
                    // ✅ full warna-warni, tanpa white/gray
                    'class' => '
                        p-6 rounded-3xl shadow-lg border-2 border-indigo-300
                        bg-gradient-to-br from-indigo-100 via-pink-100 to-yellow-100
                    ',
                ]),
            ])

            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')  // ✅ warna-warni
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
