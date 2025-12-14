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

    protected static ?string $navigationIcon   = 'heroicon-o-home-modern';
    protected static ?string $navigationLabel  = 'Asrama';
    protected static ?string $modelLabel       = 'Asrama';
    protected static ?string $pluralModelLabel = 'Asrama';

    /**
     * =========================
     * QUERY
     * =========================
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    /**
     * =========================
     * FORM CREATE & EDIT
     * - Create asrama: kosong
     * - Kamar manual (tidak auto dari config)
     * =========================
     */
    public static function form(Form $form): Form
    {
        return $form->schema([

            /* ===============================
             * INFORMASI ASRAMA
             * =============================== */
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
                                'Campur'    => 'Campur',
                            ])
                            ->nullable(), // boleh kosong
                    ]),
                ]),

            /* ===============================
             * KAMAR & BED (MANUAL)
             * =============================== */
            Forms\Components\Section::make('Kamar & Bed')
                ->description('Tambah kamar secara manual')
                ->schema([

                    Forms\Components\Repeater::make('kamars')
                        ->relationship()
                        ->schema([

                            Forms\Components\TextInput::make('nomor_kamar')
                                ->label('Nomor Kamar')
                                ->numeric()
                                ->required(),

                            Forms\Components\TextInput::make('total_beds')
                                ->label('Jumlah Bed')
                                ->numeric()
                                ->minValue(0)
                                ->required(),

                        ])
                        ->columns(2)
                        ->addActionLabel('Tambah Kamar')
                        ->reorderable()
                        ->collapsible(),
                ]),
        ]);
    }

    /**
     * =========================
     * TABLE
     * =========================
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading('Fasilitas Asrama')
            ->description('Daftar asrama beserta jumlah kamar dan bed.')

            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])

            ->columns([
                Stack::make([

                    TextColumn::make('name')
                        ->size(TextColumn\TextColumnSize::Large)
                        ->weight('bold')
                        ->searchable()
                        ->sortable()
                        ->extraAttributes([
                            'class' => 'text-indigo-800 text-xl font-extrabold tracking-wide',
                        ]),

                    ColumnGrid::make(2)->schema([

                        BadgeColumn::make('kamars_count')
                            ->label('Kamar')
                            ->icon('heroicon-o-home')
                            ->counts('kamars')
                            ->color('primary')
                            ->alignCenter(),

                        BadgeColumn::make('total_beds')
                            ->label('Bed')
                            ->icon('heroicon-o-rectangle-stack')
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

    /**
     * =========================
     * RELATIONS
     * =========================
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\KamarRelationManager::class,
        ];
    }

    /**
     * =========================
     * PAGES
     * =========================
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAsramas::route('/'),
            'create' => Pages\CreateAsrama::route('/create'),
            'edit'   => Pages\EditAsrama::route('/{record}/edit'),
        ];
    }
}
