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
     * sekarang methodnya udah ada di model Asrama.
     */
    public static function getEloquentQuery(): Builder
    {
        Asrama::syncFromConfig();

        return parent::getEloquentQuery()
            ->withCount('kamars');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Asrama')
                ->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Asrama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('gender')
                            ->label('Khusus')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ])
                            ->required(),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Fasilitas Asrama')
            ->description('Menampilkan asrama per pelatihan sesuai config kamar.php.')

            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])

            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->label('')
                        ->size(TextColumn\TextColumnSize::Large)
                        ->weight('bold')
                        ->searchable()
                        ->sortable()
                        ->extraAttributes([
                            'class' => 'text-indigo-800 text-xl font-extrabold tracking-wide',
                        ]),

                    // tampilkan pelatihan biar jelas ini asrama pelatihan mana
                    TextColumn::make('pelatihan.nama_pelatihan')
                        ->label('Pelatihan')
                        ->wrap()
                        ->limit(60)
                        ->extraAttributes([
                            'class' => 'text-slate-600 text-sm',
                        ]),

                    ColumnGrid::make(2)->schema([
                        BadgeColumn::make('kamars_count')
                            ->label('Jumlah Kamar')
                            ->color('primary')
                            ->alignCenter(),

                        BadgeColumn::make('total_beds')
                            ->label('Jumlah Bed')
                            ->state(fn (Asrama $record) =>
                                $record->kamars()->sum('total_beds')
                            )
                            ->color('success')
                            ->alignCenter(),
                    ]),
                ])
                ->space(3)
                ->extraAttributes([
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
                    ->color('warning')
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
